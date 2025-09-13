<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location: ../views/auth/login.php');
    exit;
}
require_once __DIR__.'/../models/Dashboard.php';

$total      = countBarang();
$tersedia   = countBarang("WHERE status='Tersedia'");
$dipinjam   = countBarang("WHERE status='Dipinjam'");
$maintenance= countBarang("WHERE status='Maintenance'");
$terlambat  = countTerlambat();
$aktivitas  = getAktivitas();
$maintenance_list = getMaintenance();
?>