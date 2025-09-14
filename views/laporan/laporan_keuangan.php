<?php
/**
 * Laporan Keuangan & Aset - PTUN Banjarmasin
 * Comprehensive Financial Report with Asset Valuation
 * 
 * LOKASI FILE: views/laporan/laporan_keuangan.php (REPLACE FILE INI)
 */

$data = getLaporanKeuangan($_GET['start'] ?? '', $_GET['end'] ?? '');

if (!$data) {
    echo '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ada data keuangan pada periode yang dipilih.</div>';
    return;
}

$totalAset = 0;
$totalItem = mysqli_num_rows($data);
$asetPerKategori = [];
$asetPerLokasi = [];
$asetPerTahun = [];
$nilaiTertinggi = 0;
$barangTertinggi = '';

// Get maintenance costs for the same period
$maintenanceData = getLaporanMaintenance($_GET['start'] ?? '', $_GET['end'] ?? '');
$totalBiayaMaintenance = 0;

if ($maintenanceData) {
    mysqli_data_seek($maintenanceData, 0);
    while ($maintenanceRow = mysqli_fetch_assoc($maintenanceData)) {
        $totalBiayaMaintenance += (float)$maintenanceRow['biaya'];
    }
}

// Analyze financial data
mysqli_data_seek($data, 0);
while ($row = mysqli_fetch_assoc($data)) {
    $harga = (float)($row['harga_pembelian'] ?? 0);
    $totalAset += $harga;
    
    // Find highest value item
    if ($harga > $nilaiTertinggi) {
        $nilaiTertinggi = $harga;
        $barangTertinggi = $row['nama_barang'];
    }
    
    // Group by category if available
    if (!empty($row['nama_kategori'])) {
        $kategori = $row['nama_kategori'];
        $asetPerKategori[$kategori] = ($asetPerKategori[$kategori] ?? 0) + $harga;
    }
    
    // Group by location if available  
    if (!empty($row['nama_lokasi'])) {
        $lokasi = $row['nama_lokasi'];
        $asetPerLokasi[$lokasi] = ($asetPerLokasi[$lokasi] ?? 0) + $harga;
    }
    
    // Group by purchase year
    if (!empty($row['tanggal_pembelian'])) {
        $tahun = date('Y', strtotime($row['tanggal_pembelian']));
        $asetPerTahun[$tahun] = ($asetPerTahun[$tahun] ?? 0) + $harga;
    }
}

// Sort arrays by value
arsort($asetPerKategori);
arsort($asetPerLokasi);
ksort($asetPerTahun);

// Calculate averages and ratios
$rataAsetPerItem = $totalItem > 0 ? $totalAset / $totalItem : 0;
$rasioMaintenanceAset = $totalAset > 0 ? ($totalBiayaMaintenance / $totalAset) * 100 : 0;

// Reset data pointer
mysqli_data_seek($data, 0);
?>

<style>
    .financial-stat-card {
        border-radius: 15px;
        color: white;
        padding: 2.5rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .financial-stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
    }
    
    .asset-breakdown-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .category-bar {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .category-bar:hover {
        transform: translateX(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    
    .financial-metric {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 5px solid var(--ptun-primary);
    }
    
    .asset-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--ptun-primary);
    }
    
    .trend-indicator {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>

<!-- Report Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h4 class="fw-bold text-danger">💰 LAPORAN KEUANGAN & ASET</h4>
        <p class="text-muted mb-0">Analisis nilai aset, investasi, dan biaya operasional inventaris PTUN Banjarmasin</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-danger fs-6 px-3 py-2">
            Total Nilai Aset: Rp <?= number_format($totalAset / 1000000, 1) ?>M
        </div>
    </div>
</div>

<!-- Key Financial Metrics -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="financial-stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="bi bi-currency-dollar display-3 mb-3"></i>
            <h2 class="fw-bold">Rp <?= number_format($totalAset / 1000000, 1) ?>M</h2>
            <p class="mb-0">Total Nilai Aset</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="financial-stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="bi bi-archive display-3 mb-3"></i>
            <h2 class="fw-bold"><?= number_format($totalItem) ?></h2>
            <p class="mb-0">Total Item Aset</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="financial-stat-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
            <i class="bi bi-graph-up display-3 mb-3"></i>
            <h2 class="fw-bold">Rp <?= number_format($rataAsetPerItem / 1000, 0) ?>K</h2>
            <p class="mb-0">Rata-rata per Item</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="financial-stat-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <i class="bi bi-wrench display-3 mb-3"></i>
            <h2 class="fw-bold">Rp <?= number_format($totalBiayaMaintenance / 1000, 0) ?>K</h2>
            <p class="mb-0">Biaya Maintenance</p>
        </div>
    </div>
</div>

<!-- Main Financial Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-danger text-white">
        <h6 class="mb-0"><i class="bi bi-table me-2"></i>Rincian Aset Inventaris</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="reportTable">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="30%">Nama Barang</th>
                    <th width="15%">Kategori</th>
                    <th width="12%">Lokasi</th>
                    <th width="12%">Tgl Pembelian</th>
                    <th class="text-end" width="13%">Nilai (Rp)</th>
                    <th class="text-center" width="13%">% dari Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($data)): 
                    $harga = (float)($row['harga_pembelian'] ?? 0);
                    $persentase = $totalAset > 0 ? ($harga / $totalAset) * 100 : 0;
                    $tglBeli = !empty($row['tanggal_pembelian']) ? date('d/m/Y', strtotime($row['tanggal_pembelian'])) : 'Tidak diketahui';
                    
                    // Determine asset tier
                    $tier = 'low';
                    if ($harga >= 10000000) $tier = 'high';
                    elseif ($harga >= 5000000) $tier = 'medium';
                ?>
                <tr class="<?= $tier === 'high' ? 'table-success' : ($tier === 'medium' ? 'table-warning' : '') ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                                <div class="me-2">
                                <?php if ($tier === 'high'): ?>
                                    <i class="bi bi-gem text-success" title="Aset Tinggi"></i>
                                <?php elseif ($tier === 'medium'): ?>
                                    <i class="bi bi-star text-warning" title="Aset Menengah"></i>
                                <?php else: ?>
                                    <i class="bi bi-circle text-muted" title="Aset Standar"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_barang']) ?></div>
                                <small class="text-muted">Tier: <?= ucfirst($tier) ?> Value</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if (!empty($row['nama_kategori'])): ?>
                            <span class="badge bg-light text-dark border">
                                <?= htmlspecialchars($row['nama_kategori']) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Tidak dikategorikan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <i class="bi bi-geo-alt text-muted me-1"></i>
                        <?= htmlspecialchars($row['nama_lokasi'] ?? 'Tidak diketahui') ?>
                    </td>
                    <td>
                        <i class="bi bi-calendar text-primary me-1"></i>
                        <?= $tglBeli ?>
                    </td>
                    <td class="text-end">
                        <span class="asset-value" style="color: <?= $tier === 'high' ? '#28a745' : ($tier === 'medium' ? '#ffc107' : '#6c757d') ?>;">
                            <?= number_format($harga, 0, ',', '.') ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="progress me-2" style="width: 60px; height: 6px;">
                                <div class="progress-bar bg-<?= $tier === 'high' ? 'success' : ($tier === 'medium' ? 'warning' : 'secondary') ?>" 
                                     style="width: <?= min($persentase * 10, 100) ?>%"></div>
                            </div>
                            <span class="small"><?= number_format($persentase, 1) ?>%</span>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot class="table-danger">
                <tr class="fw-bold">
                    <th colspan="5" class="text-end">TOTAL NILAI ASET:</th>
                    <th class="text-end">Rp <?= number_format($totalAset, 0, ',', '.') ?></th>
                    <th class="text-center">100%</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Financial Analysis Section -->
<div class="row g-4">
    <!-- Asset Breakdown by Category -->
    <div class="col-lg-6">
        <div class="asset-breakdown-card">
            <h5 class="mb-4">
                <i class="bi bi-pie-chart-fill me-2"></i>
                Distribusi Aset per Kategori
            </h5>
            
            <?php if (empty($asetPerKategori)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-inbox display-4 opacity-50"></i>
                    <p class="mt-2 opacity-75">Tidak ada data kategori</p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($asetPerKategori, 0, 5, true) as $kategori => $nilai): ?>
                <div class="category-bar">
                    <div class="d-flex justify-content-between align-items-center text-dark">
                        <div>
                            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($kategori) ?></h6>
                            <small class="text-muted">
                                <?= $totalAset > 0 ? number_format(($nilai / $totalAset) * 100, 1) : 0 ?>% dari total aset
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">Rp <?= number_format($nilai / 1000000, 1) ?>M</div>
                            <small class="text-muted"><?= number_format($nilai, 0, ',', '.') ?></small>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-primary" 
                             style="width: <?= $totalAset > 0 ? ($nilai / max(array_values($asetPerKategori))) * 100 : 0 ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Asset Breakdown by Location -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Distribusi Aset per Lokasi</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($asetPerLokasi)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-building display-4"></i>
                    <p class="mt-2">Tidak ada data lokasi</p>
                </div>
                <?php else: ?>
                <div style="max-height: 400px; overflow-y: auto;">
                    <?php foreach ($asetPerLokasi as $lokasi => $nilai): ?>
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-building text-success me-2"></i>
                                <?= htmlspecialchars($lokasi) ?>
                            </div>
                            <small class="text-muted">
                                <?= $totalAset > 0 ? number_format(($nilai / $totalAset) * 100, 1) : 0 ?>% dari total
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">Rp <?= number_format($nilai / 1000000, 1) ?>M</div>
                            <div class="progress mt-1" style="width: 80px; height: 4px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?= ($nilai / max(array_values($asetPerLokasi))) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Financial Metrics Dashboard -->
<div class="row mt-4 g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Indikator Keuangan Utama</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="financial-metric">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="text-primary mb-1">Total Investasi Aset</h6>
                                    <p class="mb-0 text-muted small">Nilai keseluruhan inventaris</p>
                                </div>
                                <div class="col-4 text-end">
                                    <h4 class="text-primary mb-0">Rp <?= number_format($totalAset / 1000000, 1) ?>M</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="financial-metric">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="text-success mb-1">Nilai per Unit</h6>
                                    <p class="mb-0 text-muted small">Rata-rata investasi per barang</p>
                                </div>
                                <div class="col-4 text-end">
                                    <h4 class="text-success mb-0">Rp <?= number_format($rataAsetPerItem / 1000, 0) ?>K</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="financial-metric">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="text-warning mb-1">Biaya Maintenance</h6>
                                    <p class="mb-0 text-muted small">Total pengeluaran perawatan</p>
                                </div>
                                <div class="col-4 text-end">
                                    <h4 class="text-warning mb-0">Rp <?= number_format($totalBiayaMaintenance / 1000, 0) ?>K</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="financial-metric">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="text-info mb-1">Rasio Maintenance</h6>
                                    <p class="mb-0 text-muted small">Biaya maintenance vs nilai aset</p>
                                </div>
                                <div class="col-4 text-end">
                                    <h4 class="text-info mb-0"><?= number_format($rasioMaintenanceAset, 1) ?>%</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Asset Quality Indicators -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="text-secondary mb-3">Indikator Kualitas Aset</h6>
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="text-success">
                                <i class="bi bi-trophy display-6"></i>
                                <h6 class="mt-2">Aset Tertinggi</h6>
                                <p class="small mb-0"><?= htmlspecialchars($barangTertinggi ?: 'N/A') ?></p>
                                <strong class="text-success">Rp <?= number_format($nilaiTertinggi / 1000, 0) ?>K</strong>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-primary">
                                <i class="bi bi-building display-6"></i>
                                <h6 class="mt-2">Total Lokasi</h6>
                                <strong class="text-primary"><?= count($asetPerLokasi) ?></strong>
                                <p class="small mb-0">lokasi aktif</p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-info">
                                <i class="bi bi-tags display-6"></i>
                                <h6 class="mt-2">Total Kategori</h6>
                                <strong class="text-info"><?= count($asetPerKategori) ?></strong>
                                <p class="small mb-0">jenis aset</p>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="text-warning">
                                <i class="bi bi-calendar display-6"></i>
                                <h6 class="mt-2">Rentang Tahun</h6>
                                <strong class="text-warning">
                                    <?= !empty($asetPerTahun) ? min(array_keys($asetPerTahun)) . '-' . max(array_keys($asetPerTahun)) : 'N/A' ?>
                                </strong>
                                <p class="small mb-0">periode investasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Yearly Investment Trend -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-calendar-range me-2"></i>Tren Investasi Tahunan</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($asetPerTahun)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x display-4"></i>
                    <p class="mt-2">Tidak ada data tahun pembelian</p>
                </div>
                <?php else: ?>
                <div style="max-height: 350px; overflow-y: auto;">
                    <?php foreach ($asetPerTahun as $tahun => $nilai): ?>
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-calendar-event text-info me-2"></i>
                                Tahun <?= $tahun ?>
                            </div>
                            <small class="text-muted">Investasi periode ini</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-info">Rp <?= number_format($nilai / 1000000, 1) ?>M</div>
                            <div class="progress mt-1" style="width: 60px; height: 4px;">
                                <div class="progress-bar bg-info" 
                                     style="width: <?= ($nilai / max(array_values($asetPerTahun))) * 100 ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary & Recommendations -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Ringkasan Keuangan & Rekomendasi</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="text-primary mb-3">Analisis Keuangan</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-success mb-2">
                                <i class="bi bi-check-circle me-2"></i>Kinerja Positif
                            </h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right text-success me-2"></i>
                                    Total aset senilai <strong>Rp <?= number_format($totalAset / 1000000, 1) ?>M</strong>
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right text-success me-2"></i>
                                    Diversifikasi aset di <strong><?= count($asetPerKategori) ?> kategori</strong>
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right text-success me-2"></i>
                                    Distribusi di <strong><?= count($asetPerLokasi) ?> lokasi</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded">
                            <h6 class="text-warning mb-2">
                                <i class="bi bi-exclamation-triangle me-2"></i>Area Perhatian
                            </h6>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right text-warning me-2"></i>
                                    Rasio maintenance <strong><?= number_format($rasioMaintenanceAset, 1) ?>%</strong> dari nilai aset
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right text-warning me-2"></i>
                                    Perlu asuransi untuk aset tinggi
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-arrow-right text-warning me-2"></i>
                                    Monitor depresiasi nilai aset
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <h6 class="text-info mb-3">Rekomendasi Strategis</h6>
                <div class="bg-info bg-opacity-10 p-3 rounded">
                    <ol class="small mb-0">
                        <li class="mb-2">Lakukan evaluasi ulang nilai aset secara berkala</li>
                        <li class="mb-2">Pertimbangkan asuransi untuk aset >Rp 10M</li>
                        <li class="mb-2">Optimalisasi biaya maintenance (target <3% dari nilai aset)</li>
                        <li class="mb-2">Dokumentasi lengkap untuk audit keuangan</li>
                        <li class="mb-0">Rencanakan penggantian aset yang sudah tua</li>
                    </ol>
                </div>
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