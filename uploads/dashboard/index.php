<?php
/* -------------------------------------------------
   Dashboard Inventaris PTUN Banjarmasin
   ------------------------------------------------- */
include '../../controllers/DashboardController.php';
include '../template/header.php';
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="d-flex" id="wrapper">

  <!-- SIDEBAR -->
  <div class="bg-gradient-primary border-end" id="sidebar-wrapper" style="width:260px; height:100vh;">
    <!-- Logo & Brand -->
    <div class="sidebar-heading d-flex flex-column align-items-center py-4 text-white">
      <img src="<?php echo BASE_URL; ?>assets/images/logo-ptun.png"
           alt="Logo PTUN" width="70" class="rounded-circle border border-light mb-2">
      <div class="text-center">
        <h6 class="mb-0 fw-bold">PTUN Banjarmasin</h6>
        <small>Sistem Inventaris</small>
      </div>
    </div>

    <!-- User Info -->
    <div class="text-center mb-3 text-white">
      <div class="fw-semibold">Administrator PTUN</div>
      <small class="text-light">Admin</small>
    </div>

    <!-- Navigasi -->
    <div class="list-group list-group-flush small">
      <a href="home" class="list-group-item list-group-item-action bg-transparent text-white active">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
      </a>

      <span class="d-block px-3 py-1 fw-bold text-light">Master Data</span>
      <a href="barang" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-box-seam me-2"></i>Barang
      </a>
      <a href="kategori" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-tags me-2"></i>Kategori
      </a>
      <a href="lokasi" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-geo-alt me-2"></i>Lokasi
      </a>
      <a href="user" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-people me-2"></i>Users
      </a>

      <span class="d-block px-3 py-1 fw-bold text-light">Transaksi</span>
      <a href="peminjaman" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-arrow-down-square me-2"></i>Peminjaman
      </a>
      <a href="pengembalian" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-arrow-up-square me-2"></i>Pengembalian
      </a>
      <a href="maintenance" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-wrench me-2"></i>Maintenance
      </a>

      <span class="d-block px-3 py-1 fw-bold text-light">Laporan</span>
      <a href="laporan/barang" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-file-earmark-text me-2"></i>Laporan Barang
      </a>
      <a href="laporan/peminjaman" class="list-group-item list-group-item-action bg-transparent text-white">
        <i class="bi bi-clock-history me-2"></i>Laporan Peminjaman
      </a>

      <!-- Menu bawah -->
      <div class="mt-auto px-3 pt-3 pb-2">
        <a href="home/profile" class="btn btn-outline-light btn-sm w-100 mb-2">
          <i class="bi bi-person-circle"></i> Profil
        </a>
        <a href="../../controllers/AuthController.php?logout=1" class="btn btn-outline-danger btn-sm w-100">
          <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
      </div>
    </div>
  </div>

  <!-- CONTENT -->
  <div id="page-content-wrapper" class="w-100">
    <!-- Top Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom px-3">
      <button class="btn btn-sm btn-outline-secondary me-3" id="menu-toggle">
        <i class="bi bi-list"></i>
      </button>
      <span class="navbar-brand fw-bold mb-0">Inventaris PTUN Banjarmasin</span>

      <div class="ms-auto d-flex align-items-center">
        <span class="me-3 fw-semibold">Administrator PTUN</span>
        <a href="../../controllers/AuthController.php?logout=1" class="btn btn-sm btn-outline-danger">
          <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
      </div>
    </nav>

    <!-- Main -->
    <div class="container-fluid p-4">
      <h5 class="mb-4">Selamat datang di Sistem Inventaris PTUN Banjarmasin</h5>

      <!-- 5 Statistik Card -->
      <div class="row g-4 mb-4">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-archive-fill text-primary" style="font-size:2.5rem;"></i>
              <h2 class="mt-2 mb-0 fw-bold"><?= $total ?? 100 ?></h2>
              <small class="text-muted">Total</small>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
              <h2 class="mt-2 mb-0 fw-bold"><?= $tersedia ?? 85 ?></h2>
              <small class="text-muted">Tersedia</small>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-arrow-repeat text-warning" style="font-size:2.5rem;"></i>
              <h2 class="mt-2 mb-0 fw-bold"><?= $dipinjam ?? 5 ?></h2>
              <small class="text-muted">Dipinjam</small>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-wrench text-info" style="font-size:2.5rem;"></i>
              <h2 class="mt-2 mb-0 fw-bold"><?= $maintenance ?? 8 ?></h2>
              <small class="text-muted">Maintenance</small>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
          <div class="card shadow-sm text-center">
            <div class="card-body">
              <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2.5rem;"></i>
              <h2 class="mt-2 mb-0 fw-bold"><?= $terlambat ?? 2 ?></h2>
              <small class="text-muted">Terlambat</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Aktivitas & Maintenance -->
      <div class="row g-4 mb-4">
        <!-- Aktivitas Terbaru -->
        <div class="col-lg-7">
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <strong><i class="bi bi-activity text-primary me-2"></i>Aktivitas Terbaru</strong>
            </div>
            <ul class="list-group list-group-flush">
              <?php
                // Dummy – ganti dengan query Anda
                $aktivitas = [
                  ['barang'=>'Laptop Dell Inspiron 15','user'=>'Siti Nurhaliza','waktu'=>'2 jam lalu'],
                  ['barang'=>'Printer Canon LBP2900','user'=>'Ahmad Riyadi','waktu'=>'5 jam lalu'],
                  ['barang'=>'AC Split Daikin','user'=>'Admin System','waktu'=>'1 hari lalu']
                ];
                foreach($aktivitas as $a):
              ?>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                  <div>
                    <span class="fw-semibold"><?= $a['barang'] ?></span> dipinjam oleh <?= $a['user'] ?>
                  </div>
                  <small class="text-muted"><?= $a['waktu'] ?></small>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="card-footer bg-white text-center">
              <a href="#" class="btn btn-sm btn-outline-primary">Lihat semua aktivitas</a>
            </div>
          </div>
        </div>

        <!-- Maintenance Mendatang -->
        <div class="col-lg-5">
          <div class="card shadow-sm">
            <div class="card-header bg-white">
              <strong><i class="bi bi-calendar-event text-info me-2"></i>Maintenance Mendatang</strong>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">
                AC Split Daikin
                <small class="text-muted">20 Des 2024</small>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                Printer HP LaserJet
                <small class="text-muted">25 Des 2024</small>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Peminjaman Aktif -->
      <div class="card shadow-sm">
        <div class="card-header bg-white">
          <strong><i class="bi bi-list-check text-secondary me-2"></i>Peminjaman Aktif</strong>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Kode</th>
                  <th>Nama Barang</th>
                  <th>Peminjam</th>
                  <th>Tgl Pinjam</th>
                  <th>Tgl Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $aktif = [
                    ['kode'=>'BRG-001','barang'=>'Printer Canon LBP2900','peminjam'=>'Siti Nurhaliza','pinjam'=>'02/09/2025','kembali'=>'05/09/2025','status'=>'Aktif']
                  ];
                  $no=1;
                  foreach($aktif as $p):
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $p['kode'] ?></td>
                  <td><?= $p['barang'] ?></td>
                  <td><?= $p['peminjam'] ?></td>
                  <td><?= $p['pinjam'] ?></td>
                  <td><?= $p['kembali'] ?></td>
                  <td><span class="badge bg-primary"><?= $p['status'] ?></span></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle sidebar
  document.getElementById("menu-toggle").addEventListener("click", function () {
    document.getElementById("sidebar-wrapper").classList.toggle("toggled");
  });
</script>
</body>
</html>