<?php
require_once '../../models/Maintenance.php';
require_once '../../models/Barang.php';

$maintenance = getAllMaintenance();
if (!$maintenance) {
    echo "<div class='alert alert-warning'>Belum ada data maintenance.</div>";
    $rows = [];
} else {
    $rows = mysqli_fetch_all($maintenance, MYSQLI_ASSOC);
}
include '../template/header.php';
?>

<div class="container-fluid p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Manajemen Maintenance</h5>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Tambah Jadwal
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>No</th><th>Barang</th><th>Tanggal</th><th>Jenis</th>
          <th>Biaya</th><th>Status</th><th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $no = 1;
      if (!empty($rows)):
        foreach ($rows as $row): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
            <td><?= date('d/m/Y',strtotime($row['tanggal_maintenance'])) ?></td>
            <td><span class="badge bg-info"><?= $row['jenis'] ?></span></td>
            <td>Rp <?= number_format($row['biaya']) ?></td>
            <td>
              <?php
              $badge = $row['status'] === 'Selesai' ? 'success' :
                       ($row['status'] === 'Terjadwal' ? 'warning' : 'danger');
              ?>
              <span class="badge bg-<?= $badge ?>"><?= $row['status'] ?></span>
            </td>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                      data-bs-target="#hapusModal<?= $row['id'] ?>">
                <i class="bi bi-trash"></i>
              </button>
              <div class="modal fade" id="hapusModal<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header"><h6>Konfirmasi</h6></div>
                    <div class="modal-body">Hapus jadwal <?= $row['nama_barang'] ?>?</div>
                    <div class="modal-footer">
                      <a href="../../controllers/MaintenanceController.php?hapus=<?= $row['id'] ?>"
                         class="btn btn-danger btn-sm">Hapus</a>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach;
      else: ?>
        <tr>
          <td colspan="7" class="text-center">Belum ada data maintenance.</td>
        </tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../template/footer.php'; ?>