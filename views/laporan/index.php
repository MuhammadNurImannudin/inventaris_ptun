<?php
/**
 * Complete Report System - 5 Professional Reports for PTUN Banjarmasin
 * 
 * LOKASI FILE: views/laporan/index.php (REPLACE FILE INI)
 */

require_once __DIR__ . '/../../models/Laporan.php';
require_once __DIR__ . '/../../models/Kategori.php';
require_once __DIR__ . '/../../models/Lokasi.php';

$start = $_GET['start'] ?? date('Y-m-01');
$end = $_GET['end'] ?? date('Y-m-t');
$kategori = $_GET['kategori'] ?? '';
$lokasi = $_GET['lokasi'] ?? '';
$jenis = $_GET['jenis'] ?? 'inventaris';

$kategoriList = getAllKategori();
$lokasiList = getAllLokasi();

$pageTitle = 'Laporan Inventaris';
require_once __DIR__ . '/../template/header.php';
?>

<style>
    .report-card {
        border-radius: 16px;
        border: none;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .report-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .report-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        color: white;
    }
    
    .filter-card {
        background: linear-gradient(135deg, var(--ptun-primary), var(--ptun-secondary));
        color: white;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(30, 64, 175, 0.2);
    }
    
    .report-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin-top: 2rem;
    }
</style>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="display-6 fw-bold text-primary mb-2">
            <i class="bi bi-file-earmark-bar-graph me-3"></i>
            Sistem Pelaporan Inventaris
        </h1>
        <p class="lead text-muted mb-0">Pengadilan Tata Usaha Negara Banjarmasin</p>
    </div>
    <div class="text-end">
        <button class="btn btn-outline-primary" onclick="printReport()">
            <i class="bi bi-printer"></i> Print
        </button>
        <button class="btn btn-outline-success" onclick="exportToCSV('reportTable', 'laporan-ptun')">
            <i class="bi bi-file-excel"></i> Export
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-funnel-fill me-2"></i>
            Filter Laporan
        </h5>
        
        <form method="GET" class="row g-3">
            <input type="hidden" name="jenis" value="<?= htmlspecialchars($jenis) ?>">
            
            <div class="col-md-3">
                <label class="form-label text-white-50">Tanggal Mulai</label>
                <input type="date" name="start" class="form-control" value="<?= htmlspecialchars($start) ?>">
            </div>
            
            <div class="col-md-3">
                <label class="form-label text-white-50">Tanggal Akhir</label>
                <input type="date" name="end" class="form-control" value="<?= htmlspecialchars($end) ?>">
            </div>
            
            <?php if ($jenis === 'inventaris'): ?>
            <div class="col-md-2">
                <label class="form-label text-white-50">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    <?php mysqli_data_seek($kategoriList, 0); while ($k = mysqli_fetch_assoc($kategoriList)): ?>
                        <option value="<?= $k['id'] ?>" <?= $k['id'] == $kategori ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label text-white-50">Lokasi</label>
                <select name="lokasi" class="form-select">
                    <option value="">Semua</option>
                    <?php mysqli_data_seek($lokasiList, 0); while ($l = mysqli_fetch_assoc($lokasiList)): ?>
                        <option value="<?= $l['id'] ?>" <?= $l['id'] == $lokasi ? 'selected' : '' ?>>
                            <?= htmlspecialchars($l['nama_lokasi']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="col-md-2">
                <label class="form-label text-white-50">&nbsp;</label>
                <button type="submit" class="btn btn-warning w-100 fw-bold">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Report Type Selection -->
<div class="row g-4 mb-4">
    <div class="col-md">
        <a href="?jenis=inventaris&start=<?= $start ?>&end=<?= $end ?>" 
           class="card report-card text-decoration-none <?= $jenis === 'inventaris' ? 'border-primary bg-primary text-white' : '' ?>">
            <div class="card-body text-center p-4">
                <div class="report-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <i class="bi bi-archive"></i>
                </div>
                <h5 class="card-title fw-bold">Laporan Inventaris</h5>
                <p class="card-text">Data lengkap semua barang inventaris</p>
            </div>
        </a>
    </div>
    
    <div class="col-md">
        <a href="?jenis=peminjaman&start=<?= $start ?>&end=<?= $end ?>" 
           class="card report-card text-decoration-none <?= $jenis === 'peminjaman' ? 'border-warning bg-warning text-white' : '' ?>">
            <div class="card-body text-center p-4">
                <div class="report-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="bi bi-arrow-down-square"></i>
                </div>
                <h5 class="card-title fw-bold">Laporan Peminjaman</h5>
                <p class="card-text">Riwayat peminjaman barang</p>
            </div>
        </a>
    </div>
    
    <div class="col-md">
        <a href="?jenis=pengembalian&start=<?= $start ?>&end=<?= $end ?>" 
           class="card report-card text-decoration-none <?= $jenis === 'pengembalian' ? 'border-success bg-success text-white' : '' ?>">
            <div class="card-body text-center p-4">
                <div class="report-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="bi bi-arrow-up-square"></i>
                </div>
                <h5 class="card-title fw-bold">Laporan Pengembalian</h5>
                <p class="card-text">Data pengembalian barang</p>
            </div>
        </a>
    </div>
    
    <div class="col-md">
        <a href="?jenis=maintenance&start=<?= $start ?>&end=<?= $end ?>" 
           class="card report-card text-decoration-none <?= $jenis === 'maintenance' ? 'border-info bg-info text-white' : '' ?>">
            <div class="card-body text-center p-4">
                <div class="report-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="bi bi-wrench"></i>
                </div>
                <h5 class="card-title fw-bold">Laporan Maintenance</h5>
                <p class="card-text">Jadwal & riwayat maintenance</p>
            </div>
        </a>
    </div>
    
    <div class="col-md">
        <a href="?jenis=keuangan&start=<?= $start ?>&end=<?= $end ?>" 
           class="card report-card text-decoration-none <?= $jenis === 'keuangan' ? 'border-danger bg-danger text-white' : '' ?>">
            <div class="card-body text-center p-4">
                <div class="report-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <h5 class="card-title fw-bold">Laporan Keuangan</h5>
                <p class="card-text">Nilai aset & pengeluaran</p>
            </div>
        </a>
    </div>
</div>

<!-- Report Content Section -->
<div class="report-section printable-area">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="fw-bold text-primary mb-0">
            <?php
            $reportTitles = [
                'inventaris' => '<i class="bi bi-archive me-2"></i>Laporan Inventaris Barang',
                'peminjaman' => '<i class="bi bi-arrow-down-square me-2"></i>Laporan Peminjaman Barang',
                'pengembalian' => '<i class="bi bi-arrow-up-square me-2"></i>Laporan Pengembalian Barang',
                'maintenance' => '<i class="bi bi-wrench me-2"></i>Laporan Maintenance & Perawatan',
                'keuangan' => '<i class="bi bi-currency-dollar me-2"></i>Laporan Keuangan & Aset'
            ];
            echo $reportTitles[$jenis] ?? $reportTitles['inventaris'];
            ?>
        </h3>
        
        <div class="badge bg-secondary fs-6 px-3 py-2">
            Periode: <?= date('d/m/Y', strtotime($start)) ?> - <?= date('d/m/Y', strtotime($end)) ?>
        </div>
    </div>

    <?php
    // Include the appropriate report file
    $reportFile = __DIR__ . "/laporan_{$jenis}.php";
    if (file_exists($reportFile)) {
        include $reportFile;
    } else {
        echo '<div class="alert alert-warning">Laporan tidak ditemukan.</div>';
    }
    ?>
</div>

<script>
// Initialize report functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh data setiap 5 menit untuk laporan real-time
    setTimeout(function() {
        location.reload();
    }, 300000); // 5 minutes
    
    // Highlight current report type
    const currentJenis = '<?= $jenis ?>';
    const reportCards = document.querySelectorAll('.report-card');
    reportCards.forEach(card => {
        const href = card.getAttribute('href');
        if (href && href.includes('jenis=' + currentJenis)) {
            card.classList.add('active-report');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../template/footer.php'; ?>