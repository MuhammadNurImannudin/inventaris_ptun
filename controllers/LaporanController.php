<?php
require_once '../models/Laporan.php';
require_once '../models/Kategori.php';
require_once '../models/Lokasi.php';

$start  = $_GET['start'] ?? '';
$end    = $_GET['end'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';

$kategoriList = getAllKategori();
$lokasiList   = getAllLokasi();

$jenis = $_GET['jenis'] ?? 'inventaris';
switch($jenis){
    case 'inventaris' : $data = getLaporanInventaris($start,$end,$kategori,$lokasi); break;
    case 'peminjaman' : $data = getLaporanPeminjaman($start,$end); break;
    case 'pengembalian': $data = getLaporanPengembalian($start,$end); break;
    case 'maintenance': $data = getLaporanMaintenance($start,$end); break;
    case 'keuangan'   : $data = getLaporanKeuangan($start,$end); break;
    default: $data = [];
}
?>