<?php
require_once '../../models/Barang.php';
require_once '../../models/Kategori.php';
require_once '../../models/Lokasi.php';

// Ambil data filter & search dari GET
$filters = [
    'kategori_id' => $_GET['kategori_id'] ?? '',
    'lokasi_id'   => $_GET['lokasi_id'] ?? '',
    'status'      => $_GET['status'] ?? '',
    'search'      => $_GET['search'] ?? ''
];

// Ambil data barang sesuai filter
$barang = getAllBarang($filters);

// Ambil data kategori & lokasi untuk dropdown
$kategori = getAllKategori();
$lokasi   = getAllLokasi();

include '../template/header.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Manajemen Barang</h5>
        <a href="tambah.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </a>
    </div>

    <!-- Filter & Search -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari nama / no inventaris..."
                   value="<?= htmlspecialchars($filters['search']) ?>">
        </div>
        <div class="col-md-2">
            <select name="kategori_id" class="form-select">
                <option value="">-- Semua Kategori --</option>
                <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
                    <option value="<?= $row['id'] ?>" <?= $filters['kategori_id'] == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="lokasi_id" class="form-select">
                <option value="">-- Semua Lokasi --</option>
                <?php while ($row = mysqli_fetch_assoc($lokasi)): ?>
                    <option value="<?= $row['id'] ?>" <?= $filters['lokasi_id'] == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['nama_lokasi']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                <option value="Tersedia"   <?= $filters['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="Dipinjam"   <?= $filters['status'] == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                <option value="Maintenance" <?= $filters['status'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>No Inventaris</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($barang && mysqli_num_rows($barang) > 0): ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($barang)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <?php
                            $foto = $row['foto'] ?? null;
                            $fotoPath = ($foto && file_exists("../../uploads/barang/{$foto}")) 
                                ? "../../uploads/barang/{$foto}" 
                                : "../../assets/img/no-img.jpg";
                            ?>
                            <!-- Thumbnail klik untuk buka modal -->
                            <img src="<?= $fotoPath ?>" 
                                width="50" 
                                class="rounded img-thumbnail" 
                                style="cursor:pointer" 
                                data-bs-toggle="modal" 
                                data-bs-target="#fotoModal<?= $row['id'] ?>">

                            <!-- Modal Fullscreen Foto -->
                            <div class="modal fade" id="fotoModal<?= $row['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-fullscreen">
                                    <div class="modal-content bg-dark">
                                        <div class="modal-header border-0">
                                            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body d-flex justify-content-center align-items-center">
                                            <img src="<?= $fotoPath ?>" class="img-fluid rounded shadow-lg" style="max-height: 100vh; object-fit: contain;">
                                        </div>
                                        <div class="modal-footer border-0 justify-content-center">
                                            <button type="button" class="btn btn-light" onclick="openFullscreen('<?= $fotoPath ?>')">
                                                <i class="bi bi-arrows-fullscreen"></i> Fullscreen
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['nomor_inventaris'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['nama_lokasi'] ?? '-') ?></td>
                        <td>
                            <span class="badge bg-secondary"><?= htmlspecialchars($row['kondisi']) ?></span>
                        </td>
                        <td>
                            <?php
                            $badge = [
                                'Tersedia' => 'success',
                                'Dipinjam' => 'warning',
                                'Maintenance' => 'info'
                            ];
                            $statusRow = $row['status'] ?? 'Tersedia';
                            ?>
                            <span class="badge bg-<?= $badge[$statusRow] ?? 'secondary' ?>">
                                <?= htmlspecialchars($statusRow) ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>

                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="hapusModal<?= $row['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Konfirmasi</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Hapus barang <b><?= htmlspecialchars($row['nama_barang']) ?></b>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <a href="../../controllers/BarangController.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Tidak ada data ditemukan.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function openFullscreen(imgUrl) {
    let fullscreenDiv = document.createElement("div");
    fullscreenDiv.style.position = "fixed";
    fullscreenDiv.style.top = "0";
    fullscreenDiv.style.left = "0";
    fullscreenDiv.style.width = "100%";
    fullscreenDiv.style.height = "100%";
    fullscreenDiv.style.background = "rgba(0,0,0,0.95)";
    fullscreenDiv.style.display = "flex";
    fullscreenDiv.style.justifyContent = "center";
    fullscreenDiv.style.alignItems = "center";
    fullscreenDiv.style.zIndex = "9999";

    let img = document.createElement("img");
    img.src = imgUrl;
    img.style.maxWidth = "100%";
    img.style.maxHeight = "100%";
    img.style.borderRadius = "10px";
    img.style.boxShadow = "0 0 20px rgba(255,255,255,0.8)";

    fullscreenDiv.appendChild(img);

    fullscreenDiv.addEventListener("click", () => {
        fullscreenDiv.remove();
    });

    document.body.appendChild(fullscreenDiv);
}
</script>

<?php include '../template/footer.php'; ?>
