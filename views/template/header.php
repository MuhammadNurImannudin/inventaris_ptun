<?php
/**
 * views/template/header.php - Professional Header Template
 * 
 * LOKASI FILE: views/template/header.php (REPLACE)
 */

/* Aktifkan session jika belum */
if (session_status() === PHP_SESSION_NONE) session_start();

/* Security check */
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('views/auth/login.php'));
    exit;
}

/* Ambil informasi halaman saat ini */
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));

/* Get user info */
$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'Staff';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Sistem Inventaris PTUN Banjarmasin</title>
    
    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts - Poppins for modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* PTUN Brand Colors - Professional Government Theme */
            --ptun-primary: #1e40af;      /* Deep Blue */
            --ptun-secondary: #0f172a;    /* Dark Blue */
            --ptun-accent: #059669;       /* Government Green */
            --ptun-gold: #f59e0b;         /* Gold accent */
            --ptun-light: #f8fafc;        /* Light background */
            --ptun-gray: #64748b;         /* Text gray */
            --ptun-dark: #1e293b;         /* Dark text */
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #1e40af 0%, #3730a3 50%, #1e3a8a 100%);
            --gradient-accent: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--ptun-light);
            color: var(--ptun-dark);
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styling - Government Professional Look */
        #sidebar-wrapper {
            min-height: 100vh;
            width: 280px;
            background: var(--gradient-primary);
            box-shadow: 4px 0 20px rgba(30, 64, 175, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        #sidebar-wrapper.toggled {
            margin-left: -280px;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-title {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            font-weight: 400;
        }

        /* Navigation Menu */
        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-section {
            margin-bottom: 1.5rem;
        }

        .menu-section-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 1.5rem 0.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.85) !important;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            position: relative;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            padding-left: 2rem;
        }

        .nav-link.active {
            background: var(--gradient-accent);
            color: white !important;
            font-weight: 600;
            box-shadow: inset 4px 0 0 var(--ptun-gold);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--ptun-gold);
        }

        /* Main Content Area */
        #page-content-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            background: var(--ptun-light);
            transition: margin-left 0.3s ease;
        }

        #page-content-wrapper.toggled {
            margin-left: 0;
        }

        /* Top Navigation Bar */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--ptun-primary);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--ptun-primary);
            font-size: 1.25rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: var(--ptun-dark);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--ptun-gray);
        }

        /* Main Content */
        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        /* Cards and Components */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--ptun-primary);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem;
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
        }

        .btn-success {
            background: var(--gradient-accent);
            border: none;
        }

        /* Tables */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }

        .table-light {
            background: var(--ptun-light);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -280px;
            }
            
            #sidebar-wrapper.show {
                margin-left: 0;
            }
            
            #page-content-wrapper {
                margin-left: 0;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .top-navbar {
                padding: 1rem;
            }
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--ptun-primary);
            margin: 0;
        }

        .page-subtitle {
            color: var(--ptun-gray);
            font-size: 1rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar-wrapper">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="bi bi-bank text-primary" style="font-size: 2rem;"></i>
        </div>
        <div class="sidebar-title">PTUN BANJARMASIN</div>
        <div class="sidebar-subtitle">Sistem Inventaris</div>
    </div>

    <div class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-section-title">Menu Utama</div>
            <a class="nav-link <?= $currentDir == 'dashboard' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=dashboard">
                <i class="bi bi-speedometer2"></i>Dashboard
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Master Data</div>
            <a class="nav-link <?= $currentDir == 'barang' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=barang">
                <i class="bi bi-box-seam"></i>Data Barang
            </a>
            <a class="nav-link <?= $currentDir == 'kategori' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=kategori">
                <i class="bi bi-tags"></i>Kategori
            </a>
            <a class="nav-link <?= $currentDir == 'lokasi' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=lokasi">
                <i class="bi bi-geo-alt"></i>Lokasi
            </a>
            <a class="nav-link <?= $currentDir == 'users' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=users">
                <i class="bi bi-people"></i>Pengguna
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Transaksi</div>
            <a class="nav-link <?= $currentDir == 'peminjaman' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=peminjaman">
                <i class="bi bi-arrow-down-square"></i>Peminjaman
            </a>
            <a class="nav-link <?= $currentDir == 'pengembalian' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=pengembalian">
                <i class="bi bi-arrow-up-square"></i>Pengembalian
            </a>
            <a class="nav-link <?= $currentDir == 'maintenance' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=maintenance">
                <i class="bi bi-wrench"></i>Maintenance
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Laporan</div>
            <a class="nav-link <?= $currentDir == 'laporan' ? 'active' : '' ?>" href="<?= base_url() ?>index.php?page=laporan">
                <i class="bi bi-file-earmark-text"></i>Semua Laporan
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="page-content-wrapper">
    <!-- Top Navigation -->
    <nav class="top-navbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-primary btn-sm me-3" id="menu-toggle">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand">Sistem Inventaris PTUN</span>
        </div>
        
        <div class="user-info">
            <div class="user-details">
                <div class="user-name"><?= htmlspecialchars($username) ?></div>
                <div class="user-role"><?= htmlspecialchars($role) ?></div>
            </div>
            <div class="user-avatar">
                <?= strtoupper(substr($username, 0, 2)) ?>
            </div>
            <a href="<?= base_url() ?>controllers/AuthController.php?logout=1" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content">