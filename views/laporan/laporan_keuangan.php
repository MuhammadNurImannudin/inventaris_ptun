<?php
$data = getLaporanKeuangan($_GET['start'] ?? '', $_GET['end'] ?? '');
?>
<div class="d-flex justify-content-between mb-3">
  <h6>💰 Laporan Keuangan</h6>
  <a href="print.php?<?= http_build_query($_GET) ?>" class="btn btn-danger">
    <i class="bi bi-download"></i> Download PDF
  </a>
</div>
<table class="table table-bordered table-hover align-middle">
  <thead class="table-light">
    <tr><th>No</th><th>Barang</th><th>Harga</th><th>Total Aset</th></tr>
  </thead>
  <tbody>
  <?php $no = 1; $total = 0; while ($row = mysqli_fetch_assoc($data)): $total += $row['harga_pembelian']; ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['nama_barang'] ?></td>
      <td>Rp <?= number_format($row['harga_pembelian']) ?></td>
      <td>Rp <?= number_format($row['harga_pembelian']) ?></td>
    </tr>
  <?php endwhile; ?>
    <tr class="table-info">
      <th colspan="2">Total Aset Keseluruhan</th>
      <th>Rp <?= number_format($total) ?></th>
    </tr>
  </tbody>
</table>