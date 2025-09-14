<?php
/**
 * Laporan Pengembalian Barang - PTUN Banjarmasin
 * Professional Return Report with Condition Analysis
 * 
 * LOKASI FILE: views/laporan/laporan_pengembalian.php (REPLACE FILE INI)
 */

$data = getLaporanPengembalian($_GET['start'] ?? '', $_GET['end'] ?? '');

if (!$data) {
    echo '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ada data pengembalian pada periode yang dipilih.</div>';
    return;
}

$totalPengembalian = mysqli_num_rows($data);
$kondisiBaik = 0;
$kondisiRusak = 0;
$kondisiHilang = 0;
$tepatWaktu = 0;
$terlambat = 0;
$pengembalianPerHari = [];

// Analyze data
mysqli_data_seek($data, 0);
while ($row = mysqli_fetch_assoc($data)) {
    // Count conditions
    switch (strtolower($row['kondisi'])) {
        case 'baik': $kondisiBaik++; break;
        case 'rusak': $kondisiRusak++; break;
        case 'hilang': $kondisiHilang++; break;
    }
    
    // Count by date for trend analysis
    $tanggal = date('Y-m-d', strtotime($row['tanggal_kembali']));
    $pengembalianPerHari[$tanggal] = ($pengembalianPerHari[$tanggal] ?? 0) + 1;
    
    // Analyze punctuality (assuming target return date exists)
    if (isset($row['tanggal_pinjam']) && isset($row['target_kembali'])) {
        $targetKembali = strtotime($row['target_kembali']);
        $actualKembali = strtotime($row['tanggal_kembali']);
        
        if ($actualKembali <= $targetKembali) {
            $tepatWaktu++;
        } else {
            $terlambat++;
        }
    }
}

// Sort dates for trend analysis
ksort($pengembalianPerHari);

// Reset data pointer
mysqli_data_seek($data, 0);
?>

<style>
    .return-stat-card {
        border-radius: 15px;
        color: white;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .return-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
    }
    
    .condition-analysis {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }
    
    .trend-chart {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .daily-trend-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #e9ecef;
        transition: background 0.2s ease;
    }
    
    .daily-trend-item:hover {
        background: #f8f9fa;
    }
    
    .condition-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
</style>

<!-- Report Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h4 class="fw-bold text-success">📤 LAPORAN PENGEMBALIAN BARANG</h4>
        <p class="text-muted mb-0">Analisis pengembalian dan kondisi barang setelah peminjaman - PTUN Banjarmasin</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-success fs-6 px-3 py-2">
            Total Pengembalian: <?= number_format($totalPengembalian) ?>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-2 col-md-4">
        <div class="return-stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="bi bi-arrow-up-square display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($totalPengembalian) ?></h3>
            <p class="mb-0">Total Pengembalian</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="return-stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="bi bi-check-circle display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($kondisiBaik) ?></h3>
            <p class="mb-0">Kondisi Baik</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="return-stat-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <i class="bi bi-exclamation-triangle display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($kondisiRusak) ?></h3>
            <p class="mb-0">Kondisi Rusak</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="return-stat-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
            <i class="bi bi-clock-fill display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($tepatWaktu) ?></h3>
            <p class="mb-0">Tepat Waktu</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="return-stat-card" style="background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);">
            <i class="bi bi-clock display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($terlambat) ?></h3>
            <p class="mb-0">Terlambat</p>
        </div>
    </div>
    
    <div class="col-lg-2 col-md-4">
        <div class="return-stat-card" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);">
            <i class="bi bi-percent display-4 mb-3"></i>
            <h3 class="fw-bold">
                <?= $totalPengembalian > 0 ? number_format(($kondisiBaik / $totalPengembalian) * 100, 1) : 0 ?>%
            </h3>
            <p class="mb-0">Tingkat Kebaikan</p>
        </div>
    </div>
</div>

<!-- Main Data Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <h6 class="mb-0"><i class="bi bi-table me-2"></i>Detail Transaksi Pengembalian</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="reportTable">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="25%">Nama Barang</th>
                    <th width="15%">Peminjam</th>
                    <th width="12%">Tgl Pinjam</th>
                    <th width="12%">Tgl Kembali</th>
                    <th class="text-center" width="10%">Kondisi</th>
                    <th class="text-center" width="8%">Status</th>
                    <th width="13%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($data)): 
                    $tglPinjam = isset($row['tanggal_pinjam']) ? date('d/m/Y', strtotime($row['tanggal_pinjam'])) : '-';
                    $tglKembali = date('d/m/Y', strtotime($row['tanggal_kembali']));
                    
                    // Determine if it's late
                    $isLate = false;
                    if (isset($row['target_kembali'])) {
                        $isLate = strtotime($row['tanggal_kembali']) > strtotime($row['target_kembali']);
                    }
                ?>
                <tr class="<?= $row['kondisi'] === 'Rusak' ? 'table-warning' : '' ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_barang']) ?></div>
                        <small class="text-muted">ID: KMB-<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></small>
                    </td>
                    <td>
                        <i class="bi bi-person text-muted me-2"></i>
                        <span class="fw-semibold"><?= htmlspecialchars($row['username'] ?? 'Unknown') ?></span>
                    </td>
                    <td>
                        <i class="bi bi-calendar-plus text-primary me-1"></i>
                        <?= $tglPinjam ?>
                    </td>
                    <td>
                        <i class="bi bi-calendar-check-fill text-success me-1"></i>
                        <?= $tglKembali ?>
                        <?php if ($isLate): ?>
                            <br><small class="text-danger">
                                <i class="bi bi-clock me-1"></i>Terlambat
                            </small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php
                        $kondisiClass = [
                            'baik' => 'bg-success',
                            'rusak' => 'bg-danger', 
                            'hilang' => 'bg-warning text-dark'
                        ][strtolower($row['kondisi'])] ?? 'bg-secondary';
                        ?>
                        <span class="condition-badge <?= $kondisiClass ?>">
                            <?= ucfirst($row['kondisi']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?php if ($isLate): ?>
                            <span class="badge bg-warning text-dark">Terlambat</span>
                        <?php else: ?>
                            <span class="badge bg-success">Tepat Waktu</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($row['catatan'])): ?>
                            <small><?= htmlspecialchars($row['catatan']) ?></small>
                        <?php else: ?>
                            <small class="text-muted">Tidak ada catatan</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Analysis Section -->
<div class="row g-4">
    <!-- Condition Analysis -->
    <div class="col-lg-6">
        <div class="condition-analysis">
            <h5 class="mb-4">
                <i class="bi bi-pie-chart-fill me-2"></i>
                Analisis Kondisi Pengembalian
            </h5>
            
            <div class="row text-center mb-4">
                <div class="col-4">
                    <div class="bg-white bg-opacity-25 rounded p-3">
                        <i class="bi bi-check-circle display-5"></i>
                        <h4 class="mt-2"><?= $kondisiBaik ?></h4>
                        <small>Baik</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white bg-opacity-25 rounded p-3">
                        <i class="bi bi-exclamation-triangle display-5"></i>
                        <h4 class="mt-2"><?= $kondisiRusak ?></h4>
                        <small>Rusak</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-white bg-opacity-25 rounded p-3">
                        <i class="bi bi-question-circle display-5"></i>
                        <h4 class="mt-2"><?= $kondisiHilang ?></h4>
                        <small>Hilang</small>
                    </div>
                </div>
            </div>
            
            <!-- Progress bars -->
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Tingkat Kebaikan</span>
                    <span><?= $totalPengembalian > 0 ? number_format(($kondisiBaik / $totalPengembalian) * 100, 1) : 0 ?>%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: <?= $totalPengembalian > 0 ? ($kondisiBaik / $totalPengembalian) * 100 : 0 ?>%"></div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Tingkat Kerusakan</span>
                    <span><?= $totalPengembalian > 0 ? number_format(($kondisiRusak / $totalPengembalian) * 100, 1) : 0 ?>%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-danger" style="width: <?= $totalPengembalian > 0 ? ($kondisiRusak / $totalPengembalian) * 100 : 0 ?>%"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daily Trend -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Tren Pengembalian Harian</h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pengembalianPerHari)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x display-4"></i>
                    <p class="mt-2">Tidak ada data tren pengembalian</p>
                </div>
                <?php else: ?>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php foreach ($pengembalianPerHari as $tanggal => $jumlah): ?>
                    <div class="daily-trend-item">
                        <div>
                            <i class="bi bi-calendar-date text-primary me-2"></i>
                            <span class="fw-semibold"><?= date('d F Y', strtotime($tanggal)) ?></span>
                        </div>
                        <span class="badge bg-primary"><?= $jumlah ?> item</span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Summary Report -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Ringkasan Kinerja Pengembalian</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h6 class="text-primary mb-3">Indikator Kinerja Utama (KPI)</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded text-center">
                            <div class="text-success mb-2">
                                <i class="bi bi-trophy-fill display-6"></i>
                            </div>
                            <h5><?= $totalPengembalian > 0 ? number_format(($kondisiBaik / $totalPengembalian) * 100, 1) : 0 ?>%</h5>
                            <small class="text-muted">Tingkat Kebaikan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded text-center">
                            <div class="text-primary mb-2">
                                <i class="bi bi-clock-fill display-6"></i>
                            </div>
                            <h5><?= ($tepatWaktu + $terlambat) > 0 ? number_format(($tepatWaktu / ($tepatWaktu + $terlambat)) * 100, 1) : 0 ?>%</h5>
                            <small class="text-muted">Ketepatan Waktu</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded text-center">
                            <div class="text-warning mb-2">
                                <i class="bi bi-exclamation-triangle-fill display-6"></i>
                            </div>
                            <h5><?= $totalPengembalian > 0 ? number_format(($kondisiRusak / $totalPengembalian) * 100, 1) : 0 ?>%</h5>
                            <small class="text-muted">Tingkat Kerusakan</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded text-center">
                            <div class="text-info mb-2">
                                <i class="bi bi-graph-up display-6"></i>
                            </div>
                            <h5><?= count($pengembalianPerHari) > 0 ? number_format($totalPengembalian / count($pengembalianPerHari), 1) : 0 ?></h5>
                            <small class="text-muted">Rata-rata/Hari</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <h6 class="text-warning mb-3">Rekomendasi</h6>
                <ul class="list-unstyled">
                    <?php if ($kondisiRusak > 0): ?>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-danger me-2"></i>
                        <small>Perlu evaluasi prosedur peminjaman untuk <?= $kondisiRusak ?> item rusak</small>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($terlambat > 0): ?>
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-warning me-2"></i>
                        <small>Tingkatkan sistem reminder untuk <?= $terlambat ?> keterlambatan</small>
                    </li>
                    <?php endif; ?>
                    
                    <li class="mb-2">
                        <i class="bi bi-arrow-right text-success me-2"></i>
                        <small>Tingkat kebaikan barang <?= $totalPengembalian > 0 ? number_format(($kondisiBaik / $totalPengembalian) * 100, 1) : 0 ?>% - <?= $kondisiBaik >= $kondisiRusak ? 'Baik' : 'Perlu Diperbaiki' ?></small>
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