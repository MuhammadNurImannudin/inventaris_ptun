<?php
/**
 * Professional Dashboard for PTUN Banjarmasin
 * Inventory Management System
 * 
 * LOKASI FILE: views/dashboard/index.php (REPLACE FILE INI)
 */

require_once __DIR__ . '/../../controllers/DashboardController.php';
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../template/header.php';

// Pastikan variabel tersedia dengan nilai default
$total = $total ?? 0;
$tersedia = $tersedia ?? 0;
$dipinjam = $dipinjam ?? 0;
$maintenance = $maintenance ?? 0;
$terlambat = $terlambat ?? 0;
?>

<style>
    .stats-card {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stats-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--ptun-primary), var(--ptun-secondary));
        color: white;
        box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--ptun-dark);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        font-size: 0.95rem;
        color: var(--ptun-gray);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .activity-item {
        padding: 1.25rem;
        border-radius: 12px;
        background: white;
        margin-bottom: 1rem;
        border-left: 4px solid var(--ptun-primary);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
        transform: translateX(8px);
    }

    .welcome-section {
        background: linear-gradient(135deg, var(--ptun-primary) 0%, var(--ptun-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 30px rgba(30, 64, 175, 0.2);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ptun-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .quick-action-btn {
        background: white;
        border: 2px solid var(--ptun-primary);
        color: var(--ptun-primary);
        padding: 1.25rem;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
    }

    .quick-action-btn:hover {
        background: var(--ptun-primary);
        color: white;
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
    }
</style>

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold mb-2">Selamat Datang di Sistem Inventaris</h1>
            <h2 class="h4 mb-3">Pengadilan Tata Usaha Negara Banjarmasin</h2>
            <p class="lead mb-0">Kelola inventaris dengan mudah, efisien, dan terintegrasi untuk mendukung operasional peradilan yang optimal.</p>
        </div>
        <div class="col-lg-4 text-center">
            <i class="bi bi-building" style="font-size: 8rem; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="<?= BASE_URL ?>views/barang/tambah.php" class="quick-action-btn">
        <i class="bi bi-plus-circle" style="font-size: 2rem;"></i>
        <span>Tambah Barang</span>
    </a>
    <a href="<?= BASE_URL ?>views/peminjaman/index.php" class="quick-action-btn">
        <i class="bi bi-arrow-down-square" style="font-size: 2rem;"></i>
        <span>Peminjaman</span>
    </a>
    <a href="<?= BASE_URL ?>views/maintenance/index.php" class="quick-action-btn">
        <i class="bi bi-wrench" style="font-size: 2rem;"></i>
        <span>Maintenance</span>
    </a>
    <a href="<?= BASE_URL ?>views/laporan/index.php" class="quick-action-btn">
        <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
        <span>Laporan</span>
    </a>
</div>

<!-- Statistics Cards -->
<div class="section-title">
    <i class="bi bi-graph-up"></i>
    Statistik Inventaris
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card stats-card text-center h-100">
            <div class="card-body p-4">
                <div class="stats-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                    <i class="bi bi-archive"></i>
                </div>
                <div class="stats-number"><?= number_format($total) ?></div>
                <div class="stats-label">Total Barang</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card stats-card text-center h-100">
            <div class="card-body p-4">
                <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number"><?= number_format($tersedia) ?></div>
                <div class="stats-label">Tersedia</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card stats-card text-center h-100">
            <div class="card-body p-4">
                <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stats-number"><?= number_format($dipinjam) ?></div>
                <div class="stats-label">Dipinjam</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card stats-card text-center h-100">
            <div class="card-body p-4">
                <div class="stats-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="bi bi-wrench"></i>
                </div>
                <div class="stats-number"><?= number_format($maintenance) ?></div>
                <div class="stats-label">Maintenance</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="card stats-card text-center h-100">
            <div class="card-body p-4">
                <div class="stats-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stats-number"><?= number_format($terlambat) ?></div>
                <div class="stats-label">Terlambat</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-4">
    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-0" style="border-radius: 16px 16px 0 0;">
                <div class="section-title mb-0">
                    <i class="bi bi-activity"></i>
                    Aktivitas Terbaru
                </div>
            </div>
            <div class="card-body p-0">
                <?php
                $aktivitas_terbaru = [
                    ['user' => 'Ahmad Riyadi', 'action' => 'menambah barang', 'item' => 'Laptop Dell Inspiron 15', 'time' => '2 jam yang lalu'],
                    ['user' => 'Siti Nurhaliza', 'action' => 'meminjam', 'item' => 'Printer Canon LBP2900', 'time' => '3 jam yang lalu'],
                    ['user' => 'Budi Santoso', 'action' => 'mengembalikan', 'item' => 'Proyektor Epson', 'time' => '5 jam yang lalu'],
                    ['user' => 'Admin System', 'action' => 'menjadwalkan maintenance', 'item' => 'AC Split Daikin', 'time' => '1 hari yang lalu'],
                    ['user' => 'Maria Ulfa', 'action' => 'mengubah status', 'item' => 'Scanner HP', 'time' => '2 hari yang lalu']
                ];
                
                foreach ($aktivitas_terbaru as $activity): ?>
                    <div class="activity-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 fw-semibold text-dark">
                                    <?= htmlspecialchars($activity['user']) ?> <?= $activity['action'] ?>
                                </h6>
                                <p class="mb-1 text-muted"><?= htmlspecialchars($activity['item']) ?></p>
                                <small class="text-muted"><?= $activity['time'] ?></small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="p-3 text-center border-top">
                    <a href="<?= BASE_URL ?>views/laporan/index.php?jenis=aktivitas" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye"></i> Lihat Semua Aktivitas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Schedule & Quick Stats -->
    <div class="col-lg-4">
        <!-- Maintenance Schedule -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white border-0">
                <div class="section-title mb-0">
                    <i class="bi bi-calendar-event"></i>
                    Jadwal Maintenance
                </div>
            </div>
            <div class="card-body">
                <?php
                $maintenance_schedule = [
                    ['item' => 'AC Split Ruang Ketua', 'date' => '2025-01-15', 'type' => 'Preventif'],
                    ['item' => 'Printer HP LaserJet', 'date' => '2025-01-18', 'type' => 'Korektif'],
                    ['item' => 'Generator Set', 'date' => '2025-01-20', 'type' => 'Preventif']
                ];
                
                foreach ($maintenance_schedule as $maintenance): ?>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($maintenance['item']) ?></h6>
                            <small class="text-muted"><?= date('d M Y', strtotime($maintenance['date'])) ?></small>
                        </div>
                        <span class="badge bg-<?= $maintenance['type'] === 'Preventif' ? 'info' : 'warning' ?> text-dark">
                            <?= $maintenance['type'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
                
                <div class="text-center mt-3">
                    <a href="<?= BASE_URL ?>views/maintenance/index.php" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-calendar-plus"></i> Kelola Jadwal
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body text-center p-4">
                <div class="section-title mb-3">
                    <i class="bi bi-info-circle"></i>
                    Informasi Sistem
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-server text-primary d-block mb-2" style="font-size: 1.5rem;"></i>
                            <h6 class="mb-0">Server Status</h6>
                            <small class="text-success">Online</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <i class="bi bi-shield-check text-success d-block mb-2" style="font-size: 1.5rem;"></i>
                            <h6 class="mb-0">Backup</h6>
                            <small class="text-success">Updated</small>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="text-muted">
                    <small>
                        <i class="bi bi-clock me-1"></i>
                        Last updated: <?= date('d M Y, H:i') ?> WITA
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-0">
                <div class="section-title mb-0">
                    <i class="bi bi-bar-chart"></i>
                    Grafik Inventaris Per Bulan
                </div>
            </div>
            <div class="card-body">
                <div id="inventarisChart" style="height: 300px;">
                    <!-- Placeholder for chart -->
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <div class="text-center">
                            <i class="bi bi-bar-chart" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-2">Grafik akan ditampilkan di sini</p>
                            <small>Menggunakan Chart.js atau library grafik lainnya</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle sidebar functionality
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar-wrapper').classList.toggle('show');
});

// Auto-hide mobile sidebar when clicking outside
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar-wrapper');
    const toggle = document.getElementById('sidebarToggle');
    
    if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('show');
    }
});

// Initialize tooltips if using Bootstrap tooltips
if (typeof bootstrap !== 'undefined') {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}
</script>

<?php require_once __DIR__ . '/../template/footer.php'; ?>