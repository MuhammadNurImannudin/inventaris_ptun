<?php
/**
 * Laporan Inventaris - PTUN Banjarmasin
 * Data berasal dari fungsi getLaporanInventaris()
 */
$data = getLaporanInventaris(
    $_GET['start'] ?? '',
    $_GET['end']   ?? '',
    $_GET['kategori'] ?? '',
    $_GET['lokasi']   ?? ''
);

$grandTotal = 0;
$no         = 1;
?>

<!-- Header Laporan -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="fw-bold mb-0">
    <i class="bi bi-archive-fill text-info me-2"></i>Laporan Inventaris
  </h5>
  <a href="print.php?<?= http_build_query($_GET) ?>" class="btn btn-outline-danger btn-sm">
    <i class="bi bi-file-earmark-pdf"></i> Download PDF
  </a>
</div>

<!-- Ringkasan Cepat -->
<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-box-seam text-primary fs-4"></i>
        <h5 class="mb-0 mt-2">
          <?php mysqli_data_seek($data, 0); echo mysqli_num_rows($data); ?>
        </h5>
        <small class="text-muted">Total Barang</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-cash-coin text-success fs-4"></i>
        <h5 class="mb-0 mt-2" id="totalHarga">Rp 0</h5>
        <small class="text-muted">Total Harga</small>
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
            $baik = 0;
            while ($r = mysqli_fetch_assoc($data))
              if (strtolower($r['kondisi']) === 'baik') $baik++;
            echo $baik;
          ?>
        </h5>
        <small class="text-muted">Kondisi Baik</small>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
        <h5 class="mb-0 mt-2">
          <?php
            mysqli_data_seek($data, 0);
            $rusak = 0;
            while ($r = mysqli_fetch_assoc($data))
              if (strtolower($r['kondisi']) === 'rusak') $rusak++;
            echo $rusak;
          ?>
        </h5>
        <small class="text-muted">Kondisi Rusak</small>
      </div>
    </div>
  </div>
</div>

<!-- Tabel Detail -->
<div class="table-responsive">
  <table class="table table-hover table-bordered align-middle">
    <thead class="table-light text-center">
      <tr>
        <th width="5%">No</th>
        <th>No Inventaris</th>
        <th>Nama Barang</th>
        <th>Kategori</th>
        <th>Lokasi</th>
        <th width="10%">Kondisi</th>
        <th width="13%">Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php mysqli_data_seek($data, 0); ?>
      <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <?php $grandTotal += $row['harga_pembelian']; ?>
        <tr>
          <td class="text-center"><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['nomor_inventaris'] ?? '-') ?></td>
          <td><?= htmlspecialchars($row['nama_barang']) ?></td>
          <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
          <td><?= htmlspecialchars($row['nama_lokasi'] ?? '-') ?></td>
          <td class="text-center">
            <?php
              $badge = 'secondary';
              switch (strtolower($row['kondisi'])) {
                case 'baik':
                  $badge = 'success';
                  break;
                case 'rusak':
                  $badge = 'danger';
                  break;
              }
            ?>
            <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($row['kondisi']) ?></span>
          </td>
          <td class="text-end">Rp <?= number_format($row['harga_pembelian'], 0, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
    <tfoot class="table-info">
      <tr>
        <th colspan="6" class="text-end">Total Harga Seluruhnya</th>
        <th class="text-end">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
      </tr>
    </tfoot>
  </table>
</div>

<!-- Auto-update total harga di card ringkasan -->
<script>
  document.getElementById('totalHarga').textContent =
    'Rp <?= number_format($grandTotal, 0, ',', '.') ?>';
</script>
