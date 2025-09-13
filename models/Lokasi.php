<?php
/**
 * Models/Lokasi.php
 * Manajemen Lokasi - PTUN Banjarmasin
 */
require_once __DIR__ . '/../config/database.php';

/* =======================================================
   SELECT semua lokasi (hasil: mysqli_result)
   ======================================================= */
function getAllLokasi($keyword = '')
{
    global $conn;
    if (!$conn) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }

    if (trim($keyword) === '') {
        $sql  = "SELECT * FROM lokasi ORDER BY dibuat_pada DESC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Query error (getAllLokasi): " . $conn->error);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    $sql  = "SELECT * FROM lokasi
             WHERE nama_lokasi LIKE ? OR gedung LIKE ? OR ruangan LIKE ?
             ORDER BY dibuat_pada DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare gagal (getAllLokasi): " . $conn->error);
    }
    $like = "%$keyword%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    return $stmt->get_result();
}

/* =======================================================
   Ambil satu lokasi by ID
   ======================================================= */
function getLokasiById($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM lokasi WHERE id = ?");
    if (!$stmt) {
        die("Prepare gagal (getLokasiById): " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/* =======================================================
   Insert lokasi baru
   ======================================================= */
function insertLokasi($data)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO lokasi (nama_lokasi, gedung, lantai, ruangan, deskripsi, jumlah_barang, dibuat_pada)
         VALUES (?, ?, ?, ?, ?, ?, NOW())"
    );
    if (!$stmt) {
        die("Prepare gagal (insertLokasi): " . $conn->error);
    }

    $nama    = $data['nama_lokasi']   ?? '';
    $gedung  = $data['gedung']        ?? '';
    $lantai  = isset($data['lantai'])   && $data['lantai']   !== '' ? (int)$data['lantai']   : 0;
    $ruangan = $data['ruangan']       ?? '';
    $desk    = $data['deskripsi']     ?? '';
    $jml     = isset($data['jumlah_barang']) && $data['jumlah_barang'] !== '' ?
               (int)$data['jumlah_barang'] : 0;

    $stmt->bind_param("ssissi", $nama, $gedung, $lantai, $ruangan, $desk, $jml);
    if ($stmt->execute()) {
        return true;
    } else {
        die("Insert gagal (insertLokasi): " . $stmt->error);
    }
}

/* =======================================================
   Update lokasi
   ======================================================= */
function updateLokasi($id, $data)
{
    global $conn;
    $stmt = $conn->prepare(
        "UPDATE lokasi
         SET nama_lokasi=?, gedung=?, lantai=?, ruangan=?, deskripsi=?, jumlah_barang=?
         WHERE id=?"
    );
    if (!$stmt) {
        die("Prepare gagal (updateLokasi): " . $conn->error);
    }

    $nama    = $data['nama_lokasi']   ?? '';
    $gedung  = $data['gedung']        ?? '';
    $lantai  = isset($data['lantai'])   && $data['lantai']   !== '' ? (int)$data['lantai']   : 0;
    $ruangan = $data['ruangan']       ?? '';
    $desk    = $data['deskripsi']     ?? '';
    $jml     = isset($data['jumlah_barang']) && $data['jumlah_barang'] !== '' ?
               (int)$data['jumlah_barang'] : 0;

    $stmt->bind_param("ssissii", $nama, $gedung, $lantai, $ruangan, $desk, $jml, $id);
    if ($stmt->execute()) {
        return true;
    } else {
        die("Update gagal (updateLokasi): " . $stmt->error);
    }
}

/* =======================================================
   Delete lokasi
   ======================================================= */
function deleteLokasi($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM lokasi WHERE id=?");
    if (!$stmt) {
        die("Prepare gagal (deleteLokasi): " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return true;
    } else {
        die("Delete gagal (deleteLokasi): " . $stmt->error);
    }
}
?>