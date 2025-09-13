<?php
require_once __DIR__.'/../config/database.php';

function countBarang($where=''){
    global $conn;
    $sql="SELECT COUNT(*) AS total FROM barang $where";
    return mysqli_fetch_assoc(mysqli_query($conn,$sql))['total'];
}
function countTerlambat(){
    global $conn;
    $sql="SELECT COUNT(*) AS total
          FROM peminjaman
          WHERE status='Aktif' AND target_kembali < CURDATE()";
    return mysqli_fetch_assoc(mysqli_query($conn,$sql))['total'];
}
function getAktivitas(){
    global $conn;
    $sql="SELECT b.nama_barang, u.username, p.tanggal_pinjam AS tanggal
          FROM peminjaman p
          JOIN barang b ON b.id=p.barang_id
          JOIN users u ON u.id=p.user_id
          WHERE p.status='Aktif'
          ORDER BY p.tanggal_pinjam DESC
          LIMIT 5";
    return mysqli_query($conn,$sql);
}
function getMaintenance(){
    global $conn;
    $sql="SELECT nama_barang, tanggal_maintenance
          FROM maintenance
          WHERE tanggal_maintenance >= CURDATE()
          ORDER BY tanggal_maintenance ASC
          LIMIT 5";
    return mysqli_query($conn,$sql);
}
?>