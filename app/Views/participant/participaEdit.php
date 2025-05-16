<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4 px-5 bg-white rounded shadow-sm">
    <h3 class="mb-4">Participant Detail</h3>

    <div class="row g-3">
        <!-- Race Category -->
        <div class="col-lg-4">
            <div class="form-group mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="mb-0 font-weight-bold">Race Category:</label>
                    <a href="#" class="text-primary small"
                        data-toggle="modal" data-target="#editModal"
                        data-field="race_category" data-id="<?= $participant['id'] ?>"
                        data-label="Race Category" data-value="<?= esc($participant['race_category']) ?>">
                        Edit
                    </a>
                </div>
                <input type="text" class="form-control mt-1" value="<?= esc($participant['race_category']) ?>" readonly>
            </div>
        </div>


        <!-- Bib (Tidak bisa di-edit) -->
        <div class="col-lg-4">
            <label class="form-label fw-bold">Bib:</label>
            <input type="text" class="form-control" value="<?= esc($participant['bib']) ?>" readonly>
        </div>

        <!-- Jersey Size -->
        <div class="col-lg-4">
            <label class="form-label fw-bold">Jersey Size:
                <a href="#" class="text-primary small" data-toggle="modal" data-target="#editModal"
                    data-field="jerseySize" data-id="<?= $participant['id'] ?>" data-label="Jersey Size"
                    data-value="<?= esc($participant['jerseySize']) ?>">Edit</a>
            </label>
            <input type="text" class="form-control" value="<?= esc($participant['jerseySize']) ?>" readonly>
        </div>

        <!-- First Name -->
        <div class="col-lg-4">
            <div class="form-group mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="mb-0 font-weight-bold">First Name</label>
                    <a href="#" class="text-primary small"
                        data-toggle="modal" data-target="#editModal"
                        data-field="firstname" data-id="<?= $participant['id'] ?>"
                        data-label="First Name" data-value="<?= esc($participant['firstname']) ?>">
                        Edit
                    </a>
                </div>
                <input type="text" class="form-control mt-1" value="<?= esc($participant['firstname']) ?>" readonly>
            </div>
        </div>

        <!-- Last Name -->
        <div class="col-lg-4">
            <div class="form-group mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="mb-0 font-weight-bold">Last Name</label>
                    <a href="#" class="text-primary small"
                        data-toggle="modal" data-target="#editModal"
                        data-field="lastname" data-id="<?= $participant['id'] ?>"
                        data-label="Last Name" data-value="<?= esc($participant['lastname']) ?>">
                        Edit
                    </a>
                </div>
                <input type="text" class="form-control mt-1" value="<?= esc($participant['lastname']) ?>" readonly>
            </div>
        </div>

        <!-- Phone Number -->
        <div class="col-lg-4">
            <div class="form-group mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="mb-0 font-weight-bold">Phone Number</label>
                    <a href="#" class="text-primary small"
                        data-toggle="modal" data-target="#editModal"
                        data-field="phonenumber" data-id="<?= $participant['id'] ?>"
                        data-label="Phone Number" data-value="<?= esc($participant['phonenumber']) ?>">
                        Edit
                    </a>
                </div>
                <input type="text" class="form-control mt-1" value="<?= esc($participant['phonenumber']) ?>" readonly>
            </div>
        </div>

        <!-- Email -->
        <div class="col-lg-4">
            <div class="form-group mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="mb-0 font-weight-bold">Email</label>
                    <a href="#" class="text-primary small"
                        data-toggle="modal" data-target="#editModal"
                        data-field="email_address" data-id="<?= $participant['id'] ?>"
                        data-label="Email" data-value="<?= esc($participant['email_address']) ?>">
                        Edit
                    </a>
                </div>
                <input type="text" class="form-control mt-1" value="<?= esc($participant['email_address']) ?>" readonly>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form method="post" action="<?= base_url('participant/update-field') ?>">

                    <input type="hidden" name="id" id="modal-id">
                    <input type="hidden" name="field" id="modal-field">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-label">Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="value" id="modal-value" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <?= $this->endSection() ?>