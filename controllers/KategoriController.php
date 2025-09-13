<?php
require_once __DIR__.'/../models/kategori.php';

class KategoriController
{
    public function index()
    {
        $data = getAllKategori();      // panggil fungsi model
        include __DIR__.'/../views/kategori/index.php';
    }

    public function create()
    {
        include __DIR__.'/../views/kategori/tambah.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            insertKategori($_POST);
            header('Location: index.php?page=kategori');
            exit;
        }
    }

    public function edit()
    {
        $id   = $_GET['id'] ?? null;
        $row  = getKategoriById($id);   // tambahkan fungsi di bawah
        if (!$row) {
            header('Location: index.php?page=kategori');
            exit;
        }
        include __DIR__.'/../views/kategori/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'];
            updateKategori($id, $_POST);
            header('Location: index.php?page=kategori');
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        deleteKategori($id);
        header('Location: index.php?page=kategori');
        exit;
    }
}

/* tambahan helper: ambil satu kategori */
function getKategoriById($id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM kategori WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
?>