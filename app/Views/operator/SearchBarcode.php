<?= $this->extend('layouts/LayOperator.php') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h2 class="text-center display-4">REPC Barcode</h2>

    <form id="search-form">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="form-group">
                    <div class="input-group input-group-lg">
                        <input type="search" name="keyword" id="keyword" class="form-control form-control-lg"
                            placeholder="Scan or type Invoice / ID Number" autofocus autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div id="result" class="row justify-content-center mt-4">
        <div class="col-md-10">
            <!-- AJAX result will be inserted here -->
        </div>
    </div>
</div>

<!-- Modal Check Out -->
<!-- Modal Check Out -->
<div class="modal fade" id="modalCheckout" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document"> <!-- Ukuran diperbesar -->
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle"></i> Konfirmasi Check Out
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="checkoutParticipantId">
                <input type="hidden" id="expectedBib">

                <div class="form-group">
                    <label for="confirmBib">Scan Ulang Nomor BIB</label>
                    <input type="text" class="form-control" id="confirmBib" placeholder="Scan ulang nomor BIB...">
                    <div id="bibMismatch" class="invalid-feedback d-none">
                        Nomor BIB tidak cocok!
                    </div>
                </div>

                <div class="form-group">
                    <label for="pengambilanOleh">Pengambilan oleh:</label>
                    <select class="form-control" id="pengambilanOleh">
                        <option value="sendiri">Sendiri</option>
                        <option value="diwakilkan">Diwakilkan</option>
                    </select>
                </div>

                <div id="wakilFields" class="d-none">
                    <div class="form-group">
                        <label for="namaWakil">Nama Pengambil</label>
                        <input type="text" class="form-control" id="namaWakil" placeholder="Nama lengkap">
                    </div>
                    <div class="form-group">
                        <label for="telpWakil">No. Telp Pengambil</label>
                        <input type="text" class="form-control" id="telpWakil" placeholder="08xxxx">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button id="btnConfirmCheckout" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Konfirmasi Check Out
                </button>
            </div>
        </div>
    </div>
</div>





<?= $this->endSection() ?>