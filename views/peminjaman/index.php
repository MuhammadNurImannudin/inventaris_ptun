<?php
/**
 * views/peminjaman/index.php - Manajemen Peminjaman Barang
 * Professional borrowing management system for PTUN Banjarmasin
 * 
 * LOKASI FILE: views/peminjaman/index.php (REPLACE FILE KOSONG)
 */

require_once __DIR__ . '/../../models/Peminjaman.php';
require_once __DIR__ . '/../../models/Barang.php';
require_once __DIR__ . '/../../models/User.php';

// Get filters
$filters = [
    'status' => $_GET['status'] ?? '',
    'start_date' => $_GET['start_date'] ?? '',
    'end_date' => $_GET['end_date'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$peminjaman = getAllPeminjaman($filters);
$overduePeminjaman = getOverduePeminjaman();

$pageTitle = 'Manajemen Peminjaman';
require_once __DIR__ . '/../template/header.php';
?>

<style>
    .borrowing-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .borrowing-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .overdue-alert {
        background: linear-gradient(135deg, #ff6b6b, #ee5a24);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .quick-stats {
        background: linear-gradient(135deg, var(--ptun-primary), var(--ptun-secondary));
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }
</style>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="display-6 fw-bold text-warning mb-2">
            <i class="bi bi-arrow-down-square me-3"></i>
            Manajemen Peminjaman Barang
        </h1>
        <p class="lead text-muted mb-0">Pengadilan Tata Usaha Negara Banjarmasin</p>
    </div>
    <a href="tambah.php" class="btn btn-warning btn-lg">
        <i class="bi bi-plus-circle me-2"></i>Tambah Peminjaman
    </a>
</div>

<!-- Overdue Alert -->
<?php if ($overduePeminjaman && mysqli_num_rows($overduePeminjaman) > 0): ?>
<div class="overdue-alert">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Peringatan Keterlambatan!
            </h5>
            <p class="mb-0">
                Terdapat <strong><?= mysqli_num_rows($overduePeminjaman) ?> peminjaman</strong> yang melewati batas waktu pengembalian.
                Segera lakukan tindak lanjut dengan peminjam.
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="#overdue-section" class="btn btn-light btn-lg">
                <i class="bi bi-eye me-2"></i>Lihat Detail
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Quick Statistics -->
<div class="quick-stats">
    <div class="row text-center">
        <div class="col-md-3">
            <div class="bg-white bg-opacity-25 rounded p-3">
                <i class="bi bi-list-ul display-4 mb-2"></i>
                <h3><?= mysqli_num_rows($peminjaman) ?></h3>
                <small>Total Peminjaman</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white bg-opacity-25 rounded p-3">
                <i class="bi bi-clock-fill display-4 mb-2"></i>
                <?php
                mysqli_data_seek($peminjaman, 0);
                $aktif = 0;
                while ($row = mysqli_fetch_assoc($peminjaman)) {
                    if ($row['status'] === 'Aktif') $aktif++;
                }
                ?>
                <h3><?= $aktif ?></h3>
                <small>Sedang Dipinjam</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white bg-opacity-25 rounded p-3">
                <i class="bi bi-check-circle-fill display-4 mb-2"></i>
                <?php
                mysqli_data_seek($peminjaman, 0);
                $kembali = 0;
                while ($row = mysqli_fetch_assoc($peminjaman)) {
                    if ($row['status'] === 'Kembali') $kembali++;
                }
                ?>
                <h3><?= $kembali ?></h3>
                <small>Sudah Dikembalikan</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white bg-opacity-25 rounded p-3">
                <i class="bi bi-exclamation-triangle-fill display-4 mb-2"></i>
                <h3><?= $overduePeminjaman ? mysqli_num_rows($overduePeminjaman) : 0 ?></h3>
                <small>Terlambat</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <h6 class="text-primary mb-3">
        <i class="bi bi-funnel me-2"></i>Filter & Pencarian
    </h6>
    
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" 
                   placeholder="Cari barang atau peminjam..." 
                   value="<?= htmlspecialchars($filters['search']) ?>">
        </div>
        
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Aktif" <?= $filters['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="Kembali" <?= $filters['status'] === 'Kembali' ? 'selected' : '' ?>>Kembali</option>
                <option value="Terlambat" <?= $filters['status'] === 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <input type="date" name="start_date" class="form-control" 
                   value="<?= htmlspecialchars($filters['start_date']) ?>" 
                   title="Tanggal Mulai">
        </div>
        
        <div class="col-md-2">
            <input type="date" name="end_date" class="form-control" 
                   value="<?= htmlspecialchars($filters['end_date']) ?>"
                   title="Tanggal Akhir">
        </div>
        
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary me-2">
                <i class="bi bi-search me-1"></i>Cari
            </button>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Main Data Table -->
<div class="card borrowing-card">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0">
            <i class="bi bi-table me-2"></i>
            Daftar Peminjaman Barang
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Barang</th>
                    <th width="15%">Peminjam</th>
                    <th width="12%">Tgl Pinjam</th>
                    <th width="12%">Target Kembali</th>
                    <th width="12%">Durasi</th>
                    <th width="10%">Status</th>
                    <th width="14%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($peminjaman, 0);
                if (mysqli_num_rows($peminjaman) > 0):
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($peminjaman)): 
                        $tglPinjam = date('d/m/Y', strtotime($row['tanggal_pinjam']));
                        $targetKembali = date('d/m/Y', strtotime($row['target_kembali']));
                        
                        // Calculate duration and check if overdue
                        $today = new DateTime();
                        $target = new DateTime($row['target_kembali']);
                        $pinjam = new DateTime($row['tanggal_pinjam']);
                        
                        if ($row['status'] === 'Aktif') {
                            $isOverdue = $today > $target;
                            $durasi = $pinjam->diff($today)->days . ' hari';
                            $actualStatus = $isOverdue ? 'Terlambat' : 'Aktif';
                        } else {
                            $kembali = new DateTime($row['tanggal_kembali'] ?? $row['tanggal_pinjam']);
                            $durasi = $pinjam->diff($kembali)->days . ' hari';
                            $actualStatus = $row['status'];
                            $isOverdue = false;
                        }
                ?>
                <tr class="<?= $isOverdue ? 'table-danger' : '' ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <div class="fw-semibold text-dark">
                            <?= htmlspecialchars($row['nama_barang']) ?>
                        </div>
                        <small class="text-muted">
                            <?= htmlspecialchars($row['nomor_inventaris'] ?? 'No. Inventaris tidak tersedia') ?>
                        </small>
                    </td>
                    <td>
                        <i class="bi bi-person text-muted me-2"></i>
                        <span class="fw-semibold"><?= htmlspecialchars($row['username']) ?></span>
                    </td>
                    <td>
                        <i class="bi bi-calendar-plus text-success me-1"></i>
                        <?= $tglPinjam ?>
                    </td>
                    <td>
                        <i class="bi bi-calendar-check <?= $isOverdue ? 'text-danger' : 'text-warning' ?> me-1"></i>
                        <?= $targetKembali ?>
                        <?php if ($isOverdue): ?>
                            <br><small class="text-danger">
                                <i class="bi bi-exclamation-triangle me-1"></i>Terlambat
                            </small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark"><?= $durasi ?></span>
                    </td>
                    <td>
                        <?php
                        $statusClass = [
                            'Aktif' => 'bg-warning text-dark',
                            'Kembali' => 'bg-success',
                            'Terlambat' => 'bg-danger'
                        ][$actualStatus] ?? 'bg-secondary';
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= $actualStatus ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($row['status'] === 'Aktif'): ?>
                            <a href="../pengembalian/tambah.php?peminjaman_id=<?= $row['id'] ?>" class="btn btn-outline-success" title="Proses Pengembalian">
                                <i class="bi bi-arrow-up-square"></i>
                            </a>
                            <?php endif; ?>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id'] ?>" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <!-- Modal Konfirmasi Hapus -->
                        <div class="modal fade" id="hapusModal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">Konfirmasi Penghapusan</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Yakin ingin menghapus peminjaman:</p>
                                        <strong><?= htmlspecialchars($row['nama_barang']) ?></strong>
                                        <br>oleh <strong><?= htmlspecialchars($row['username']) ?></strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <a href="../../controllers/PeminjamanController.php?hapus=<?= $row['id'] ?>" class="btn btn-danger">Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2">Tidak ada data peminjaman ditemukan</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Overdue Items Section -->
<?php if ($overduePeminjaman && mysqli_num_rows($overduePeminjaman) > 0): ?>
<div class="mt-4" id="overdue-section">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Peminjaman Terlambat - Tindak Lanjut Diperlukan
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-danger table-striped mb-0">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Peminjam</th>
                        <th>Target Kembali</th>
                        <th>Hari Terlambat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($overdue = mysqli_fetch_assoc($overduePeminjaman)): 
                        $target = new DateTime($overdue['target_kembali']);
                        $today = new DateTime();
                        $daysLate = $target->diff($today)->days;
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($overdue['nama_barang']) ?></td>
                        <td><?= htmlspecialchars($overdue['username']) ?></td>
                        <td><?= date('d/m/Y', strtotime($overdue['target_kembali'])) ?></td>
                        <td>
                            <span class="badge bg-danger">
                                <?= $daysLate ?> hari
                            </span>
                        </td>
                        <td>
                            <a href="../pengembalian/tambah.php?peminjaman_id=<?= $overdue['id'] ?>" class="btn btn-sm btn-success">
                                <i class="bi bi-arrow-up-square me-1"></i>Proses Pengembalian
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../template/footer.php'; ?>