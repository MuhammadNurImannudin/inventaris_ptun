<?php
/**
 * Laporan Maintenance & Perawatan - PTUN Banjarmasin  
 * Professional Maintenance Report with Cost Analysis
 * 
 * LOKASI FILE: views/laporan/laporan_maintenance.php (REPLACE FILE INI)
 */

$data = getLaporanMaintenance($_GET['start'] ?? '', $_GET['end'] ?? '');

if (!$data) {
    echo '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ada data maintenance pada periode yang dipilih.</div>';
    return;
}

$totalMaintenance = mysqli_num_rows($data);
$totalBiaya = 0;
$statusSelesai = 0;
$statusTerjadwal = 0;
$statusPending = 0;
$jenisPreventif = 0;
$jenisKorektif = 0;
$maintenancePerBulan = [];
$biayaPerJenis = [];

// Analyze maintenance data
mysqli_data_seek($data, 0);
while ($row = mysqli_fetch_assoc($data)) {
    $totalBiaya += (float)$row['biaya'];
    
    // Count by status
    switch (strtolower($row['status'])) {
        case 'selesai': $statusSelesai++; break;
        case 'dijadwalkan':
        case 'terjadwal': $statusTerjadwal++; break;
        case 'pending': $statusPending++; break;
    }
    
    // Count by type
    if (stripos($row['jenis'], 'preventif') !== false) {
        $jenisPreventif++;
        $biayaPerJenis['Preventif'] = ($biayaPerJenis['Preventif'] ?? 0) + (float)$row['biaya'];
    } elseif (stripos($row['jenis'], 'korektif') !== false) {
        $jenisKorektif++;
        $biayaPerJenis['Korektif'] = ($biayaPerJenis['Korektif'] ?? 0) + (float)$row['biaya'];
    } else {
        $biayaPerJenis['Lainnya'] = ($biayaPerJenis['Lainnya'] ?? 0) + (float)$row['biaya'];
    }
    
    // Monthly distribution
    $bulan = date('Y-m', strtotime($row['tanggal_maintenance']));
    $maintenancePerBulan[$bulan] = ($maintenancePerBulan[$bulan] ?? 0) + 1;
}

// Sort monthly data
ksort($maintenancePerBulan);

// Reset data pointer
mysqli_data_seek($data, 0);
?>

<style>
    .maintenance-stat-card {
        border-radius: 15px;
        color: white;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .maintenance-stat-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
    }
    
    .cost-analysis-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
    }
    
    .maintenance-type-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-left: 5px solid;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }
    
    .maintenance-type-card:hover {
        transform: translateX(5px);
    }
    
    .priority-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.5rem;
    }
    
    .cost-breakdown {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin: 1rem 0;
    }
</style>

<!-- Report Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h4 class="fw-bold text-primary">🔧 LAPORAN MAINTENANCE & PERAWATAN</h4>
        <p class="text-muted mb-0">Analisis kegiatan pemeliharaan dan biaya perawatan inventaris PTUN Banjarmasin</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-primary fs-6 px-3 py-2">
            Total Aktivitas: <?= number_format($totalMaintenance) ?>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-2 col-md-4">
        <div class="maintenance-stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="bi bi-wrench display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($totalMaintenance) ?></h3>
            <p class="mb-0">Total Maintenance</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="maintenance-stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="bi bi-check-circle display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($statusSelesai) ?></h3>
            <p class="mb-0">Selesai</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="maintenance-stat-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
            <i class="bi bi-calendar-check display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($statusTerjadwal) ?></h3>
            <p class="mb-0">Terjadwal</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="maintenance-stat-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <i class="bi bi-clock display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($statusPending) ?></h3>
            <p class="mb-0">Pending</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="maintenance-stat-card" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);">
            <i class="bi bi-shield-check display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($jenisPreventif) ?></h3>
            <p class="mb-0">Preventif</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="maintenance-stat-card" style="background: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);">
            <i class="bi bi-currency-dollar display-4 mb-3"></i>
            <h3 class="fw-bold">Rp <?= number_format($totalBiaya / 1000000, 1) ?>M</h3>
            <p class="mb-0">Total Biaya</p>
        </div>
    </div>
</div>

<!-- Main Data Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0"><i class="bi bi-table me-2"></i>Detail Kegiatan Maintenance</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="reportTable">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="25%">Nama Barang</th>
                    <th width="12%">Tanggal</th>
                    <th width="15%">Jenis Maintenance</th>
                    <th class="text-end" width="12%">Biaya (Rp)</th>
                    <th class="text-center" width="12%">Status</th>
                    <th width="19%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($data)): 
                    $tanggal = date('d/m/Y', strtotime($row['tanggal_maintenance']));
                    $biaya = (float)$row['biaya'];
                    
                    // Determine priority based on cost and type
                    $priority = 'low';
                    if ($biaya > 1000000) $priority = 'high';
                    elseif ($biaya > 500000) $priority = 'medium';
                    
                    // Status color
                    $statusClass = [
                        'selesai' => 'bg-success',
                        'dijadwalkan' => 'bg-warning text-dark', 
                        'terjadwal' => 'bg-warning text-dark',
                        'pending' => 'bg-danger'
                    ][strtolower($row['status'])] ?? 'bg-secondary';
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="priority-indicator bg-<?= $priority === 'high' ? 'danger' : ($priority === 'medium' ? 'warning' : 'success') ?>"></span>
                            <div>
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_barang']) ?></div>
                                <small class="text-muted">Priority: <?= ucfirst($priority) ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <i class="bi bi-calendar text-primary me-1"></i>
                        <?= $tanggal ?>
                        <br><small class="text-muted"><?= date('l', strtotime($row['tanggal_maintenance'])) ?></small>
                    </td>
                    <td>
                        <?php
                        $jenisColor = stripos($row['jenis'], 'preventif') !== false ? 'success' : 'warning';
                        $jenisIcon = stripos($row['jenis'], 'preventif') !== false ? 'shield-check' : 'exclamation-triangle';
                        ?>
                        <span class="badge bg-<?= $jenisColor ?> text-dark">
                            <i class="bi bi-<?= $jenisIcon ?> me-1"></i>
                            <?= htmlspecialchars($row['jenis']) ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <span class="fw-bold text-<?= $biaya > 1000000 ? 'danger' : ($biaya > 500000 ? 'warning' : 'success') ?>">
                            <?= number_format($biaya, 0, ',', '.') ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge <?= $statusClass ?> px-3">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($row['keterangan'])): ?>
                            <small><?= htmlspecialchars($row['keterangan']) ?></small>
                        <?php else: ?>
                            <small class="text-muted">Tidak ada keterangan</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot class="table-primary">
                <tr class="fw-bold">
                    <th colspan="4" class="text-end">TOTAL BIAYA MAINTENANCE:</th>
                    <th class="text-end">Rp <?= number_format($totalBiaya, 0, ',', '.') ?></th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Analysis Section -->
<div class="row g-4">
    <!-- Cost Analysis -->
    <div class="col-lg-6">
        <div class="cost-analysis-card">
            <h5 class="mb-4">
                <i class="bi bi-pie-chart-fill me-2"></i>
                Analisis Biaya Maintenance
            </h5>
            
            <div class="cost-breakdown">
                <?php foreach ($biayaPerJenis as $jenis => $biaya): ?>
                <div class="d-flex justify-content-between mb-2 text-dark">
                    <span><strong><?= $jenis ?>:</strong></span>
                    <span class="fw-bold">Rp <?= number_format($biaya, 0, ',', '.') ?></span>
                </div>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-<?= $jenis === 'Preventif' ? 'success' : ($jenis === 'Korektif' ? 'warning' : 'info') ?>" 
                         style="width: <?= $totalBiaya > 0 ? ($biaya / $totalBiaya) * 100 : 0 ?>%"></div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="row text-center mt-4">
                <div class="col-4">
                    <div class="bg-white bg-opacity-25 rounded p-3">
                        <i class="bi bi-graph-up display-5"></i>
                        <h5 class="mt-2">Rp <?= number_format($totalBiaya / max($totalMaintenance, 1), 0, ',', '.') ?></h5>
                        <small>Rata-rata per Item</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white bg-opacity-25 rounded p-3">
                        <i class="bi bi-calendar-month display-5"></i>
                        <h5 class="mt-2">Rp <?= number_format($totalBiaya / max(count($maintenancePerBulan), 1), 0, ',', '.') ?></h5>
                        <small>Rata-rata per Bulan</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white bg-opacity-25 rounded p-3">
                        <i class="bi bi-percent display-5"></i>
                        <h5 class="mt-2"><?= $totalMaintenance > 0 ? number_format(($statusSelesai / $totalMaintenance) * 100, 1) : 0 ?>%</h5>
                        <small>Tingkat Penyelesaian</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Trend -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Tren Maintenance Bulanan</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($maintenancePerBulan)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x display-4"></i>
                    <p class="mt-2">Tidak ada data tren maintenance</p>
                </div>
                <?php else: ?>
                <div style="max-height: 350px; overflow-y: auto;">
                    <?php foreach ($maintenancePerBulan as $bulan => $jumlah): ?>
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <i class="bi bi-calendar-month text-info me-2"></i>
                            <span class="fw-semibold"><?= date('F Y', strtotime($bulan . '-01')) ?></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="progress me-3" style="width: 100px; height: 8px;">
                                <div class="progress-bar bg-info" style="width: <?= ($jumlah / max(array_values($maintenancePerBulan))) * 100 ?>%"></div>
                            </div>
                            <span class="badge bg-info"><?= $jumlah ?> item</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Types Analysis -->
<div class="row mt-4">
    <div class="col-12">
        <h5 class="text-primary mb-3">
            <i class="bi bi-tools me-2"></i>
            Analisis Jenis Maintenance
        </h5>
    </div>
    
    <div class="col-md-6">
        <div class="maintenance-type-card" style="border-left-color: #28a745;">
            <div class="row align-items-center">
                <div class="col-8">
                    <h6 class="text-success mb-1">
                        <i class="bi bi-shield-check me-2"></i>Preventif Maintenance
                    </h6>
                    <p class="mb-0 text-muted">Perawatan pencegahan terjadwal</p>
                    <small class="text-success">Biaya: Rp <?= number_format($biayaPerJenis['Preventif'] ?? 0, 0, ',', '.') ?></small>
                </div>
                <div class="col-4 text-end">
                    <h3 class="text-success mb-0"><?= $jenisPreventif ?></h3>
                    <small class="text-muted">aktivitas</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="maintenance-type-card" style="border-left-color: #ffc107;">
            <div class="row align-items-center">
                <div class="col-8">
                    <h6 class="text-warning mb-1">
                        <i class="bi bi-exclamation-triangle me-2"></i>Corrective Maintenance
                    </h6>
                    <p class="mb-0 text-muted">Perbaikan setelah kerusakan</p>
                    <small class="text-warning">Biaya: Rp <?= number_format($biayaPerJenis['Korektif'] ?? 0, 0, ',', '.') ?></small>
                </div>
                <div class="col-4 text-end">
                    <h3 class="text-warning mb-0"><?= $jenisKorektif ?></h3>
                    <small class="text-muted">aktivitas</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommendations -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Rekomendasi Optimalisasi Maintenance</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-success"><i class="bi bi-check-circle me-2"></i>Kinerja Positif:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-success me-2"></i>
                        <strong><?= $statusSelesai ?> maintenance</strong> berhasil diselesaikan
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-success me-2"></i>
                        <strong><?= $jenisPreventif ?> perawatan preventif</strong> telah dilakukan
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-info me-2"></i>
                        Rata-rata biaya: <strong>Rp <?= number_format($totalBiaya / max($totalMaintenance, 1), 0, ',', '.') ?></strong> per item
                    </li>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Area Perbaikan:</h6>
                <ul class="list-unstyled">
                    <?php if ($statusPending > 0): ?>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-danger me-2"></i>
                        <strong><?= $statusPending ?> maintenance pending</strong> perlu segera ditangani
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($jenisKorektif > $jenisPreventif): ?>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-warning me-2"></i>
                        Tingkatkan <strong>maintenance preventif</strong> untuk mengurangi kerusakan
                    </li>
                    <?php endif; ?>
                    
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Monitor biaya maintenance: <strong><?= $totalBiaya > 0 ? 'Rp ' . number_format($totalBiaya, 0, ',', '.') : 'Tidak ada biaya' ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="row mt-4 text-muted">
    <div class="col-md-6">
        <small>
            <i class="bi bi-calendar me-1"></i>
            Periode: <?= date('d/m/Y', strtotime($_GET['start'] ?? date('Y-m-01'))) ?> - <?= date('d/m/Y', strtotime($_GET['end'] ?? date('Y-m-t'))) ?>
        </small>
    </div>
    <div class="col-md-6 text-end">
        <small>
            <i class="bi bi-clock me-1"></i>
            Dibuat: <?= date('d F Y, H:i:s') ?> WITA
        </small>
    </div>
</div>