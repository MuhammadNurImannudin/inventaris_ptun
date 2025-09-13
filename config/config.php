<?php
/**
 * index.php
 * Router sederhana untuk semua halaman
 * PTUN Banjarmasin
 */

session_start();

/* --------------------------------------------------
   1. Konfigurasi & autoload model / controller
   -------------------------------------------------- */
require_once __DIR__ . '/config/database.php';

/* Daftar model & controller yang digunakan */
require_once __DIR__ . '/models/Laporan.php';
require_once __DIR__ . '/models/Kategori.php';
require_once __DIR__ . '/models/Lokasi.php';
require_once __DIR__ . '/controllers/LokasiController.php';
require_once __DIR__ . '/controllers/BarangController.php';

/* --------------------------------------------------
   2. Ambil parameter halaman & aksi
   -------------------------------------------------- */
$page = $_GET['page'] ?? 'dashboard';
$aksi = $_GET['aksi'] ?? 'index';

/* --------------------------------------------------
   3. Routing ke halaman laporan
   -------------------------------------------------- */
switch ($page) {
    case 'lokasi':
        $lokasiCtrl = new LokasiController();
        switch ($aksi) {
            case 'tambah':
                $lokasiCtrl->tambah();
                break;
            case 'edit':
                $lokasiCtrl->edit();
                break;
            case 'hapus':
                $lokasiCtrl->hapus();
                break;
            default:
                $lokasiCtrl->index();
        }
        break;

    case 'laporan':
        /* Parameter untuk laporan */
        $start    = $_GET['start'] ?? date('Y-m-01');
        $end      = $_GET['end']   ?? date('Y-m-t');
        $kategori = $_GET['kategori'] ?? '';
        $lokasi   = $_GET['lokasi'] ?? '';
        $jenis    = $_GET['jenis'] ?? 'inventaris';

        include 'views/laporan/index.php';
        break;

    /* Tambahkan page lain jika diperlukan */
    case 'dashboard':
    default:
        include 'views/home/index.php';
        break;
}
?>