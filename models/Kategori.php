<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Ambil semua kategori (opsional pakai keyword).
 * Mengembalikan mysqli_result agar bisa di-loop:
 * while($row = mysqli_fetch_assoc($result)) { ... }
 */
function getAllKategori($keyword = '')
{
    global $conn;

    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    // Jika tanpa keyword -> query sederhana (lebih cepat)
    if (trim($keyword) === '') {
        // Pakai kolom yang pasti ada. Umumnya 'id' selalu ada.
        $sql = "SELECT * FROM kategori ORDER BY id DESC";
        $result = $conn->query($sql);
        if (!$result) {
            die("Query error: " . $conn->error);
        }
        return $result; // mysqli_result
    }

    // Dengan pencarian keyword (prepared statement)
    $sql = "SELECT * FROM kategori
            WHERE nama_kategori LIKE ? OR deskripsi LIKE ?
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query prepare gagal: " . $conn->error);
    }

    $like = '%' . $keyword . '%';
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result(); // mysqli_result
    return $result;
}

/** Ambil satu kategori by id */
function getKategoriById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM kategori WHERE id=?");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/** Insert kategori baru */
function insertKategori($data)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $nama = $data['nama_kategori'] ?? '';
    $desk = $data['deskripsi'] ?? '';
    $stmt->bind_param("ss", $nama, $desk);
    return $stmt->execute();
}

/** Update kategori */
function updateKategori($id, $data)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE kategori SET nama_kategori=?, deskripsi=? WHERE id=?");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $nama = $data['nama_kategori'] ?? '';
    $desk = $data['deskripsi'] ?? '';
    $stmt->bind_param("ssi", $nama, $desk, $id);
    return $stmt->execute();
}

/** Delete kategori */
function deleteKategori($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id=?");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
