<?= $this->extend('layouts/LayMgr') ?>
<?= $this->section('content') ?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title d-inline">Event Master</h3>
    <button class="btn btn-sm btn-primary float-right mb-3" data-toggle="modal" data-target="#modalAddEvent">
      <i class="fas fa-building"></i> Tambah Event
    </button>
  </div>

  <div class="card-body">
    <table id="masterEventTableMgr" class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Event</th>
          <th>Tanggal Event</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>

  <!-- Modal Tambah Event -->
  <div class="modal fade" id="modalAddEvent" tabindex="-1" role="dialog" aria-labelledby="addEventLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('EventManager/saveEventMgr') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addEventLabel">Tambah Event Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Event</label>
              <input type="text" name="eventName" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Tahun Event</label>
              <input type="number" name="eventYears" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="eventActive" class="form-control" required>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Modal Edit Event -->
  <div class="modal fade" id="modalEditEvent" tabindex="-1" role="dialog" aria-labelledby="editEventLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="formEditEvent" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="eventId" id="editEventId">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editEventLabel">Edit Event</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Event</label>
              <input type="text" name="eventName" id="editEventName" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Tahun Event</label>
              <input type="number" name="eventYears" id="editEventYears" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="eventActive" id="editEventActive" class="form-control" required disabled>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          </div>
        </div>
      </form>
    </div>
  </div>



  <?= $this->endSection() ?>