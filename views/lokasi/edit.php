<?php
require_once __DIR__ . '/../../models/lokasi.php';
$id = $_GET['id'] ?? null;
$lokasi = getLokasiById($id);
if (!$lokasi) {
    header('Location: index.php?page=lokasi');
    exit;
}
?>

<?php include __DIR__ . '/../template/header.php'; ?>

<h5 class="fw-bold mb-3">Edit Lokasi</h5>

<form action="index.php?page=lokasi&aksi=edit&id=<?= $id ?>" method="POST">
    <div class="mb-3">
        <label class="form-label">Nama Lokasi</label>
        <input type="text" name="nama_lokasi" class="form-control" value="<?= htmlspecialchars($lokasi['nama_lokasi']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Gedung</label>
        <input type="text" name="gedung" class="form-control" value="<?= htmlspecialchars($lokasi['gedung']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Lantai</label>
        <input type="text" name="lantai" class="form-control" value="<?= htmlspecialchars($lokasi['lantai']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Ruangan</label>
        <input type="text" name="ruangan" class="form-control" value="<?= htmlspecialchars($lokasi['ruangan']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($lokasi['deskripsi']) ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Jumlah Barang</label>
        <input type="number" name="jumlah_barang" class="form-control" min="0" value="<?= $lokasi['jumlah_barang'] ?>">
    </div>
    <button type="submit" class="btn btn-warning">Perbarui</button>
    <a href="index.php?page=lokasi" class="btn btn-secondary">Batal</a>
</form>

<?php include __DIR__ . '/../template/footer.php'; ?>