<?php
require_once '../../models/Maintenance.php';
require_once '../../models/Barang.php';

/* Ambil jadwal yang belum selesai */
$jadwal = getLaporanMaintenance(date('Y-m-d'), '');
include '../template/header.php';
?>

<div class="container p-4">
  <h5 class="mb-4">📅 Jadwal Maintenance Mendatang</h5>

  <!-- Filter cepat -->
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <label>Tanggal Mulai</label>
      <input type="date" name="start" class="form-control" value="<?= $_GET['start'] ?? date('Y-m-d') ?>">
    </div>
    <div class="col-md-4">
      <label>Tanggal Akhir</label>
      <input type="date" name="end" class="form-control" value="<?= $_GET['end'] ?? date('Y-m-d', strtotime('+30 days')) ?>">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-outline-primary w-100">
        <i class="bi bi-search"></i> Filter
      </button>
    </div>
  </form>

  <?php
  $start = $_GET['start'] ?? date('Y-m-d');
  $end   = $_GET['end']   ?? date('Y-m-d', strtotime('+30 days'));
  $jadwal = getLaporanMaintenance($start, $end);
  ?>

  <!-- Tabel Jadwal -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Barang</th>
          <th>Tanggal</th>
          <th>Jenis</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php $no = 1; while ($row = mysqli_fetch_assoc($jadwal)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['nama_barang']) ?></td>
          <td><?= date('d-m-Y', strtotime($row['tanggal_maintenance'])) ?></td>
          <td><span class="badge bg-info"><?= $row['jenis'] ?></span></td>
          <td>
            <?php $badge = $row['status'] === 'Selesai' ? 'success' : ($row['status'] === 'Terjadwal' ? 'warning' : 'danger'); ?>
            <span class="badge bg-<?= $badge ?>"><?= $row['status'] ?></span>
          </td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
              <i class="bi bi-pencil"></i>
            </a>
            <a href="index.php?status=<?= $row['status'] ?>" class="btn btn-sm btn-info">
              <i class="bi bi-calendar"></i>
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Ringkasan -->
  <div class="alert alert-info mt-3">
    <strong>Total Jadwal:</strong> <?= mysqli_num_rows($jadwal) ?> maintenance
  </div>
</div>

<?php include '../template/footer.php'; ?>