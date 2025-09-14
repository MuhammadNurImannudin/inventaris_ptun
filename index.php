<?php
/**
 * index.php - Main Entry Point
 * Sistem Inventaris PTUN Banjarmasin
 * 
 * LOKASI FILE: index.php (ROOT FOLDER - REPLACE)
 */

session_start();

// Security check - redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: views/auth/login.php');
    exit;
}

// Load configuration and database
require_once __DIR__ . '/config/database.php';

// Auto-load models
require_once __DIR__ . '/models/Barang.php';
require_once __DIR__ . '/models/Kategori.php';
require_once __DIR__ . '/models/Lokasi.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Dashboard.php';
require_once __DIR__ . '/models/Laporan.php';
require_once __DIR__ . '/models/Maintenance.php';

// Get parameters
$page = $_GET['page'] ?? 'dashboard';
$aksi = $_GET['aksi'] ?? 'index';

// Create uploads directory if not exists
$uploadDirs = ['uploads/barang', 'uploads/users'];
foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Main routing switch
switch ($page) {
    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        include __DIR__ . '/views/dashboard/index.php';
        break;

    case 'kategori':
        require_once __DIR__ . '/controllers/KategoriController.php';
        $controller = new KategoriController();
        switch ($aksi) {
            case 'tambah':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->store();
                } else {
                    $controller->create();
                }
                break;
            case 'edit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->update();
                } else {
                    $controller->edit();
                }
                break;
            case 'hapus':
                $controller->delete();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    case 'lokasi':
        require_once __DIR__ . '/controllers/LokasiController.php';
        $controller = new LokasiController();
        switch ($aksi) {
            case 'tambah':
                $controller->tambah();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'hapus':
                $controller->hapus();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    case 'barang':
        include __DIR__ . '/views/barang/index.php';
        break;

    case 'maintenance':
        include __DIR__ . '/views/maintenance/index.php';
        break;

    case 'users':
        include __DIR__ . '/views/users/index.php';
        break;

    case 'peminjaman':
        include __DIR__ . '/views/peminjaman/index.php';
        break;

    case 'pengembalian':
        include __DIR__ . '/views/pengembalian/index.php';
        break;

    case 'laporan':
        $jenis = $_GET['jenis'] ?? 'inventaris';
        include __DIR__ . '/views/laporan/index.php';
        break;

    default:
        require_once __DIR__ . '/controllers/DashboardController.php';
        include __DIR__ . '/views/dashboard/index.php';
        break;
}
?>