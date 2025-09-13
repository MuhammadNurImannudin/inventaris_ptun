<?php
/* Aktifkan session jika belum */
if (session_status() === PHP_SESSION_NONE) session_start();

/* Ambil nama file saat ini untuk menandai menu aktif */
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inventaris PTUN Banjarmasin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-gradient-primary border-end" id="sidebar-wrapper" style="min-width:240px;">
        <div class="sidebar-heading text-center py-4 text-white">
            <img src="../../assets/img/logo_ptun.png" alt="Logo PTUN" width="90" class="mb-2">
            <div class="fw-bold">PTUN Banjarmasin</div>
        </div>
        <div class="list-group list-group-flush bg-transparent">
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'dashboard' ? 'active' : '' ?>"
               href="../dashboard/index.php">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'barang' ? 'active' : '' ?>"
               href="../barang/index.php">
                <i class="bi bi-box-seam me-2"></i>Barang
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'kategori' ? 'active' : '' ?>"
               href="../kategori/index.php">
                <i class="bi bi-tags me-2"></i>Kategori
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'lokasi' ? 'active' : '' ?>"
               href="../lokasi/index.php">
                <i class="bi bi-geo-alt me-2"></i>Lokasi
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'peminjaman' ? 'active' : '' ?>"
               href="../peminjaman/index.php">
                <i class="bi bi-arrow-down-square me-2"></i>Peminjaman
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'pengembalian' ? 'active' : '' ?>"
               href="../pengembalian/index.php">
                <i class="bi bi-arrow-up-square me-2"></i>Pengembalian
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'maintenance' ? 'active' : '' ?>"
               href="../maintenance/index.php">
                <i class="bi bi-wrench me-2"></i>Maintenance
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'users' ? 'active' : '' ?>"
               href="../users/index.php">
                <i class="bi bi-people me-2"></i>Users
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'laporan' ? 'active' : '' ?>"
               href="../laporan/index.php">
                <i class="bi bi-file-earmark-text me-2"></i>Laporan
            </a>
            <a class="list-group-item list-group-item-action bg-transparent text-white <?= $currentDir == 'pengaturan' ? 'active' : '' ?>"
               href="../pengaturan/index.php">
                <i class="bi bi-gear me-2"></i>Pengaturan
            </a>
        </div>
    </div>

    <!-- Page content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom px-3">
            <button class="btn btn-outline-primary btn-sm" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3">Administrator PTUN</span>
                <a href="../../controllers/AuthController.php?logout=1" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
            </div>
        </nav>
        <div class="container-fluid p-4">