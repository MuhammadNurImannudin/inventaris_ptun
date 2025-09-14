<?php
/**
 * views/auth/login.php - Fixed Login Page
 * 
 * LOKASI FILE: views/auth/login.php (REPLACE)
 */

// Start session untuk check apakah sudah login
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=dashboard');
    exit;
}

// Include database config
require_once '../../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login – Inventaris PTUN Banjarmasin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Warna tema PTUN */
        :root{
            --ptun-blue:#1e40af;
            --ptun-hover:#1d4ed8;
            --ptun-light:#f8fafc;
        }
        
        html,body{
            height:100%;
            font-family: 'Poppins', sans-serif;
        }
        
        body{
            background: linear-gradient(135deg, #e6f0ff 0%, #1e40af 100%);
            display:flex;
            align-items:center;
            justify-content:center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        
        .login-wrapper{
            width:100%;
            max-width:420px;
        }
        
        .login-card{
            background:#fff;
            border-radius:20px;
            box-shadow:0 20px 60px rgba(30, 64, 175, 0.3);
            padding:3rem 2.5rem;
            text-align:center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-logo{
            width:100px;
            height:100px;
            margin:0 auto 1.5rem;
            background: linear-gradient(135deg, var(--ptun-blue), #3730a3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.3);
        }
        
        .login-title{
            font-size:1.75rem;
            font-weight:700;
            color:var(--ptun-blue);
            margin-bottom:0.5rem;
        }
        
        .login-subtitle{
            font-size:1rem;
            color:#6b7280;
            margin-bottom:2rem;
        }
        
        .form-group{
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-label{
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-control{
            border-radius:12px;
            padding:0.875rem 1rem;
            font-size:1rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .form-control:focus{
            border-color:var(--ptun-blue);
            box-shadow:0 0 0 0.2rem rgba(30, 64, 175, 0.25);
        }
        
        .btn-login{
            background: linear-gradient(135deg, var(--ptun-blue), #3730a3);
            border:none;
            border-radius:12px;
            padding:0.875rem 2rem;
            font-size:1rem;
            font-weight:600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-login:hover{
            background: linear-gradient(135deg, var(--ptun-hover), #1e3a8a);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
        }
        
        .demo-hint{
            font-size:0.875rem;
            color:#6b7280;
            margin-top:1.5rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid var(--ptun-blue);
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 1rem;
        }
        
        .ptun-info {
            background: rgba(30, 64, 175, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--ptun-blue);
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Logo -->
        <div class="login-logo">
            <i class="bi bi-bank" style="font-size: 3rem; color: white;"></i>
        </div>

        <!-- Judul -->
        <div class="login-title">PTUN Banjarmasin</div>
        <div class="login-subtitle">Sistem Inventaris</div>

        <!-- Alert untuk error -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($_SESSION['login_error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <!-- Form -->
        <form action="../../controllers/AuthController.php" method="POST" novalidate>
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="bi bi-person me-2"></i>Username
                </label>
                <input type="text" name="username" id="username" class="form-control" 
                       placeholder="Masukkan username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-2"></i>Password
                </label>
                <input type="password" name="password" id="password" class="form-control" 
                       placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" name="login" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Masuk ke Sistem
            </button>
        </form>

        <!-- Demo Info -->
        <div class="demo-hint">
            <div class="fw-bold mb-2">
                <i class="bi bi-info-circle me-2"></i>Demo Login:
            </div>
            <div><strong>Username:</strong> admin</div>
            <div><strong>Password:</strong> admin</div>
        </div>

        <!-- PTUN Info -->
        <div class="ptun-info">
            <div class="fw-bold mb-1">Pengadilan Tata Usaha Negara Banjarmasin</div>
            <div>Jl. Brig Jend. Hasan Basri No.3, Banjarmasin</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.classList.contains('show')) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150);
            }
        }, 5000);
    });
    
    // Add loading animation to login button
    const form = document.querySelector('form');
    const btn = document.querySelector('.btn-login');
    
    form.addEventListener('submit', function() {
        btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i>Memproses...';
        btn.disabled = true;
    });
});

// Add spin animation
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>

</body>
</html>