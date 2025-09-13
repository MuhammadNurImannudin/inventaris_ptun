<?php
require_once __DIR__.'/../config/database.php';

function getAllMaintenance(){
    global $conn;
    $sql = "SELECT m.*, b.nama_barang
            FROM maintenance m
            JOIN barang b ON b.id=m.barang_id
            ORDER BY m.tanggal_maintenance DESC";
    return mysqli_query($conn,$sql);
}

function getMaintenanceById($id){
    global $conn;
    $stmt=$conn->prepare("SELECT * FROM maintenance WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function insertMaintenance($data){
    global $conn;
    $stmt=$conn->prepare("INSERT INTO maintenance(barang_id,tanggal_maintenance,jenis,biaya,keterangan,status)
                          VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("ississ",
        $data['barang_id'],
        $data['tanggal_maintenance'],
        $data['jenis'],
        $data['biaya'],
        $data['keterangan'],
        $data['status']
    );
    return $stmt->execute();
}

function updateMaintenance($id,$data){
    global $conn;
    $stmt=$conn->prepare("UPDATE maintenance SET
                          barang_id=?, tanggal_maintenance=?, jenis=?, biaya=?, keterangan=?, status=?
                          WHERE id=?");
    $stmt->bind_param("ississi",
        $data['barang_id'],
        $data['tanggal_maintenance'],
        $data['jenis'],
        $data['biaya'],
        $data['keterangan'],
        $data['status'],
        $id
    );
    return $stmt->execute();
}

function deleteMaintenance($id){
    global $conn;
    $stmt=$conn->prepare("DELETE FROM maintenance WHERE id=?");
    $stmt->bind_param("i",$id);
    return $stmt->execute();
}
?>