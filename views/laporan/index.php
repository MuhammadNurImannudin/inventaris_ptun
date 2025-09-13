<?php
require_once '../../models/Laporan.php';
require_once '../../models/Kategori.php';
require_once '../../models/Lokasi.php';

$start    = $_GET['start'] ?? date('Y-m-01');
$end      = $_GET['end']   ?? date('Y-m-t');
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';
$jenis    = $_GET['jenis'] ?? 'inventaris';

$kategoriList = getAllKategori();
$lokasiList   = getAllLokasi();
include '../template/header.php';
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid p-4">
  <!-- Page Heading -->
  <div class="d-flex align-items-center mb-4">
    <i class="bi bi-clipboard-data-fill text-primary fs-3 me-2"></i>
    <h4 class="fw-bold mb-0">📊 Laporan Inventaris PTUN Banjarmasin</h4>
  </div>

  <!-- Card Filter -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
      <strong><i class="bi bi-funnel-fill me-2"></i>Filter Periode</strong>
    </div>
    <div class="card-body">
      <form method="GET" class="row g-3">
        <input type="hidden" name="jenis" value="<?= $jenis ?>">

        <!-- Tanggal -->
        <div class="col-md-3">
          <label class="form-label">Tgl Mulai</label>
          <input type="date" name="start" class="form-control" value="<?= $start ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Tgl Akhir</label>
          <input type="date" name="end" class="form-control" value="<?= $end ?>">
        </div>

        <!-- Dropdown Kategori & Lokasi (hanya untuk inventaris) -->
        <?php if ($jenis === 'inventaris'): ?>
          <div class="col-md-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select">
              <option value="">Semua Kategori</option>
              <?php while ($k = mysqli_fetch_assoc($kategoriList)): ?>
                <option value="<?= $k['id'] ?>" <?= $k['id'] == $kategori ? 'selected' : '' ?>><?= $k['nama_kategori'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Lokasi</label>
            <select name="lokasi" class="form-select">
              <option value="">Semua Lokasi</option>
              <?php while ($l = mysqli_fetch_assoc($lokasiList)): ?>
                <option value="<?= $l['id'] ?>" <?= $l['id'] == $lokasi ? 'selected' : '' ?>><?= $l['nama_lokasi'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        <?php endif; ?>

        <!-- Tombol Tampilkan -->
        <div class="col-12">
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-search"></i> Tampilkan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Pilihan Jenis Laporan (Card Grid) -->
  <div class="row g-3 mb-4">
    <?php
    $buttons = [
      'inventaris'   => ['Inventaris',   'info',    'bi-archive'],
      'peminjaman'   => ['Peminjaman',   'warning', 'bi-arrow-down-square'],
      'pengembalian' => ['Pengembalian', 'success', 'bi-arrow-up-square'],
      'maintenance'  => ['Maintenance',  'primary', 'bi-wrench'],
      'keuangan'     => ['Keuangan',     'danger',  'bi-currency-dollar'],
    ];
    foreach ($buttons as $key => [$label, $color, $icon]):
    ?>
      <div class="col-md">
        <a href="?jenis=<?= $key ?>&start=<?= $start ?>&end=<?= $end ?>"
           class="btn btn-outline-<?= $color ?> w-100 d-flex flex-column align-items-center py-3">
          <i class="bi <?= $icon ?> fs-2 mb-1"></i>
          <span><?= $label ?></span>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Ringkasan Keuangan (opsional) -->
  <?php if ($jenis === 'keuangan'): ?>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="card shadow-sm text-center">
          <div class="card-body">
            <i class="bi bi-cash-coin text-success fs-2"></i>
            <h3 class="mt-2 mb-0 fw-bold">Rp 0</h3>
            <small class="text-muted">Total Aset Keseluruhan</small>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm text-center">
          <div class="card-body">
            <i class="bi bi-download text-primary fs-2"></i>
            <h5 class="mt-2 mb-0 fw-bold">Download PDF</h5>
            <a href="laporan_pdf.php?jenis=<?= $jenis ?>&start=<?= $start ?>&end=<?= $end ?>" class="btn btn-outline-danger btn-sm mt-2">
              <i class="bi bi-file-earmark-pdf"></i> Unduh
            </a>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Konten laporan -->
  <div class="card shadow-sm">
    <div class="card-header bg-white">
      <strong><i class="bi bi-file-earmark-spreadsheet me-2"></i>Hasil Laporan</strong>
    </div>
    <div class="card-body">
      <?php
      $jenis = $_GET['jenis'] ?? 'inventaris';
      include "laporan_{$jenis}.php";
      ?>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../template/footer.php'; ?>