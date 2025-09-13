<?php
require_once '../../models/User.php';

$keyword = $_GET['search'] ?? '';
$role    = $_GET['role'] ?? '';
$users   = searchFilterUsers($keyword, $role);
include '../template/header.php';
?>

<div class="container-fluid p-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Manajemen User</h5>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-person-plus"></i> Tambah User
    </a>
  </div>

  <!-- Filter & Search -->
  <form method="GET" class="row g-3 mb-3">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="Cari username / email"
             value="<?= htmlspecialchars($keyword) ?>">
    </div>
    <div class="col-md-3">
      <select name="role" class="form-select">
        <option value="">Semua Role</option>
        <?php
        $roles = getRoleList(); // ambil dari User.php
        foreach($roles as $r){
          $sel = ($r === $role) ? 'selected' : '';
          echo "<option value=\"$r\" $sel>$r</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-outline-primary w-100">
        <i class="bi bi-search"></i> Filter
      </button>
    </div>
    <div class="col-md-3">
      <a href="index.php" class="btn btn-secondary w-100">Reset</a>
    </div>
  </form>

  <!-- Tabel User -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light text-center">
        <tr>
          <th>No</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Last Login</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php $no=1; while($u = mysqli_fetch_assoc($users)): ?>
        <tr>
          <td class="text-center"><?= $no++ ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td class="text-center">
            <span class="badge bg-primary"><?= htmlspecialchars($u['role']) ?></span>
          </td>
          <td class="text-center">
            <span class="badge bg-<?= $u['status']==='Aktif' ? 'success' : 'danger' ?>">
              <?= htmlspecialchars($u['status']) ?>
            </span>
          </td>
          <td><?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '-' ?></td>
          <td class="text-center">
            <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning" title="Edit">
              <i class="bi bi-pencil"></i>
            </a>
            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $u['id'] ?>">
              <i class="bi bi-trash"></i>
            </button>

            <!-- Modal Konfirmasi Hapus -->
            <div class="modal fade" id="hapusModal<?= $u['id'] ?>" tabindex="-1">
              <div class="modal-dialog modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title">Konfirmasi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    Hapus user <b><?= $u['username'] ?></b>?
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <a href="../../controllers/UserController.php?hapus=<?= $u['id'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../template/footer.php'; ?>