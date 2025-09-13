<?php
require_once __DIR__.'/../models/Barang.php';

if(isset($_POST['tambah'])){
    $foto=null;
    if($_FILES['foto']['name']){
        $tmp=$_FILES['foto']['tmp_name'];
        $fname=time().'_'.$_FILES['foto']['name'];
        $path=__DIR__.'/../uploads/barang/'.$fname;
        move_uploaded_file($tmp,$path);
        $foto=$fname;
    }
    insertBarang($_POST,$foto);
    header('Location: ../views/barang/index.php');
}
if (isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $tmp  = $_FILES['foto']['tmp_name'];
        $nama = time() . '_' . $_FILES['foto']['name'];
        move_uploaded_file($tmp, __DIR__ . '/../uploads/barang/' . $nama);
        $foto = $nama;
    }
    updateBarang($id, $_POST, $foto);
    header('Location: ../views/barang/index.php');
    exit;
}
    updateBarang($id,$_POST,$foto);
    header('Location: ../views/barang/index.php');
if(isset($_GET['hapus'])){
    deleteBarang($_GET['hapus']);
    header('Location: ../views/barang/index.php');
}
?>