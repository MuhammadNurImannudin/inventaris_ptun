<?php
/**
 * Laporan Inventaris Barang - PTUN Banjarmasin
 * Professional Inventory Report with Statistics
 * 
 * LOKASI FILE: views/laporan/laporan_inventaris.php (REPLACE FILE INI)
 */

$data = getLaporanInventaris(
    $_GET['start'] ?? '',
    $_GET['end'] ?? '',
    $_GET['kategori'] ?? '',
    $_GET['lokasi'] ?? ''
);

if (!$data) {
    echo '<div class="alert alert-warning">Tidak ada data inventaris pada periode yang dipilih.</div>';
    return;
}

$grandTotal = 0;
$totalBarang = mysqli_num_rows($data);
$kondisiBaik = 0;
$kondisiRusak = 0;
$kondisiHilang = 0;
$statusTersedia = 0;
$statusDipinjam = 0;
$statusMaintenance = 0;

// Calculate statistics
mysqli_data_seek($data, 0);
while ($row = mysqli_fetch_assoc($data)) {
    $grandTotal += (int)$row['harga_pembelian'];
    
    // Count by condition
    switch (strtolower($row['kondisi'])) {
        case 'baik': $kondisiBaik++; break;
        case 'rusak': $kondisiRusak++; break;
        case 'hilang': $kondisiHilang++; break;
    }
    
    // Count by status
    switch (strtolower($row['status'])) {
        case 'tersedia': $statusTersedia++; break;
        case 'dipinjam': $statusDipinjam++; break;
        case 'maintenance': $statusMaintenance++; break;
    }
}

// Reset data pointer
mysqli_data_seek($data, 0);
?>

<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        display: block;
    }
    
    .report-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .table-header {
        background: linear-gradient(135deg, var(--ptun-primary), var(--ptun-secondary));
        color: white;
    }
    
    .condition-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>

<!-- Report Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h4 class="fw-bold text-primary">📊 LAPORAN INVENTARIS BARANG</h4>
        <p class="text-muted mb-0">Data lengkap inventaris Pengadilan Tata Usaha Negara Banjarmasin</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-primary fs-6 px-3 py-2">
            Total: <?= number_format($totalBarang) ?> Item
        </div>
    </div>
</div>

<!-- Statistics Summary -->
<div class="row g-3 mb-4">
    <div class="col-lg-2 col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="bi bi-archive display-4 mb-2"></i>
            <span class="stat-number"><?= number_format($totalBarang) ?></span>
            <small>Total Barang</small>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="bi bi-check-circle display-4 mb-2"></i>
            <span class="stat-number"><?= number_format($kondisiBaik) ?></span>
            <small>Kondisi Baik</small>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);">
            <i class="bi bi-exclamation-triangle display-4 mb-2"></i>
            <span class="stat-number"><?= number_format($kondisiRusak) ?></span>
            <small>Kondisi Rusak</small>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
            <i class="bi bi-check-square display-4 mb-2"></i>
            <span class="stat-number"><?= number_format($statusTersedia) ?></span>
            <small>Tersedia</small>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
            <i class="bi bi-arrow-repeat display-4 mb-2"></i>
            <span class="stat-number"><?= number_format($statusDipinjam) ?></span>
            <small>Dipinjam</small>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #ff8a80 0%, #ea4c89 100%);">
            <i class="bi bi-currency-dollar display-4 mb-2"></i>
            <span class="stat-number">Rp <?= number_format($grandTotal / 1000000, 1) ?>M</span>
            <small>Total Nilai</small>
        </div>
    </div>
</div>

<!-- Detailed Table -->
<div class="card border-0 report-table">
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="reportTable">
            <thead class="table-header">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="12%">No. Inventaris</th>
                    <th width="25%">Nama Barang</th>
                    <th width="15%">Kategori</th>
                    <th width="15%">Lokasi</th>
                    <th class="text-center" width="10%">Kondisi</th>
                    <th class="text-center" width="10%">Status</th>
                    <th class="text-end" width="8%">Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($data)): 
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($row['nomor_inventaris'] ?? '-') ?></td>
                    <td>
                        <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_barang']) ?></div>
                        <?php if (!empty($row['nomor_serial'])): ?>
                            <small class="text-muted">SN: <?= htmlspecialchars($row['nomor_serial']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            <?= htmlspecialchars($row['nama_kategori'] ?? 'Tidak Dikategorikan') ?>
                        </span>
                    </td>
                    <td>
                        <i class="bi bi-geo-alt text-muted me-1"></i>
                        <?= htmlspecialchars($row['nama_lokasi'] ?? 'Lokasi Tidak Diketahui') ?>
                    </td>
                    <td class="text-center">
                        <?php
                        $kondisi = strtolower($row['kondisi']);
                        $kondisiClass = [
                            'baik' => 'bg-success',
                            'rusak' => 'bg-danger',
                            'hilang' => 'bg-warning text-dark'
                        ][$kondisi] ?? 'bg-secondary';
                        ?>
                        <span class="condition-badge <?= $kondisiClass ?>">
                            <?= ucfirst($row['kondisi']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?php
                        $status = strtolower($row['status']);
                        $statusClass = [
                            'tersedia' => 'bg-success',
                            'dipinjam' => 'bg-warning text-dark',
                            'maintenance' => 'bg-info text-dark'
                        ][$status] ?? 'bg-secondary';
                        ?>
                        <span class="condition-badge <?= $statusClass ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td class="text-end fw-semibold">
                        <?= number_format((int)$row['harga_pembelian'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot class="table-light">
                <tr class="fw-bold">
                    <th colspan="7" class="text-end">TOTAL NILAI ASET:</th>
                    <th class="text-end text-primary">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Summary Analysis -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Analisis Kondisi Barang</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-success">
                            <i class="bi bi-check-circle display-6"></i>
                            <h4 class="mt-2"><?= $kondisiBaik ?></h4>
                            <small class="text-muted">Baik</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-danger">
                            <i class="bi bi-exclamation-triangle display-6"></i>
                            <h4 class="mt-2"><?= $kondisiRusak ?></h4>
                            <small class="text-muted">Rusak</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-warning">
                            <i class="bi bi-question-circle display-6"></i>
                            <h4 class="mt-2"><?= $kondisiHilang ?></h4>
                            <small class="text-muted">Hilang</small>
                        </div>
                    </div>
                </div>
                
                <div class="progress mt-3" style="height: 10px;">
                    <?php
                    $persenBaik = $totalBarang > 0 ? ($kondisiBaik / $totalBarang) * 100 : 0;
                    $persenRusak = $totalBarang > 0 ? ($kondisiRusak / $totalBarang) * 100 : 0;
                    $persenHilang = $totalBarang > 0 ? ($kondisiHilang / $totalBarang) * 100 : 0;
                    ?>
                    <div class="progress-bar bg-success" style="width: <?= $persenBaik ?>%"></div>
                    <div class="progress-bar bg-danger" style="width: <?= $persenRusak ?>%"></div>
                    <div class="progress-bar bg-warning" style="width: <?= $persenHilang ?>%"></div>
                </div>
                
                <div class="row mt-3 text-center small">
                    <div class="col-4 text-success">Baik: <?= number_format($persenBaik, 1) ?>%</div>
                    <div class="col-4 text-danger">Rusak: <?= number_format($persenRusak, 1) ?>%</div>
                    <div class="col-4 text-warning">Hilang: <?= number_format($persenHilang, 1) ?>%</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Analisis Status Barang</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="text-success">
                            <i class="bi bi-check-square display-6"></i>
                            <h4 class="mt-2"><?= $statusTersedia ?></h4>
                            <small class="text-muted">Tersedia</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-warning">
                            <i class="bi bi-arrow-repeat display-6"></i>
                            <h4 class="mt-2"><?= $statusDipinjam ?></h4>
                            <small class="text-muted">Dipinjam</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-primary">
                            <i class="bi bi-wrench display-6"></i>
                            <h4 class="mt-2"><?= $statusMaintenance ?></h4>
                            <small class="text-muted">Maintenance</small>
                        </div>
                    </div>
                </div>
                
                <div class="progress mt-3" style="height: 10px;">
                    <?php
                    $persenTersedia = $totalBarang > 0 ? ($statusTersedia / $totalBarang) * 100 : 0;
                    $persenDipinjam = $totalBarang > 0 ? ($statusDipinjam / $totalBarang) * 100 : 0;
                    $persenMaintenance = $totalBarang > 0 ? ($statusMaintenance / $totalBarang) * 100 : 0;
                    ?>
                    <div class="progress-bar bg-success" style="width: <?= $persenTersedia ?>%"></div>
                    <div class="progress-bar bg-warning" style="width: <?= $persenDipinjam ?>%"></div>
                    <div class="progress-bar bg-primary" style="width: <?= $persenMaintenance ?>%"></div>
                </div>
                
                <div class="row mt-3 text-center small">
                    <div class="col-4 text-success">Tersedia: <?= number_format($persenTersedia, 1) ?>%</div>
                    <div class="col-4 text-warning">Dipinjam: <?= number_format($persenDipinjam, 1) ?>%</div>
                    <div class="col-4 text-primary">Maintenance: <?= number_format($persenMaintenance, 1) ?>%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommendations -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Rekomendasi Berdasarkan Analisis</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Perlu Perhatian:</h6>
                <ul class="list-unstyled">
                    <?php if ($kondisiRusak > 0): ?>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-danger me-2"></i>
                        <strong><?= $kondisiRusak ?> barang</strong> dalam kondisi rusak perlu diperbaiki
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($kondisiHilang > 0): ?>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-warning me-2"></i>
                        <strong><?= $kondisiHilang ?> barang</strong> dilaporkan hilang, perlu investigasi
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($statusMaintenance > 0): ?>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-info me-2"></i>
                        <strong><?= $statusMaintenance ?> barang</strong> sedang dalam maintenance
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-success"><i class="bi bi-check-circle me-2"></i>Status Positif:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-success me-2"></i>
                        <strong><?= $kondisiBaik ?> barang (<?= number_format($persenBaik, 1) ?>%)</strong> dalam kondisi baik
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-success me-2"></i>
                        <strong><?= $statusTersedia ?> barang</strong> siap digunakan
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-primary me-2"></i>
                        Total nilai aset: <strong>Rp <?= number_format($grandTotal, 0, ',', '.') ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Footer Information -->
<div class="row mt-4 text-muted">
    <div class="col-md-6">
        <small>
            <i class="bi bi-info-circle me-1"></i>
            Laporan dibuat pada: <?= date('d F Y, H:i:s') ?> WITA
        </small>
    </div>
    <div class="col-md-6 text-end">
        <small>
            <i class="bi bi-building me-1"></i>
            PTUN Banjarmasin - Sistem Inventaris v1.0
        </small>
    </div>
</div>