<?php
/* File: views/lokasi/tambah.php */
include __DIR__ . '/../template/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container-fluid p-4">
  <div class="card shadow-sm" style="max-width: 600px;">
    <div class="card-header bg-light">
      <strong><i class="bi bi-plus-circle me-2"></i>Tambah Lokasi Baru</strong>
      <br><small class="text-muted">PTUN Banjarmasin</small>
    </div>
    <div class="card-body">
      <form method="post" action="index.php?page=lokasi&aksi=tambah">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
            <input type="text" name="nama_lokasi" class="form-control" required
                   placeholder="Contoh: Ruang Sidang 1">
          </div>

          <div class="col-md-6">
            <label class="form-label">Gedung</label>
            <input type="text" name="gedung" class="form-control"
                   placeholder="Contoh: Gedung Utama">
          </div>

          <div class="col-md-6">
            <label class="form-label">Lantai</label>
            <input type="number" name="lantai" class="form-control" min="0"
                   placeholder="0">
          </div>

          <div class="col-md-6">
            <label class="form-label">Ruangan</label>
            <input type="text" name="ruangan" class="form-control"
                   placeholder="Contoh: Ruang 101">
          </div>

          <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"
                      placeholder="Penjelasan singkat tentang lokasi ini"></textarea>
          </div>

          <div class="col-md-6">
            <label class="form-label">Jumlah Barang Saat Ini</label>
            <input type="number" name="jumlah_barang" class="form-control" min="0"
                   value="0">
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <a href="index.php?page=lokasi" class="btn btn-secondary me-2">
            <i class="bi bi-x-circle"></i> Batal
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../template/footer.php'; ?>