<?php
require_once '../models/Maintenance.php';
require_once '../models/Barang.php';

if(isset($_POST['tambah'])){
    insertMaintenance($_POST);
    header('Location: ../views/maintenance/index.php');
}
if(isset($_POST['edit'])){
    updateMaintenance($_POST['id'],$_POST);
    header('Location: ../views/maintenance/index.php');
}
if(isset($_GET['hapus'])){
    deleteMaintenance($_GET['hapus']);
    header('Location: ../views/maintenance/index.php');
}
?>