<?php include '../template/header.php'; ?>
<div class="container p-4">
  <h5 class="mb-4">Tambah User Baru</h5>

  <form action="../../controllers/UserController.php" method="POST" class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Username <span class="text-danger">*</span></label>
      <input type="text" name="username" class="form-control" required placeholder="cth: joko">
    </div>

    <div class="col-md-4">
      <label class="form-label">Email <span class="text-danger">*</span></label>
      <input type="email" name="email" class="form-control" required placeholder="cth: joko@ptun.go.id">
    </div>

    <div class="col-md-4">
      <label class="form-label">Password <span class="text-danger">*</span></label>
      <input type="password" name="password" class="form-control" required placeholder="Min 6 karakter">
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
          echo "<option value=\"$r\">$r</option>";
        }
        ?>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Status</label>
      <select name="status" class="form-select">
        <option value="Aktif" class="text-success">Aktif</option>
        <option value="Tidak Aktif" class="text-danger">Tidak Aktif</option>
      </select>
    </div>

    <div class="col-12">
      <button type="submit" name="tambah" class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan
      </button>
      <a href="index.php" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
<?php include '../template/footer.php'; ?>