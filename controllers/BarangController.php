<?php
/**
 * BarangController.php
 * Controller untuk manajemen barang - PTUN Banjarmasin
 * 
 * LOKASI FILE: controllers/BarangController.php (REPLACE FILE INI)
 */

require_once __DIR__ . '/../models/Barang.php';
require_once __DIR__ . '/../config/database.php';

class BarangController {
    
    public function index() {
        // Ambil data filter & search dari GET
        $filters = [
            'kategori_id' => $_GET['kategori_id'] ?? '',
            'lokasi_id'   => $_GET['lokasi_id'] ?? '',
            'status'      => $_GET['status'] ?? '',
            'search'      => $_GET['search'] ?? ''
        ];

        // Ambil data barang sesuai filter
        $barang = getAllBarang($filters);

        // Ambil data kategori & lokasi untuk dropdown
        require_once __DIR__ . '/../models/Kategori.php';
        require_once __DIR__ . '/../models/Lokasi.php';
        $kategori = getAllKategori();
        $lokasi   = getAllLokasi();

        include __DIR__ . '/../views/barang/index.php';
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            require_once __DIR__ . '/../models/Kategori.php';
            require_once __DIR__ . '/../models/Lokasi.php';
            $kategori = getAllKategori();
            $lokasi   = getAllLokasi();
            include __DIR__ . '/../views/barang/tambah.php';
        }
    }

    public function store() {
        try {
            // Handle file upload
            $foto = null;
            if (isset($_FILES['foto']) && $_FILES['foto']['name']) {
                $foto = $this->handleFileUpload($_FILES['foto']);
            }

            // Insert barang
            if (insertBarang($_POST, $foto)) {
                $_SESSION['success'] = 'Barang berhasil ditambahkan!';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan barang!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'views/barang/index.php');
        exit;
    }

    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update();
        } else {
            $barang = getBarangById($id);
            if (!$barang) {
                header('Location: ' . BASE_URL . 'views/barang/index.php');
                exit;
            }

            require_once __DIR__ . '/../models/Kategori.php';
            require_once __DIR__ . '/../models/Lokasi.php';
            $kategori = getAllKategori();
            $lokasi   = getAllLokasi();
            include __DIR__ . '/../views/barang/edit.php';
        }
    }

    public function update() {
        try {
            $id = (int)$_POST['id'];
            $foto = null;
            
            // Handle new file upload
            if (isset($_FILES['foto']) && $_FILES['foto']['name']) {
                $foto = $this->handleFileUpload($_FILES['foto']);
            }

            if (updateBarang($id, $_POST, $foto)) {
                $_SESSION['success'] = 'Barang berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui barang!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'views/barang/index.php');
        exit;
    }

    public function hapus() {
        try {
            $id = (int)($_GET['hapus'] ?? 0);
            
            if (deleteBarang($id)) {
                $_SESSION['success'] = 'Barang berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus barang!';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'views/barang/index.php');
        exit;
    }

    private function handleFileUpload($file) {
        $uploadDir = UPLOAD_PATH . 'barang/';
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validate file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = MAX_UPLOAD_SIZE; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Tipe file tidak diizinkan. Gunakan JPG, PNG, atau GIF.');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('Ukuran file terlalu besar. Maksimal 5MB.');
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        } else {
            throw new Exception('Gagal mengupload file.');
        }
    }
}

// Handle direct calls to this controller
if (basename($_SERVER['PHP_SELF']) === 'BarangController.php') {
    session_start();
    
    $controller = new BarangController();
    
    if (isset($_POST['tambah'])) {
        $controller->store();
    } elseif (isset($_POST['edit'])) {
        $controller->update();
    } elseif (isset($_GET['hapus'])) {
        $controller->hapus();
    } else {
        $controller->index();
    }
}
?>