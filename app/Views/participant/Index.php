<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title d-inline">Data Participant</h3>
        <!-- <button class="btn btn-sm btn-primary float-right mb-3" data-toggle="modal" data-target="#modalAddUser">
            <i class="fas fa-user-plus"></i> Tambah User
        </button> -->

    </div>

    <div class="card-body">
        <table id="ParticipantTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>FristName</th>
                    <th>race category</th>
                    <th>BibNo</th>
                    <th>PhoneNo</th>
                    <th>Jersey</th>
                    <th>ID Number</th>
                    <th>Option</th>
                </tr>
            </thead>
        </table>

    </div>

    <!-- Modal Edit Participant -->
    <div class="modal fade" id="editParticipantModal" tabindex="-1" role="dialog" aria-labelledby="editParticipantLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="editParticipantForm" action="<?= base_url('participant/update') ?>" method="post" class="modal-content needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="editParticipantLabel">Edit Participant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="editParticipantId">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editFirstname">First Name</label>
                            <input type="text" name="firstname" id="editFirstname" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editlastname">Last Name</label>
                            <input type="text" name="lastname" id="editlastname" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editRaceCategory">Race Category</label>
                            <input type="text" name="race_category" id="editRaceCategory" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="editPhone">Phone No</label>
                            <input type="text" name="phonenumber" id="editPhone" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="editJersey">Jersey</label>
                            <select name="jerseySize" id="editJersey" class="form-control">
                                <option value="">- Pilih Ukuran -</option>
                            </select>

                        </div>
                        <div class="form-group col-md-6">
                            <label for="editIdNumber">ID Number</label>
                            <input type="text" name="idnumber" id="editIdNumber" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="editBib">BIB No</label>
                        <input type="text" name="bib" id="editBib" class="form-control" readonly>
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