<?php
require_once '../../models/Barang.php';
require_once '../../models/Kategori.php';
require_once '../../models/Lokasi.php';

$id     = (int)($_GET['id'] ?? 0);
$barang = getBarangById($id);
if (!$barang) {
    header('Location: index.php');
    exit;
}

$kategori = getAllKategori();
$lokasi   = getAllLokasi();
include '../template/header.php';
?>

<div class="container p-4">
  <h5 class="mb-4">Edit Barang</h5>

  <form action="../../controllers/BarangController.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= htmlspecialchars($barang['id']) ?>">
    <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($barang['foto'] ?? '') ?>">

    <div class="row g-3">

      <!-- Baris 1 -->
      <div class="col-md-6">
        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
        <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Nomor Inventaris <span class="text-danger">*</span></label>
        <input type="text" name="nomor_inventaris" class="form-control" value="<?= htmlspecialchars($barang['nomor_inventaris'] ?? '') ?>" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Nomor Serial</label>
        <input type="text" name="nomor_serial" class="form-control" value="<?= htmlspecialchars($barang['nomor_serial'] ?? '') ?>">
      </div>

      <!-- Baris 2 -->
      <div class="col-md-3">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <select name="kategori_id" class="form-select" required>
          <option value="">Pilih Kategori</option>
          <?php while($k = mysqli_fetch_assoc($kategori)): ?>
            <option value="<?= $k['id'] ?>" <?= isset($barang['kategori_id']) && $k['id'] == $barang['kategori_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($k['nama_kategori']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
        <select name="lokasi_id" class="form-select" required>
          <option value="">Pilih Lokasi</option>
          <?php while($l = mysqli_fetch_assoc($lokasi)): ?>
            <option value="<?= $l['id'] ?>" <?= isset($barang['lokasi_id']) && $l['id'] == $barang['lokasi_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($l['nama_lokasi']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Kondisi</label>
        <select name="kondisi" class="form-select">
          <?php foreach(['Baik','Rusak','Hilang'] as $k): ?>
            <option value="<?= $k ?>" <?= ($k === ($barang['kondisi'] ?? 'Baik')) ? 'selected' : '' ?>><?= $k ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <?php foreach(['Tersedia','Dipinjam','Maintenance'] as $s): ?>
            <option value="<?= $s ?>" <?= ($s === ($barang['status'] ?? 'Tersedia')) ? 'selected' : '' ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Tgl Pembelian</label>
        <input type="date" name="tanggal_pembelian" class="form-control"
               value="<?= htmlspecialchars($barang['tanggal_pembelian'] ?? '') ?>">
      </div>

      <!-- Baris 3 -->
      <div class="col-md-3">
        <label class="form-label">Harga Pembelian (Rp)</label>
        <input type="number" name="harga_pembelian" class="form-control" min="0"
               value="<?= htmlspecialchars($barang['harga_pembelian'] ?? 0) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Garansi Sampai</label>
        <input type="date" name="garansi_sampai" class="form-control"
               value="<?= htmlspecialchars($barang['garansi_sampai'] ?? '') ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Foto Baru (opsional)</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
      </div>

      <!-- Baris 4 -->
      <div class="col-md-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($barang['deskripsi'] ?? '') ?></textarea>
      </div>

      <!-- Preview foto lama -->
      <?php if (!empty($barang['foto'])): ?>
        <div class="col-12">
          <label class="form-label">Foto Lama</label><br>
          <div class="d-flex align-items-center">
            <img src="../../uploads/barang/<?= htmlspecialchars($barang['foto']) ?>" width="100" class="rounded border me-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#fotoLamaModal">
              Lihat Fullscreen
            </button>
          </div>
        </div>
      <?php endif; ?>

      <!-- Tombol -->
      <div class="col-12">
        <button type="submit" name="edit" class="btn btn-primary">
          <i class="bi bi-save"></i> Update
        </button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>
    </div>
  </form>
</div>

<!-- Modal Preview Foto Lama -->
<?php if (!empty($barang['foto'])): ?>
<div class="modal fade" id="fotoLamaModal" tabindex="-1" aria-labelledby="fotoLamaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-dark">
      <div class="modal-header border-0">
        <h5 class="modal-title text-white" id="fotoLamaModalLabel">Preview Foto Lama</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body d-flex justify-content-center align-items-center">
        <img src="../../uploads/barang/<?= htmlspecialchars($barang['foto']) ?>" class="img-fluid rounded" alt="Foto Lama">
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php include '../template/footer.php'; ?>
