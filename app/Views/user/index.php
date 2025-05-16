<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title d-inline">Data User</h3>
    <button class="btn btn-sm btn-primary float-right mb-3" data-toggle="modal" data-target="#modalAddUser">
      <i class="fas fa-user-plus"></i> Tambah User
    </button>

  </div>

  <div class="card-body">
    <table id="userTable" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Level</th>
          <th>Dibuat</th>
        </tr>
      </thead>
    </table>



  </div>

  <!-- Modals Add -->
  <div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="modalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form action="<?= base_url('users/save') ?>" method="post" class="modal-content needs-validation" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="modalAddUserLabel">Tambah User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name">Nama Lengkap</label>
              <input type="text" name="name" class="form-control" required>
              <div class="invalid-feedback">Nama Lengkap wajib diisi.</div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="Email">Email</label>
              <input type="text" name="email" class="form-control" required>
              <div class="invalid-feedback">Email wajib diisi.</div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="Phone">Phone</label>
              <input type="text" name="phone" class="form-control" required>
              <div class="invalid-feedback">Phone number wajib diisi.</div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="address">address</label>
              <input type="text" name="address" class="form-control" required>
              <div class="invalid-feedback">address wajib diisi.</div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="username">username</label>
              <input type="text" name="username" class="form-control" required>
              <div class="invalid-feedback">Username wajib diisi.</div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control" required>
              <div class="invalid-feedback">Password wajib diisi.</div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
            </div>
            <div class="col-md-6 mb-3">
              <label for="level">Level</label>
              <select name="level" class="form-control" required>
                <option value="" selected disabled>- Pilih Level -</option>
                <option value="1">Administrator</option>
                <option value="2">Event Manager</option>
                <option value="3">Oprator</option>
                <option value="4">visitor</option>
              </select>
              <div class="invalid-feedback">Level wajib dipilih.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>


  <?= $this->endSection() ?>