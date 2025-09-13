<?php
session_start();
require_once __DIR__ . '/../config/database.php';

/* ---------- LOGOUT (diletakkan di paling atas) ---------- */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../views/auth/login.php");
    exit;
}

/* ---------- LOGIN ---------- */
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    /* validasi kosong */
    if (!$username || !$password) {
        echo "<script>alert('Username & password harus diisi.'); window.location='../views/auth/login.php';</script>";
        exit;
    }

    /* prepared statement - anti SQL Injection */
    $stmt = $conn->prepare("SELECT id, username, role, status FROM users WHERE username = ? AND password = ? AND status = 'Aktif' LIMIT 1");
    $stmt->bind_param("ss", $username, md5($password));
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        /* set session */
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['role']      = $user['role'];

        /* update last login */
        $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $update->bind_param("i", $user['id']);
        $update->execute();

        header("Location: ../views/dashboard/index.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah.'); window.location='../views/auth/login.php';</script>";
        exit;
    }
}
?>