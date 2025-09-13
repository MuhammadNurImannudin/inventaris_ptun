<?php
require_once __DIR__.'/../config/database.php';

/* ---------- SELECT (dengan filter + search) ---------- */
function getAllBarang($filters = []){
    global $conn;

    $where = [];
    $params = [];
    $types  = '';

    // Search (cari di beberapa kolom)
    if (!empty($filters['search'])) {
        $where[] = "(b.nama_barang LIKE ? 
                  OR b.nomor_inventaris LIKE ? 
                  OR b.nomor_serial LIKE ?
                  OR b.kondisi LIKE ?
                  OR b.status LIKE ?
                  OR b.deskripsi LIKE ?)";
        $search = "%" . $filters['search'] . "%";
        // enam kali untuk enam kolom
        for ($i=0; $i<6; $i++) {
            $params[] = $search;
            $types   .= "s";
        }
    }

    // Filter kategori (integer)
    if (!empty($filters['kategori_id'])) {
        $where[] = "b.kategori_id = ?";
        $params[] = (int)$filters['kategori_id'];
        $types   .= "i";
    }

    // Filter lokasi (integer)
    if (!empty($filters['lokasi_id'])) {
        $where[] = "b.lokasi_id = ?";
        $params[] = (int)$filters['lokasi_id'];
        $types   .= "i";
    }

    // Filter status (string)
    if (!empty($filters['status'])) {
        $where[] = "b.status = ?";
        $params[] = $filters['status'];
        $types   .= "s";
    }

    $sql = "SELECT b.*, k.nama_kategori, l.nama_lokasi
            FROM barang b
            LEFT JOIN kategori k ON k.id = b.kategori_id
            LEFT JOIN lokasi   l ON l.id = b.lokasi_id";

    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY b.id DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }

    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result();
}

/* ---------- SELECT by ID ---------- */
function getBarangById($id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM barang WHERE id=?");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $stmt->bind_param("i",$id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/* ---------- INSERT ---------- */
function insertBarang($data,$foto){
    global $conn;

    $sql = "INSERT INTO barang
        (nama_barang, nomor_inventaris, nomor_serial, kategori_id, lokasi_id,
         kondisi, status, tanggal_pembelian, harga_pembelian, garansi_sampai,
         deskripsi, foto)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }

    $params = [
        $data['nama_barang']        ?? '',
        $data['nomor_inventaris']   ?? '',
        $data['nomor_serial']       ?? '',
        (int)($data['kategori_id']  ?? 0),
        (int)($data['lokasi_id']    ?? 0),
        $data['kondisi']            ?? '',
        $data['status']             ?? '',
        $data['tanggal_pembelian']  ?: null,
        $data['harga_pembelian']    ?? '',
        $data['garansi_sampai']     ?: null,
        $data['deskripsi']          ?? '',
        $foto
    ];

    // tipe data: 3 string + 2 int + 6 string
    $types = "sssii" . str_repeat("s", 6);

    $stmt->bind_param($types, ...$params);
    return $stmt->execute();
}

/* ---------- UPDATE ---------- */
function updateBarang($id,$data,$foto=null){
    global $conn;

    $row = getBarangById($id);
    if($foto && !empty($row['foto'])){
        @unlink(__DIR__."/../uploads/barang/".$row['foto']);
    }

    $fields = [
        'nama_barang=?',
        'nomor_inventaris=?',
        'nomor_serial=?',
        'kategori_id=?',
        'lokasi_id=?',
        'kondisi=?',
        'status=?',
        'tanggal_pembelian=?',
        'harga_pembelian=?',
        'garansi_sampai=?',
        'deskripsi=?'
    ];
    $values = [
        $data['nama_barang']        ?? '',
        $data['nomor_inventaris']   ?? '',
        $data['nomor_serial']       ?? '',
        (int)($data['kategori_id']  ?? 0),
        (int)($data['lokasi_id']    ?? 0),
        $data['kondisi']            ?? '',
        $data['status']             ?? '',
        $data['tanggal_pembelian']  ?: null,
        $data['harga_pembelian']    ?? '',
        $data['garansi_sampai']     ?: null,
        $data['deskripsi']          ?? ''
    ];

    if($foto){
        $fields[] = 'foto=?';
        $values[] = $foto;
    }

    $fields[] = 'updated_at=NOW()';
    $sql = "UPDATE barang SET " . implode(", ", $fields) . " WHERE id=?";
    $values[] = (int)$id;

    // tipe data: 3 string + 2 int + sisanya string
    $types = "sssii" . str_repeat("s", count($values)-5);

    $stmt = $conn->prepare($sql);
    if(!$stmt){
        die("Query error: " . $conn->error);
    }

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
}

/* ---------- DELETE ---------- */
function deleteBarang($id){
    global $conn;
    $row = getBarangById($id);
    if($row && !empty($row['foto'])){
        @unlink(__DIR__."/../uploads/barang/".$row['foto']);
    }
    $stmt = $conn->prepare("DELETE FROM barang WHERE id=?");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $stmt->bind_param("i",$id);
    return $stmt->execute();
}
