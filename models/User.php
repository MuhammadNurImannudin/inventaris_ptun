<?php
require_once __DIR__.'/../config/database.php';

/* ---------- ROLE LIST PTUN ---------- */
function getRoleList(){
    return [
        'Administrator PTUN',
        'Ketua',
        'Wakil Ketua',
        'Sekretaris',
        'Panitera',
        'Panitera Muda',
        'Hakim',
        'Staf Administrasi',
        'Operator IT',
        'Security',
        'Cleaning Service'
    ];
}

/* ---------- SEARCH + FILTER ---------- */
function searchFilterUsers($keyword='', $role=''){
    global $conn;
    $sql = "SELECT * FROM users WHERE 1=1";
    $params = [];
    $types  = '';

    if($keyword){
        $sql .= " AND (username LIKE ? OR email LIKE ?)";
        $kw = "%$keyword%";
        $params[] = $kw;
        $params[] = $kw;
        $types .= 'ss';
    }
    if($role){
        $sql .= " AND role=?";
        $params[] = $role;
        $types .= 's';
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $conn->prepare($sql);
    if($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result();
}

/* ---------- SELECT ---------- */
function getAllUsers(){
    return searchFilterUsers(); // default tanpa filter
}

function getUserById($id){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/* ---------- INSERT ---------- */
function insertUser($data){
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users(username,email,password,role,status)
                            VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",
        $data['username'],
        $data['email'],
        MD5($data['password']),
        $data['role'],
        $data['status']
    );
    return $stmt->execute();
}

/* ---------- UPDATE ---------- */
function updateUser($id,$data,$password=null){
    global $conn;
    $fields = ['username=?','email=?','role=?','status=?'];
    $types  = 'ssss';
    $values = [$data['username'],$data['email'],$data['role'],$data['status']];

    if($password){
        $fields[] = 'password=?';
        $types   .= 's';
        $values[] = MD5($password);
    }

    $sql = "UPDATE users SET ".implode(',',$fields).", updated_at=NOW() WHERE id=?";
    $types .= 'i';
    $values[] = $id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types,...$values);
    return $stmt->execute();
}

/* ---------- DELETE ---------- */
function deleteUser($id){
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    return $stmt->execute();
}
?>