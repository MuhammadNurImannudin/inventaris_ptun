<?php
/**
 * Laporan Maintenance - PTUN Banjarmasin
 * Data berasal dari fungsi getLaporanMaintenance()
 */
$data = getLaporanMaintenance($_GET['start'] ?? '', $_GET['end'] ?? '');
$total = 0;
?>

<!-- Header Laporan -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">
    <i class="bi bi-wrench text-primary me-2"></i>Laporan Maintenance
  </h5>
  <a href="print.php?<?= http_build_query($_GET) ?>" class="btn btn-outline-danger btn-sm">
    <i class="bi bi-file-earmark-pdf"></i> Download PDF
  </a>
</div>

<!-- Tabel Hasil -->
<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light text-center">
      <tr>
        <th width="5%">No</th>
        <th>Barang</th>
        <th width="13%">Tanggal</th>
        <th width="12%">Jenis</th>
        <th width="12%">Biaya</th>
        <th width="12%">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($data) > 0): ?>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): $total += $row['biaya']; ?>
          <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
            <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal_maintenance'])) ?></td>
            <td><?= $row['jenis'] ?></td>
            <td class="text-end">Rp <?= number_format($row['biaya'], 0, ',', '.') ?></td>
            <td class="text-center">
              <?php
                // ✅ Ganti match dengan switch agar support PHP 7
                $status = strtolower($row['status']);
                switch ($status) {
                  case 'selesai':
                    $badge = 'success';
                    break;
                  case 'proses':
                    $badge = 'warning';
                    break;
                  case 'terjadwal':
                    $badge = 'info';
                    break;
                  default:
                    $badge = 'secondary';
                }
              ?>
              <span class="badge bg-<?= $badge ?>"><?= $row['status'] ?></span>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted">Belum ada data maintenance pada periode yang dipilih.</td>
        </tr>
      <?php endif; ?>
    </tbody>
    <tfoot class="table-info">
      <tr>
        <th colspan="4" class="text-end">Total Biaya</th>
        <th class="text-end">Rp <?= number_format($total, 0, ',', '.') ?></th>
        <th></th>
      </tr>
    </tfoot>
  </table>
</div>

<!-- Ringkasan cepat -->
<div class="row g-3 mt-2">
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-list-check text-primary fs-4"></i>
        <h5 class="mb-0 mt-2"><?= mysqli_num_rows($data) ?></h5>
        <small class="text-muted">Total Record</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-cash-coin text-success fs-4"></i>
        <h5 class="mb-0 mt-2">Rp <?= number_format($total, 0, ',', '.') ?></h5>
        <small class="text-muted">Total Biaya</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-check-circle-fill text-success fs-4"></i>
        <h5 class="mb-0 mt-2">
          <?php
            mysqli_data_seek($data, 0);
            $selesai = 0;
            while ($r = mysqli_fetch_assoc($data)) {
              if (strtolower($r['status']) === 'selesai') $selesai++;
            }
            echo $selesai;
          ?>
        </h5>
        <small class="text-muted">Selesai</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-clock-fill text-info fs-4"></i>
        <h5 class="mb-0 mt-2">
          <?php
            mysqli_data_seek($data, 0);
            $terjadwal = 0;
            while ($r = mysqli_fetch_assoc($data)) {
              if (strtolower($r['status']) === 'terjadwal') $terjadwal++;
            }
            echo $terjadwal;
          ?>
        </h5>
        <small class="text-muted">Terjadwal</small>
      </div>
    </div>
  </div>
</div>
