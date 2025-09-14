<?php
/**
 * models/Peminjaman.php
 * Model untuk manajemen peminjaman barang - PTUN Banjarmasin
 * 
 * LOKASI FILE: models/Peminjaman.php (BUAT FILE BARU)
 */

require_once __DIR__ . '/../config/database.php';

/* ========== PEMINJAMAN FUNCTIONS ========== */

function getAllPeminjaman($filters = []) {
    global $conn;
    
    $where = [];
    $params = [];
    $types = '';
    
    $sql = "SELECT p.*, b.nama_barang, b.nomor_inventaris, u.username, u.email
            FROM peminjaman p 
            LEFT JOIN barang b ON b.id = p.barang_id
            LEFT JOIN users u ON u.id = p.user_id
            WHERE 1=1";
    
    // Filter by status
    if (!empty($filters['status'])) {
        $where[] = "p.status = ?";
        $params[] = $filters['status'];
        $types .= 's';
    }
    
    // Filter by date range
    if (!empty($filters['start_date'])) {
        $where[] = "p.tanggal_pinjam >= ?";
        $params[] = $filters['start_date'];
        $types .= 's';
    }
    
    if (!empty($filters['end_date'])) {
        $where[] = "p.tanggal_pinjam <= ?";
        $params[] = $filters['end_date'];
        $types .= 's';
    }
    
    // Search functionality
    if (!empty($filters['search'])) {
        $where[] = "(b.nama_barang LIKE ? OR u.username LIKE ? OR b.nomor_inventaris LIKE ?)";
        $search = "%" . $filters['search'] . "%";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
        $types .= 'sss';
    }
    
    if ($where) {
        $sql .= " AND " . implode(" AND ", $where);
    }
    
    $sql .= " ORDER BY p.id DESC";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}

function getPeminjamanById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT p.*, b.nama_barang, u.username 
                           FROM peminjaman p 
                           LEFT JOIN barang b ON b.id = p.barang_id
                           LEFT JOIN users u ON u.id = p.user_id
                           WHERE p.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function insertPeminjaman($data) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO peminjaman (barang_id, user_id, tanggal_pinjam, target_kembali, status, keterangan)
                           VALUES (?, ?, ?, ?, ?, ?)");
    
    $barang_id = (int)$data['barang_id'];
    $user_id = (int)$data['user_id'];
    $tanggal_pinjam = $data['tanggal_pinjam'];
    $target_kembali = $data['target_kembali'];
    $status = $data['status'] ?? 'Aktif';
    $keterangan = $data['keterangan'] ?? '';
    
    $stmt->bind_param("iissss", $barang_id, $user_id, $tanggal_pinjam, $target_kembali, $status, $keterangan);
    
    if ($stmt->execute()) {
        // Update status barang menjadi 'Dipinjam'
        updateBarangStatus($barang_id, 'Dipinjam');
        return true;
    }
    return false;
}

function updatePeminjaman($id, $data) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE peminjaman 
                           SET barang_id=?, user_id=?, tanggal_pinjam=?, target_kembali=?, status=?, keterangan=?
                           WHERE id=?");
    
    $barang_id = (int)$data['barang_id'];
    $user_id = (int)$data['user_id'];
    $tanggal_pinjam = $data['tanggal_pinjam'];
    $target_kembali = $data['target_kembali'];
    $status = $data['status'];
    $keterangan = $data['keterangan'] ?? '';
    
    $stmt->bind_param("iissssi", $barang_id, $user_id, $tanggal_pinjam, $target_kembali, $status, $keterangan, $id);
    
    return $stmt->execute();
}

function deletePeminjaman($id) {
    global $conn;
    
    // Get barang_id first
    $peminjaman = getPeminjamanById($id);
    
    $stmt = $conn->prepare("DELETE FROM peminjaman WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Update status barang kembali ke 'Tersedia'
        if ($peminjaman && $peminjaman['status'] === 'Aktif') {
            updateBarangStatus($peminjaman['barang_id'], 'Tersedia');
        }
        return true;
    }
    return false;
}

function updateBarangStatus($barang_id, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE barang SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $barang_id);
    return $stmt->execute();
}

function getOverduePeminjaman() {
    global $conn;
    $sql = "SELECT p.*, b.nama_barang, u.username 
            FROM peminjaman p 
            LEFT JOIN barang b ON b.id = p.barang_id
            LEFT JOIN users u ON u.id = p.user_id
            WHERE p.status = 'Aktif' AND p.target_kembali < CURDATE()
            ORDER BY p.target_kembali ASC";
    
    $result = $conn->query($sql);
    return $result ? $result : false;
}

?>

---

<?php
/**
 * models/Pengembalian.php  
 * Model untuk manajemen pengembalian barang - PTUN Banjarmasin
 * 
 * LOKASI FILE: models/Pengembalian.php (BUAT FILE BARU)
 */

require_once __DIR__ . '/../config/database.php';

/* ========== PENGEMBALIAN FUNCTIONS ========== */

function getAllPengembalian($filters = []) {
    global $conn;
    
    $where = [];
    $params = [];
    $types = '';
    
    $sql = "SELECT pg.*, p.tanggal_pinjam, p.target_kembali, 
                   b.nama_barang, b.nomor_inventaris, u.username
            FROM pengembalian pg 
            LEFT JOIN peminjaman p ON p.id = pg.peminjaman_id
            LEFT JOIN barang b ON b.id = p.barang_id
            LEFT JOIN users u ON u.id = p.user_id
            WHERE 1=1";
    
    // Filter by date range
    if (!empty($filters['start_date'])) {
        $where[] = "pg.tanggal_kembali >= ?";
        $params[] = $filters['start_date'];
        $types .= 's';
    }
    
    if (!empty($filters['end_date'])) {
        $where[] = "pg.tanggal_kembali <= ?";
        $params[] = $filters['end_date'];
        $types .= 's';
    }
    
    // Filter by condition
    if (!empty($filters['kondisi'])) {
        $where[] = "pg.kondisi = ?";
        $params[] = $filters['kondisi'];
        $types .= 's';
    }
    
    // Search functionality
    if (!empty($filters['search'])) {
        $where[] = "(b.nama_barang LIKE ? OR u.username LIKE ?)";
        $search = "%" . $filters['search'] . "%";
        $params[] = $search;
        $params[] = $search;
        $types .= 'ss';
    }
    
    if ($where) {
        $sql .= " AND " . implode(" AND ", $where);
    }
    
    $sql .= " ORDER BY pg.id DESC";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}

function getPengembalianById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT pg.*, p.tanggal_pinjam, p.target_kembali, 
                           b.nama_barang, u.username
                           FROM pengembalian pg 
                           LEFT JOIN peminjaman p ON p.id = pg.peminjaman_id
                           LEFT JOIN barang b ON b.id = p.barang_id
                           LEFT JOIN users u ON u.id = p.user_id
                           WHERE pg.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function insertPengembalian($data) {
    global $conn;
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Insert pengembalian record
        $stmt = $conn->prepare("INSERT INTO pengembalian (peminjaman_id, tanggal_kembali, kondisi, catatan)
                               VALUES (?, ?, ?, ?)");
        
        $peminjaman_id = (int)$data['peminjaman_id'];
        $tanggal_kembali = $data['tanggal_kembali'];
        $kondisi = $data['kondisi'];
        $catatan = $data['catatan'] ?? '';
        
        $stmt->bind_param("isss", $peminjaman_id, $tanggal_kembali, $kondisi, $catatan);
        $stmt->execute();
        
        // Update status peminjaman
        $stmt2 = $conn->prepare("UPDATE peminjaman SET status = 'Kembali', tanggal_kembali = ? WHERE id = ?");
        $stmt2->bind_param("si", $tanggal_kembali, $peminjaman_id);
        $stmt2->execute();
        
        // Get barang_id from peminjaman
        $stmt3 = $conn->prepare("SELECT barang_id FROM peminjaman WHERE id = ?");
        $stmt3->bind_param("i", $peminjaman_id);
        $stmt3->execute();
        $result = $stmt3->get_result();
        $peminjaman = $result->fetch_assoc();
        
        // Update barang status and condition
        if ($peminjaman) {
            $new_status = ($kondisi === 'Rusak') ? 'Maintenance' : 'Tersedia';
            $stmt4 = $conn->prepare("UPDATE barang SET status = ?, kondisi = ? WHERE id = ?");
            $stmt4->bind_param("ssi", $new_status, $kondisi, $peminjaman['barang_id']);
            $stmt4->execute();
        }
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function updatePengembalian($id, $data) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE pengembalian 
                           SET tanggal_kembali=?, kondisi=?, catatan=?
                           WHERE id=?");
    
    $tanggal_kembali = $data['tanggal_kembali'];
    $kondisi = $data['kondisi'];
    $catatan = $data['catatan'] ?? '';
    
    $stmt->bind_param("sssi", $tanggal_kembali, $kondisi, $catatan, $id);
    
    return $stmt->execute();
}

function deletePengembalian($id) {
    global $conn;
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Get pengembalian data
        $pengembalian = getPengembalianById($id);
        
        // Delete pengembalian record
        $stmt = $conn->prepare("DELETE FROM pengembalian WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Revert peminjaman status back to 'Aktif'
        if ($pengembalian) {
            $stmt2 = $conn->prepare("UPDATE peminjaman SET status = 'Aktif', tanggal_kembali = NULL WHERE id = ?");
            $stmt2->bind_param("i", $pengembalian['peminjaman_id']);
            $stmt2->execute();
            
            // Get barang_id and update status back to 'Dipinjam'
            $stmt3 = $conn->prepare("SELECT barang_id FROM peminjaman WHERE id = ?");
            $stmt3->bind_param("i", $pengembalian['peminjaman_id']);
            $stmt3->execute();
            $result = $stmt3->get_result();
            $peminjaman = $result->fetch_assoc();
            
            if ($peminjaman) {
                $stmt4 = $conn->prepare("UPDATE barang SET status = 'Dipinjam' WHERE id = ?");
                $stmt4->bind_param("i", $peminjaman['barang_id']);
                $stmt4->execute();
            }
        }
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function getReturnStatistics($start_date = '', $end_date = '') {
    global $conn;
    
    $where_clause = "";
    $params = [];
    $types = '';
    
    if ($start_date && $end_date) {
        $where_clause = "WHERE pg.tanggal_kembali BETWEEN ? AND ?";
        $params = [$start_date, $end_date];
        $types = 'ss';
    }
    
    $sql = "SELECT 
                COUNT(*) as total_returns,
                SUM(CASE WHEN pg.kondisi = 'Baik' THEN 1 ELSE 0 END) as good_condition,
                SUM(CASE WHEN pg.kondisi = 'Rusak' THEN 1 ELSE 0 END) as damaged_condition,
                SUM(CASE WHEN pg.kondisi = 'Hilang' THEN 1 ELSE 0 END) as lost_condition,
                SUM(CASE WHEN pg.tanggal_kembali <= p.target_kembali THEN 1 ELSE 0 END) as on_time,
                SUM(CASE WHEN pg.tanggal_kembali > p.target_kembali THEN 1 ELSE 0 END) as late_returns
            FROM pengembalian pg
            LEFT JOIN peminjaman p ON p.id = pg.peminjaman_id
            $where_clause";
    
    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getDamageReport($start_date = '', $end_date = '') {
    global $conn;
    
    $where_clause = "";
    $params = [];
    $types = '';
    
    if ($start_date && $end_date) {
        $where_clause = "AND pg.tanggal_kembali BETWEEN ? AND ?";
        $params = [$start_date, $end_date];
        $types = 'ss';
    }
    
    $sql = "SELECT b.nama_barang, b.nomor_inventaris, pg.kondisi, pg.catatan, 
                   pg.tanggal_kembali, u.username
            FROM pengembalian pg
            LEFT JOIN peminjaman p ON p.id = pg.peminjaman_id
            LEFT JOIN barang b ON b.id = p.barang_id
            LEFT JOIN users u ON u.id = p.user_id
            WHERE pg.kondisi IN ('Rusak', 'Hilang') $where_clause
            ORDER BY pg.tanggal_kembali DESC";
    
    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}
?>