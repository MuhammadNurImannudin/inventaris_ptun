<?php
$data = getLaporanPengembalian($_GET['start'] ?? '', $_GET['end'] ?? '');
?>
<div class="d-flex justify-content-between mb-3">
  <h6>📤 Laporan Pengembalian</h6>
  <a href="print.php?<?= http_build_query($_GET) ?>" class="btn btn-success">
    <i class="bi bi-download"></i> Download PDF
  </a>
</div>
<table class="table table-bordered table-hover align-middle">
  <thead class="table-light">
    <tr><th>No</th><th>Barang</th><th>Tgl Kembali</th><th>Kondisi</th><th>Catatan</th></tr>
  </thead>
  <tbody>
  <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['nama_barang'] ?></td>
      <td><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></td>
      <td><?= $row['kondisi'] ?></td>
      <td><?= $row['catatan'] ?? '-' ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>