<?= $this->extend('layouts/LayOperator.php') ?>

<?= $this->section('content') ?>

<div class="card shadow">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Data Peserta REPC</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableParticipant" class="table table-striped table-hover table-bordered table-sm text-sm" style="width:100%;">
                <thead class="thead-light">
                    <tr>
                        <th>Invoice</th>
                        <th>Nama</th>
                        <th>BiB</th>
                        <th>Id Number</th>
                        <th>Nomor HP</th>
                        <th>Jersey</th>
                        <th>Race</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>