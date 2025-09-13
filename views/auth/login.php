<?php include_once '../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login – Inventaris PTUN Banjarmasin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Warna tema PTUN */
        :root{
            --ptun-blue:#0056b3;
            --ptun-hover:#004494;
        }
        html,body{height:100%;}
        body{
            background:linear-gradient(135deg,#e6f0ff 0%, #0f40c7ff 100%);
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif;
        }
        .login-wrapper{
            width:100%;
            max-width:380px;
            padding:0 15px;
        }
        .login-card{
            background:#fff;
            border-radius:12px;
            box-shadow:0 8px 30px rgba(0, 0, 0, 0.08);
            padding:45px 40px 50px;
            text-align:center;
        }
        .login-logo{
            width:110px;
            margin:0 auto 18px;
        }
        .login-title{
            font-size:1.45rem;
            font-weight:700;
            color:var(--ptun-blue);
            margin-bottom:8px;
        }
        .login-subtitle{
            font-size:.95rem;
            color:#6c757d;
            margin-bottom:30px;
        }
        .form-control{
            border-radius:8px;
            padding:12px 15px;
            font-size:1rem;
        }
        .form-control:focus{
            border-color:var(--ptun-blue);
            box-shadow:0 0 0 .2rem rgba(10, 54, 100, 1);
        }
        .btn-primary{
            background-color:var(--ptun-blue);
            border:none;
            border-radius:8px;
            padding:12px;
            font-size:1rem;
            font-weight:600;
            transition:background-color .2s;
        }
        .btn-primary:hover{
            background-color:var(--ptun-hover);
        }
        .demo-hint{
            font-size:.85rem;
            color:#6c757d;
            margin-top:10px;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Logo -->
        <img src="../../assets/img/logo_ptun.png" alt="Logo PTUN" class="login-logo">

        <!-- Judul -->
        <div class="login-title">PTUN Banjarmasin</div>
        <div class="login-subtitle">Sistem Inventaris</div>

        <!-- Form -->
        <form action="../../controllers/AuthController.php" method="POST" novalidate>
            <div class="mb-3 text-start">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-4 text-start">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Masuk</button>
            <div class="demo-hint">Demo: admin / admin</div>
        </form>
    </div>
</div>

</body>
</html>