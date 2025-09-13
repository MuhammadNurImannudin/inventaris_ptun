<?php
$data = getLaporanPeminjaman($_GET['start'] ?? '', $_GET['end'] ?? '');
?>
<div class="d-flex justify-content-between mb-3">
  <h6>📥 Laporan Peminjaman</h6>
  <a href="print.php?<?= http_build_query($_GET) ?>" class="btn btn-warning">
    <i class="bi bi-download"></i> Download PDF
  </a>
</div>
<table class="table table-bordered table-hover align-middle">
  <thead class="table-light">
    <tr><th>No</th><th>Barang</th><th>Peminjam</th><th>Tanggal Pinjam</th><th>Status</th></tr>
  </thead>
  <tbody>
  <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $row['nama_barang'] ?></td>
      <td><?= $row['username'] ?></td>
      <td><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
      <td><?= $row['status'] ?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>