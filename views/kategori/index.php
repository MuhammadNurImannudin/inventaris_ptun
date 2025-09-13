<?php
/*  Manajemen Kategori - 100 % sama seperti gambar */
?>
<?php include __DIR__ . '/../template/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold">Manajemen Kategori</h5>
    <a href="index.php?page=kategori&aksi=tambah" class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Tambah Kategori
    </a>
</div>

<!-- Bar Pencarian -->
<div class="mb-3">
    <div class="input-group" style="max-width: 320px;">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control form-control-sm" placeholder="Cari kategori...">
    </div>
</div>

<!-- Grid Kartu Kategori -->
<div class="row g-3">
    <!-- Komputer & Laptop -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-1">Komputer & Laptop</h6>
                <p class="text-muted small mb-2">Perangkat komputer dan laptop untuk operasional</p>
                <span class="badge bg-info text-dark">25 barang</span>
            </div>
            <div class="card-footer bg-white border-0">
                <small class="text-muted">Dibuat: 15 Jan 2024</small>
            </div>
        </div>
    </div>

    <!-- Printer & Scanner -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-1">Printer & Scanner</h6>
                <p class="text-muted small mb-2">Perangkat printer dan scanner untuk dokumentasi</p>
                <span class="badge bg-info text-dark">12 barang</span>
            </div>
            <div class="card-footer bg-white border-0">
                <small class="text-muted">Dibuat: 15 Jan 2024</small>
            </div>
        </div>
    </div>

    <!-- Furniture -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-1">Furniture</h6>
                <p class="text-muted small mb-2">Mebel dan perabotan kantor</p>
                <span class="badge bg-info text-dark">45 barang</span>
            </div>
            <div class="card-footer bg-white border-0">
                <small class="text-muted">Dibuat: 15 Jan 2024</small>
            </div>
        </div>
    </div>

    <!-- AC & Elektronik -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-1">AC & Elektronik</h6>
                <p class="text-muted small mb-2">Perangkat pendingin dan elektronik lainnya</p>
                <span class="badge bg-info text-dark">18 barang</span>
            </div>
            <div class="card-footer bg-white border-0">
                <small class="text-muted">Dibuat: 15 Jan 2024</small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../template/footer.php'; ?>