<?php
require_once __DIR__.'/../config/database.php';

/* ---------- 1. INVENTARIS ---------- */
function getLaporanInventaris($start='',$end='',$kategori='',$lokasi=''){
    global $conn;
    $sql = "SELECT b.*, k.nama_kategori, l.nama_lokasi
            FROM barang b
            LEFT JOIN kategori k ON k.id=b.kategori_id
            LEFT JOIN lokasi l   ON l.id=b.lokasi_id
            WHERE 1=1";
    $params = []; $types = '';
    if($start){ $sql.=" AND b.created_at >= ?"; $params[]=$start; $types.='s'; }
    if($end){   $sql.=" AND b.created_at <= ?"; $params[]=$end;   $types.='s'; }
    if($kategori){ $sql.=" AND b.kategori_id = ?"; $params[]=(int)$kategori; $types.='i'; }
    if($lokasi){   $sql.=" AND b.lokasi_id = ?";   $params[]=(int)$lokasi;   $types.='i'; }
    $sql .= " ORDER BY b.id DESC";

    $stmt = $conn->prepare($sql);
    if(!$stmt) { die("Query error (getLaporanInventaris): ".$conn->error); }
    if($params) $stmt->bind_param($types,...$params);
    return $stmt->execute() ? $stmt->get_result() : false;
}

/* ---------- 2. PEMINJAMAN ---------- */
function getLaporanPeminjaman($start='',$end=''){
    global $conn;
    $sql = "SELECT p.*, b.nama_barang, u.username
            FROM peminjaman p
            JOIN barang b ON b.id=p.barang_id
            JOIN users u  ON u.id=p.user_id
            WHERE 1=1";
    $params = []; $types='';
    if($start){ $sql.=" AND p.tanggal_pinjam>=?"; $params[]=$start; $types.='s'; }
    if($end)  { $sql.=" AND p.tanggal_pinjam<=?"; $params[]=$end;   $types.='s'; }
    $sql .= " ORDER BY p.id DESC";

    $stmt = $conn->prepare($sql);
    if(!$stmt) { die("Query error (getLaporanPeminjaman): ".$conn->error); }
    if($params) $stmt->bind_param($types,...$params);
    return $stmt->execute() ? $stmt->get_result() : false;
}

/* ---------- 3. PENGEMBALIAN ---------- */
function getLaporanPengembalian($start='',$end=''){
    global $conn;
    $sql = "SELECT pb.*, p.tanggal_pinjam, b.nama_barang, u.username
            FROM pengembalian pb
            JOIN peminjaman p ON p.id=pb.peminjaman_id
            JOIN barang b     ON b.id=p.barang_id
            JOIN users u      ON u.id=p.user_id
            WHERE 1=1";
    $params = []; $types='';
    if($start){ $sql.=" AND pb.tanggal_kembali>=?"; $params[]=$start; $types.='s'; }
    if($end)  { $sql.=" AND pb.tanggal_kembali<=?"; $params[]=$end;   $types.='s'; }
    $sql .= " ORDER BY pb.id DESC";

    $stmt = $conn->prepare($sql);
    if(!$stmt) { die("Query error (getLaporanPengembalian): ".$conn->error); }
    if($params) $stmt->bind_param($types,...$params);
    return $stmt->execute() ? $stmt->get_result() : false;
}

/* ---------- 4. MAINTENANCE ---------- */
function getLaporanMaintenance($start='',$end=''){
    global $conn;
    
    // ✅ Cek dulu apakah maintenance punya kolom barang_id atau id_barang
    $check = $conn->query("SHOW COLUMNS FROM maintenance LIKE 'barang_id'");
    if ($check && $check->num_rows > 0) {
        // kalau ada kolom barang_id
        $sql = "SELECT m.*, b.nama_barang
                FROM maintenance m
                LEFT JOIN barang b ON b.id = m.barang_id
                WHERE 1=1";
    } else {
        $check2 = $conn->query("SHOW COLUMNS FROM maintenance LIKE 'id_barang'");
        if ($check2 && $check2->num_rows > 0) {
            // kalau namanya id_barang
            $sql = "SELECT m.*, b.nama_barang
                    FROM maintenance m
                    LEFT JOIN barang b ON b.id = m.id_barang
                    WHERE 1=1";
        } else {
            // kalau tidak ada relasi ke barang
            $sql = "SELECT m.*
                    FROM maintenance m
                    WHERE 1=1";
        }
    }

    $params = []; $types = '';
    if($start){ $sql.=" AND m.tanggal_maintenance>=?"; $params[]=$start; $types.='s'; }
    if($end)  { $sql.=" AND m.tanggal_maintenance<=?"; $params[]=$end;   $types.='s'; }
    $sql .= " ORDER BY m.id DESC";

    $stmt = $conn->prepare($sql);
    if(!$stmt){
        die("Query error (getLaporanMaintenance): ".$conn->error);
    }

    if($params) $stmt->bind_param($types,...$params);
    return $stmt->execute() ? $stmt->get_result() : false;
}


/* ---------- 5. KEUANGAN ---------- */
function getLaporanKeuangan($start='',$end=''){
    global $conn;
    $sql = "SELECT b.nama_barang, b.harga_pembelian, b.tanggal_pembelian
            FROM barang b
            WHERE 1=1";
    $params = []; $types='';
    if($start){ $sql.=" AND b.tanggal_pembelian>=?"; $params[]=$start; $types.='s'; }
    if($end)  { $sql.=" AND b.tanggal_pembelian<=?"; $params[]=$end;   $types.='s'; }
    $sql .= " ORDER BY b.id DESC";

    $stmt = $conn->prepare($sql);
    if(!$stmt) { die("Query error (getLaporanKeuangan): ".$conn->error); }
    if($params) $stmt->bind_param($types,...$params);
    return $stmt->execute() ? $stmt->get_result() : false;
}
?>
