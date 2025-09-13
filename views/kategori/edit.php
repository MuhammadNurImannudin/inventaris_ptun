<?php
include __DIR__ . '/../template/header.php';
require_once __DIR__ . '/../../config/database.php';

$id = $_GET['id'];
$data = $db->query("SELECT * FROM kategori WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_kategori'];
    $desk = $_POST['deskripsi'];

    $stmt = $db->prepare("UPDATE kategori SET nama_kategori=?, deskripsi=? WHERE id=?");
    $stmt->bind_param("ssi", $nama, $desk, $id);
    $stmt->execute();

    header("Location: index.php?page=kategori");
    exit;
}
?>

<h5 class="fw-bold mb-3">Edit Kategori</h5>
<form method="post">
    <div class="mb-3">
        <label class="form-label">Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($data['nama_kategori']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="index.php?page=kategori" class="btn btn-secondary">Kembali</a>
</form>

<?php include __DIR__ . '/../template/footer.php'; ?>
