<?php
require_once '../../models/Maintenance.php';
require_once '../../models/Barang.php';
$id = (int)($_GET['id'] ?? 0);
$m = getMaintenanceById($id);
if(!$m){ header('Location: index.php'); exit; }
$barang = getAllBarang();
include '../template/header.php';
?>
<div class="container p-4">
  <h5>Edit Jadwal Maintenance</h5>
  <form action="../../controllers/MaintenanceController.php" method="POST" class="row g-3">
    <input type="hidden" name="id" value="<?= $m['id'] ?>">
    <div class="col-md-6">
      <label>Barang</label>
      <select name="barang_id" class="form-select" required>
        <?php while($b=mysqli_fetch_assoc($barang)): ?>
          <option value="<?= $b['id'] ?>" <?= $b['id']==$m['barang_id'] ? 'selected' : '' ?>>
            <?= $b['nama_barang'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label>Tanggal</label>
      <input type="date" name="tanggal_maintenance" class="form-control"
             value="<?= $m['tanggal_maintenance'] ?>" required>
    </div>
    <div class="col-md-3">
      <label>Jenis</label>
      <select name="jenis" class="form-select">
        <?php foreach(['Preventif','Korektif','Insidental'] as $j): ?>
          <option value="<?= $j ?>" <?= $j==$m['jenis'] ? 'selected' : '' ?>><?= $j ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label>Biaya (Rp)</label>
      <input type="number" name="biaya" class="form-control" min="0"
             value="<?= $m['biaya'] ?>">
    </div>
    <div class="col-md-4">
      <label>Status</label>
      <select name="status" class="form-select">
        <option value="Terjadwal" <?= $m['status']=='Terjadwal' ? 'selected' : '' ?>>Terjadwal</option>
        <option value="Selesai"   <?= $m['status']=='Selesai' ? 'selected' : '' ?>>Selesai</option>
        <option value="Dibatalkan"<?= $m['status']=='Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
      </select>
    </div>
    <div class="col-md-12">
      <label>Keterangan</label>
      <textarea name="keterangan" class="form-control" rows="3"><?= $m['keterangan'] ?></textarea>
    </div>
    <div class="col-12">
      <button type="submit" name="edit" class="btn btn-primary">
        <i class="bi bi-save"></i> Update
      </button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<?php include '../template/footer.php'; ?>