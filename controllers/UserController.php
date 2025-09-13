<?php
require_once '../models/User.php';

if(isset($_POST['tambah'])){
    insertUser($_POST);
    header('Location: ../views/users/index.php');
}
if(isset($_POST['edit'])){
    updateUser($_POST['id'],$_POST,$_POST['password'] ?? null);
    header('Location: ../views/users/index.php');
}
if(isset($_GET['hapus'])){
    deleteUser($_GET['hapus']);
    header('Location: ../views/users/index.php');
}
?>