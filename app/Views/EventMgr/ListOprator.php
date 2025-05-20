<?= $this->extend('layouts/LayMgr') ?>
<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title d-inline">Data oprator</h3>

    </div>

    <div class="card-body">
        <table id="OpratorTableMgr" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>



    <!-- Modal Edit User -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="modalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="editUserForm" method="post" action="<?= base_url('admin/update-user') ?>" class="modal-content needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditUserLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit-name">Nama Lengkap</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-email">Email</label>
                            <input type="email" name="email" id="edit-email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-phone">Phone</label>
                            <input type="text" name="phone" id="edit-phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-address">Address</label>
                            <input type="text" name="address" id="edit-address" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit-level">Level</label>
                            <select name="level" id="edit-level" class="form-control" required>
                                <option value="1">Administrator</option>
                                <option value="2">Event Manager</option>
                                <option value="3">Oprator</option>
                                <option value="4">visitor</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    <?= $this->endSection() ?>