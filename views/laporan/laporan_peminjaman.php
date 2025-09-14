<?php
/**
 * Laporan Peminjaman Barang - PTUN Banjarmasin
 * Professional Borrowing Report with Analytics
 * 
 * LOKASI FILE: views/laporan/laporan_peminjaman.php (REPLACE FILE INI)
 */

$data = getLaporanPeminjaman($_GET['start'] ?? '', $_GET['end'] ?? '');

if (!$data) {
    echo '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Tidak ada data peminjaman pada periode yang dipilih.</div>';
    return;
}

$totalPeminjaman = mysqli_num_rows($data);
$statusAktif = 0;
$statusKembali = 0;
$statusTerlambat = 0;
$peminjamTerbanyak = [];
$barangTerpopuler = [];

// Analyze data
mysqli_data_seek($data, 0);
while ($row = mysqli_fetch_assoc($data)) {
    // Count status
    switch (strtolower($row['status'])) {
        case 'aktif': $statusAktif++; break;
        case 'kembali': $statusKembali++; break;
        case 'terlambat': $statusTerlambat++; break;
    }
    
    // Count borrowers
    $peminjam = $row['username'];
    $peminjamTerbanyak[$peminjam] = ($peminjamTerbanyak[$peminjam] ?? 0) + 1;
    
    // Count popular items
    $barang = $row['nama_barang'];
    $barangTerpopuler[$barang] = ($barangTerpopuler[$barang] ?? 0) + 1;
}

// Sort arrays
arsort($peminjamTerbanyak);
arsort($barangTerpopuler);

// Reset data pointer
mysqli_data_seek($data, 0);
?>

<style>
    .borrowing-stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        transition: transform 0.3s ease;
    }
    
    .borrowing-stat-card:hover {
        transform: translateY(-5px);
    }
    
    .status-indicator {
        padding: 0.4rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    
    .timeline-item {
        border-left: 3px solid var(--ptun-primary);
        padding-left: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .timeline-item::before {
        content: '';
        width: 12px;
        height: 12px;
        background: var(--ptun-primary);
        border-radius: 50%;
        position: absolute;
        left: -7px;
        top: 8px;
    }
    
    .top-borrower-item {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-left: 4px solid var(--ptun-accent);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Report Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h4 class="fw-bold text-warning">📋 LAPORAN PEMINJAMAN BARANG</h4>
        <p class="text-muted mb-0">Data peminjaman dan pengelolaan sirkulasi barang inventaris PTUN Banjarmasin</p>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-warning text-dark fs-6 px-3 py-2">
            Total Transaksi: <?= number_format($totalPeminjaman) ?>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="borrowing-stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="bi bi-list-ul display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($totalPeminjaman) ?></h3>
            <p class="mb-0">Total Peminjaman</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="borrowing-stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="bi bi-check-circle display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($statusKembali) ?></h3>
            <p class="mb-0">Sudah Dikembalikan</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="borrowing-stat-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
            <i class="bi bi-clock display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($statusAktif) ?></h3>
            <p class="mb-0">Sedang Dipinjam</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="borrowing-stat-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
            <i class="bi bi-exclamation-triangle display-4 mb-3"></i>
            <h3 class="fw-bold"><?= number_format($statusTerlambat) ?></h3>
            <p class="mb-0">Terlambat</p>
        </div>
    </div>
</div>

<!-- Main Data Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0"><i class="bi bi-table me-2"></i>Detail Transaksi Peminjaman</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="reportTable">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="25%">Nama Barang</th>
                    <th width="20%">Peminjam</th>
                    <th width="12%">Tanggal Pinjam</th>
                    <th width="12%">Target Kembali</th>
                    <th width="12%">Tanggal Kembali</th>
                    <th class="text-center" width="14%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($data)): 
                    $tglPinjam = date('d/m/Y', strtotime($row['tanggal_pinjam']));
                    $targetKembali = $row['target_kembali'] ? date('d/m/Y', strtotime($row['target_kembali'])) : '-';
                    $tglKembali = $row['tanggal_kembali'] ? date('d/m/Y', strtotime($row['tanggal_kembali'])) : '-';
                    
                    // Determine if overdue
                    $isOverdue = false;
                    if ($row['status'] === 'Aktif' && $row['target_kembali']) {
                        $isOverdue = strtotime($row['target_kembali']) < time();
                    }
                    
                    $actualStatus = $isOverdue ? 'Terlambat' : $row['status'];
                ?>
                <tr class="<?= $isOverdue ? 'table-danger' : '' ?>">
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <div class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_barang']) ?></div>
                        <small class="text-muted">ID: PMJ-<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?></small>
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
                        <i class="bi bi-calendar-check text-warning me-1"></i>
                        <?= $targetKembali ?>
                    </td>
                    <td>
                        <?php if ($tglKembali !== '-'): ?>
                            <i class="bi bi-calendar-check-fill text-success me-1"></i>
                            <?= $tglKembali ?>
                        <?php else: ?>
                            <span class="text-muted">Belum dikembalikan</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php
                        $statusClass = [
                            'Aktif' => 'bg-warning text-dark',
                            'Kembali' => 'bg-success',
                            'Terlambat' => 'bg-danger'
                        ][strtolower($actualStatus)] ?? 'bg-secondary';
                        ?>
                        <span class="status-indicator <?= $statusClass ?>">
                            <?= $actualStatus ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Analytics Section -->
<div class="row g-4">
    <!-- Top Borrowers -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Peminjam Teraktif</h6>
            </div>
            <div class="card-body">
                <?php 
                $count = 0;
                foreach (array_slice($peminjamTerbanyak, 0, 5, true) as $peminjam => $jumlah): 
                    $count++;
                ?>
                <div class="top-borrower-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary me-2"><?= $count ?></span>
                            <span class="fw-semibold"><?= htmlspecialchars($peminjam) ?></span>
                        </div>
                        <span class="badge bg-light text-dark"><?= $jumlah ?> transaksi</span>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($peminjamTerbanyak)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox display-4"></i>
                    <p class="mt-2">Belum ada data peminjam</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Popular Items -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-star-fill me-2"></i>Barang Paling Populer</h6>
            </div>
            <div class="card-body">
                <?php 
                $count = 0;
                foreach (array_slice($barangTerpopuler, 0, 5, true) as $barang => $jumlah): 
                    $count++;
                ?>
                <div class="top-borrower-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-info me-2"><?= $count ?></span>
                            <span class="fw-semibold"><?= htmlspecialchars($barang) ?></span>
                        </div>
                        <span class="badge bg-light text-dark"><?= $jumlah ?>x dipinjam</span>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($barangTerpopuler)): ?>
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox display-4"></i>
                    <p class="mt-2">Belum ada data barang populer</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Performance Summary -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-success text-white">
        <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Ringkasan Kinerja Peminjaman</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <!-- Performance Metrics -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="text-success">
                                <i class="bi bi-check-circle display-4"></i>
                                <h4 class="mt-2">
                                    <?= $totalPeminjaman > 0 ? number_format(($statusKembali / $totalPeminjaman) * 100, 1) : 0 ?>%
                                </h4>
                                <small class="text-muted">Tingkat Pengembalian</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="text-warning">
                                <i class="bi bi-clock display-4"></i>
                                <h4 class="mt-2">
                                    <?= $totalPeminjaman > 0 ? number_format(($statusAktif / $totalPeminjaman) * 100, 1) : 0 ?>%
                                </h4>
                                <small class="text-muted">Sedang Dipinjam</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="text-danger">
                                <i class="bi bi-exclamation-triangle display-4"></i>
                                <h4 class="mt-2">
                                    <?= $totalPeminjaman > 0 ? number_format(($statusTerlambat / $totalPeminjaman) * 100, 1) : 0 ?>%
                                </h4>
                                <small class="text-muted">Keterlambatan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <!-- Quick Statistics -->
                <div class="bg-light p-3 rounded">
                    <h6 class="text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Statistik Cepat</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-arrow-right text-primary me-2"></i>
                            <strong><?= count($peminjamTerbanyak) ?></strong> peminjam aktif
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-arrow-right text-primary me-2"></i>
                            <strong><?= count($barangTerpopuler) ?></strong> jenis barang dipinjam
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-arrow-right text-primary me-2"></i>
                            Rata-rata: <strong><?= count($peminjamTerbanyak) > 0 ? number_format($totalPeminjaman / count($peminjamTerbanyak), 1) : 0 ?></strong> peminjaman/orang
                        </li>
                    </ul>
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
            Periode laporan: <?= date('d/m/Y', strtotime($_GET['start'] ?? date('Y-m-01'))) ?> - <?= date('d/m/Y', strtotime($_GET['end'] ?? date('Y-m-t'))) ?>
        </small>
    </div>
    <div class="col-md-6 text-end">
        <small>
            <i class="bi bi-clock me-1"></i>
            Dibuat: <?= date('d F Y, H:i:s') ?> WITA
        </small>
    </div>
</div>