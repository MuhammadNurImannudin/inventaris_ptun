<?php
include __DIR__ . '/../template/header.php';
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_kategori'];
    $desk = $_POST['deskripsi'];

    $stmt = $db->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama, $desk);
    $stmt->execute();

    header("Location: index.php?page=kategori");
    exit;
}
?>

<h5 class="fw-bold mb-3">Tambah Kategori</h5>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="index.php?page=kategori" class="btn btn-secondary">Kembali</a>
</form>

<?php include __DIR__ . '/../template/footer.php'; ?>
