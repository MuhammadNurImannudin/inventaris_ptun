<?php
session_start();

$page = $_GET['page'] ?? 'dashboard';
$aksi = $_GET['aksi'] ?? 'index';

switch ($page) {
    case 'dashboard':
        include __DIR__ . '/views/dashboard/index.php';
        break;

    case 'kategori':
        require_once __DIR__ . '/controllers/KategoriController.php';
        $controller = new KategoriController();
        switch ($aksi) {
            case 'index':  $controller->index();  break;
            case 'tambah': $controller->tambah(); break;
            case 'edit':   $controller->edit();   break;
            case 'hapus':  $controller->hapus();  break;
        }
        break;

    case 'lokasi':
        require_once __DIR__ . '/controllers/LokasiController.php';
        $controller = new LokasiController();
        switch ($aksi) {
            case 'index':  $controller->index();  break;
            case 'tambah': $controller->tambah(); break;
            case 'edit':   $controller->edit();   break;
            case 'hapus':  $controller->hapus();  break;
        }
        break;

    case 'barang':
        require_once __DIR__ . '/controllers/BarangController.php';
        $controller = new BarangController();
        switch ($aksi) {
            case 'index':  $controller->index();   break;
            case 'tambah': $controller->tambah();  break;
            case 'edit':   $controller->edit();    break;
            case 'hapus':  $controller->hapus();   break;
        }
        break;

    default:
        echo "Halaman tidak ditemukan.";
}
?>