<?php
/* File: views/lokasi/index.php */
include __DIR__ . '/../template/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid p-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
      <i class="bi bi-geo-alt-fill text-primary me-2"></i>Manajemen Lokasi
      <br><small class="text-muted fw-normal">Pengadilan Tata Usaha Negara Banjarmasin</small>
    </h4>
    <a href="index.php?page=lokasi&aksi=tambah" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Tambah Lokasi
    </a>
  </div>

  <!-- Pencarian -->
  <div class="row mb-3">
    <div class="col-md-4">
      <form method="GET" action="index.php" class="input-group">
        <input type="hidden" name="page" value="lokasi">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" name="cari" class="form-control"
               placeholder="Cari lokasi..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>">
      </form>
    </div>
  </div>

  <!-- Grid Lokasi -->
  <div class="row g-4">
    <?php if (empty($data)): ?>
      <div class="col-12">
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle"></i> Belum ada data lokasi.
        </div>
      </div>
    <?php else: ?>
      <?php foreach ($data as $l): ?>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($l['nama_lokasi']) ?></h6>
              <p class="text-muted small mb-2"><?= htmlspecialchars($l['deskripsi']) ?></p>

              <div class="d-flex align-items-center mb-2">
                <i class="bi bi-building text-primary me-2"></i>
                <span><?= htmlspecialchars($l['gedung']) ?> · Lantai <?= $l['lantai'] ?></span>
              </div>
              <div class="d-flex align-items-center mb-2">
                <i class="bi bi-door-open text-info me-2"></i>
                <span><?= htmlspecialchars($l['ruangan']) ?></span>
              </div>

              <span class="badge bg-success"><?= $l['jumlah_barang'] ?> barang</span>
            </div>

            <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
              <small class="text-muted">
                Dibuat: <?= date('d M Y', strtotime($l['dibuat_pada'])) ?>
              </small>
              <div class="btn-group btn-group-sm">
                <a href="index.php?page=lokasi&aksi=edit&id=<?= $l['id'] ?>"
                   class="btn btn-outline-warning" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="index.php?page=lokasi&aksi=hapus&id=<?= $l['id'] ?>"
                   class="btn btn-outline-danger" title="Hapus"
                   onclick="return confirm('Yakin ingin menghapus lokasi ini?');">
                  <i class="bi bi-trash"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../template/footer.php'; ?>