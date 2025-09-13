<?php
require_once '../../models/User.php';
$id = (int)($_GET['id'] ?? 0);
$user = getUserById($id);
if(!$user){ header('Location: index.php'); exit; }
include '../template/header.php';
?>

<div class="container p-4">
  <h5 class="mb-4">Edit User</h5>

  <form action="../../controllers/UserController.php" method="POST" class="row g-3">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <div class="col-md-4">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Password Baru <small>(kosongkan jika tidak diubah)</small></label>
      <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
    </div>

    <div class="col-md-6">
      <label class="form-label">Role</label>
      <select name="role" class="form-select">
        <?php
        $roles = [
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
        foreach($roles as $r){
          echo "<option value=\"$r\" ".($r===$user['role'] ? 'selected' : '').">$r</option>";
        }
        ?>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="Aktif"  <?= $user['status']==='Aktif' ? 'selected' : '' ?>>Aktif</option>
        <option value="Tidak Aktif" <?= $user['status']==='Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
      </select>
    </div>

    <div class="col-12">
      <button type="submit" name="edit" class="btn btn-primary">
        <i class="bi bi-save"></i> Update
      </button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<?php include '../template/footer.php'; ?>