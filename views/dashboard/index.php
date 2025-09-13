<?php
/* ---------- PASTIKAN FILE & VARIABEL ADA ---------- */
require_once __DIR__ . '/../../controllers/DashboardController.php';
require_once __DIR__ . '/../template/header.php';

/* Jika belum ada di controller, tambahkan fungsi berikut di DashboardController.php */
/*
   $total      = countBarang();
   $tersedia   = countBarang("WHERE status='Tersedia'");
   $dipinjam   = countBarang("WHERE status='Dipinjam'");
   $maintenance= countBarang("WHERE status='Maintenance'");
   $terlambat  = countTerlambat();
   $aktivitas  = getAktivitas();
   $maintenance_list = getMaintenance();
*/
?>

<div class="container-fluid p-4">
  <h5 class="mb-4">Selamat datang di Sistem Inventaris PTUN Banjarmasin</h5>

  <!-- 5 Kartu Statistik -->
  <div class="row g-3 mb-4">
    <?php
    /* --- aman jika belum ada di controller --- */
    $total      = $total ?? 0;
    $tersedia   = $tersedia ?? 0;
    $dipinjam   = $dipinjam ?? 0;
    $maintenance= $maintenance ?? 0;
    $terlambat  = $terlambat ?? 0;
    ?>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
      <div class="card shadow-sm text-center h-100">
        <div class="card-body">
          <i class="bi bi-archive-fill text-primary fs-1"></i>
          <h4 class="fw-bold mb-0"><?= number_format($total) ?></h4>
          <small class="text-muted">Total</small>
        </div>
      </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
      <div class="card shadow-sm text-center h-100">
        <div class="card-body">
          <i class="bi bi-check-circle-fill text-success fs-1"></i>
          <h4 class="fw-bold mb-0"><?= number_format($tersedia) ?></h4>
          <small class="text-muted">Tersedia</small>
        </div>
      </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
      <div class="card shadow-sm text-center h-100">
        <div class="card-body">
          <i class="bi bi-arrow-repeat text-warning fs-1"></i>
          <h4 class="fw-bold mb-0"><?= number_format($dipinjam) ?></h4>
          <small class="text-muted">Dipinjam</small>
        </div>
      </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
      <div class="card shadow-sm text-center h-100">
        <div class="card-body">
          <i class="bi bi-wrench text-info fs-1"></i>
          <h4 class="fw-bold mb-0"><?= number_format($maintenance) ?></h4>
          <small class="text-muted">Maintenance</small>
        </div>
      </div>
    </div>
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
      <div class="card shadow-sm text-center h-100">
        <div class="card-body">
          <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
          <h4 class="fw-bold mb-0"><?= number_format($terlambat) ?></h4>
          <small class="text-muted">Terlambat</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Aktivitas & Jadwal -->
  <div class="row g-4">
    <!-- Aktivitas Terbaru -->
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
          <strong>Aktivitas Terbaru</strong>
          <i class="bi bi-activity text-primary"></i>
        </div>
        <ul class="list-group list-group-flush">
        <?php
        $aktivitas = $aktivitas ?? false;
        if ($aktivitas && mysqli_num_rows($aktivitas) > 0):
          while ($row = mysqli_fetch_assoc($aktivitas)): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
              <div>
                <b><?= htmlspecialchars($row['nama_barang']) ?></b> dipinjam oleh <?= htmlspecialchars($row['username']) ?>
                <br><small class="text-muted"><?= date('d M Y', strtotime($row['tanggal'])) ?></small>
              </div>
              <small class="text-muted">2 jam lalu</small>
            </li>
          <?php endwhile;
        else: ?>
          <li class="list-group-item text-muted">Belum ada aktivitas.</li>
        <?php endif; ?>
        </ul>
      </div>
    </div>

    <!-- Maintenance Mendatang -->
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
          <strong>Maintenance Mendatang</strong>
          <i class="bi bi-calendar-event text-info"></i>
        </div>
        <ul class="list-group list-group-flush">
        <?php
        $maintenance_list = $maintenance_list ?? false;
        if ($maintenance_list && mysqli_num_rows($maintenance_list) > 0):
          while ($m = mysqli_fetch_assoc($maintenance_list)): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($m['nama_barang']) ?>
              <small class="text-muted"><?= date('d M Y', strtotime($m['tanggal_maintenance'])) ?></small>
            </li>
          <?php endwhile;
        else: ?>
          <li class="list-group-item text-muted">Tidak ada jadwal.</li>
        <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/sidebar.js') ?>"></script>
</body>
</html>