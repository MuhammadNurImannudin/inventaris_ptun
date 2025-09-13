<?php
require_once __DIR__ . '/../models/lokasi.php';

class LokasiController
{
    public function index()
    {
        $keyword = $_GET['cari'] ?? '';
        $data = getAllLokasi($keyword);
        include __DIR__ . '/../views/lokasi/index.php';
    }

    public function tambah()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (insertLokasi($_POST)) {
                header('Location: index.php?page=lokasi&msg=success');
            } else {
                header('Location: index.php?page=lokasi&msg=error');
            }
            exit;
        }
        include __DIR__ . '/../views/lokasi/tambah.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (updateLokasi($id, $_POST)) {
                header('Location: index.php?page=lokasi&msg=updated');
            } else {
                header('Location: index.php?page=lokasi&msg=error');
            }
            exit;
        }
        $lokasi = getLokasiById($id);
        include __DIR__ . '/../views/lokasi/edit.php';
    }

    public function hapus()
    {
        $id = $_GET['id'] ?? null;
        deleteLokasi($id);
        header('Location: index.php?page=lokasi&msg=deleted');
        exit;
    }
}
