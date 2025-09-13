<?php
require_once '../../models/Barang.php';
$barang = getAllBarang();
include '../template/header.php';
?>
<div class="container p-4">
  <h5>Tambah Jadwal Maintenance</h5>
  <form action="../../controllers/MaintenanceController.php" method="POST" class="row g-3">
    <div class="col-md-6">
      <label>Barang</label>
      <select name="barang_id" class="form-select" required>
        <option value="">Pilih</option>
        <?php while($b=mysqli_fetch_assoc($barang)): ?>
          <option value="<?= $b['id'] ?>"><?= $b['nama_barang'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label>Tanggal</label>
      <input type="date" name="tanggal_maintenance" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label>Jenis</label>
      <select name="jenis" class="form-select">
        <option value="Preventif">Preventif</option>
        <option value="Korektif">Korektif</option>
        <option value="Insidental">Insidental</option>
      </select>
    </div>
    <div class="col-md-4">
      <label>Biaya (Rp)</label>
      <input type="number" name="biaya" class="form-control" min="0" value="0">
    </div>
    <div class="col-md-4">
      <label>Status</label>
      <select name="status" class="form-select">
        <option value="Terjadwal">Terjadwal</option>
        <option value="Selesai">Selesai</option>
        <option value="Dibatalkan">Dibatalkan</option>
      </select>
    </div>
    <div class="col-md-12">
      <label>Keterangan</label>
      <textarea name="keterangan" class="form-control" rows="3"></textarea>
    </div>
    <div class="col-12">
      <button type="submit" name="tambah" class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan
      </button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<?php include '../template/footer.php'; ?>