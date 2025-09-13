<?php
require_once '../../models/Kategori.php';
require_once '../../models/Lokasi.php';
$kategori = getAllKategori();
$lokasi   = getAllLokasi();
include '../template/header.php';
?>

<div class="container p-4">
  <h5 class="mb-4">Tambah Barang</h5>

  <form action="../../controllers/BarangController.php" method="POST" enctype="multipart/form-data">
    <div class="row g-3">

      <!-- Baris 1 -->
      <div class="col-md-6">
        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
        <input type="text" name="nama_barang" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Nomor Inventaris <span class="text-danger">*</span></label>
        <input type="text" name="nomor_inventaris" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Nomor Serial</label>
        <input type="text" name="nomor_serial" class="form-control">
      </div>

      <!-- Baris 2 -->
      <div class="col-md-3">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <select name="kategori_id" class="form-select" required>
          <option value="">Pilih Kategori</option>
          <?php while($k=mysqli_fetch_assoc($kategori)): ?>
            <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
        <select name="lokasi_id" class="form-select" required>
          <option value="">Pilih Lokasi</option>
          <?php while($l=mysqli_fetch_assoc($lokasi)): ?>
            <option value="<?= $l['id'] ?>"><?= $l['nama_lokasi'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Kondisi</label>
        <select name="kondisi" class="form-select">
          <option>Baik</option>
          <option>Rusak</option>
          <option>Hilang</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option>Tersedia</option>
          <option>Dipinjam</option>
          <option>Maintenance</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Tgl Pembelian</label>
        <input type="date" name="tanggal_pembelian" class="form-control">
      </div>

      <!-- Baris 3 -->
      <div class="col-md-3">
        <label class="form-label">Harga Pembelian (Rp)</label>
        <input type="number" name="harga_pembelian" class="form-control" min="0">
      </div>
      <div class="col-md-3">
        <label class="form-label">Garansi Sampai</label>
        <input type="date" name="garansi_sampai" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Foto</label>
        <div class="input-group">
          <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*" onchange="previewImage(event)">
          <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#previewModal">
            Lihat
          </button>
        </div>
        <small class="text-muted">Klik tombol <b>Lihat</b> untuk preview fullscreen</small>
      </div>

      <!-- Baris 4 -->
      <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
      </div>

      <!-- Tombol -->
      <div class="col-12">
        <button type="submit" name="tambah" class="btn btn-primary">
          <i class="bi bi-save"></i> Simpan
        </button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>
    </div>
  </form>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-dark">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white" id="previewModalLabel">Preview Foto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body d-flex justify-content-center align-items-center">
        <img id="previewImage" src="" class="img-fluid rounded" alt="Preview Foto">
      </div>
    </div>
  </div>
</div>

<script>
  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('previewImage');
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<?php include '../template/footer.php'; ?>
