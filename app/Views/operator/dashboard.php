<!-- File: app/Views/operator/dashboard.php -->
<?= $this->extend('layouts/layoperator') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="mb-4">Dashboard Operator</h1>

    <div class="row">
        <!-- Kartu total peserta -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= $totalParticipant ?? 0 ?></h3>
                    <p>Total Peserta</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="<?= base_url('Operator/participant') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Kartu selesai -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $doneParticipant ?? 0 ?></h3>
                    <p>Peserta Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>

        <!-- Kartu proses -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $processingParticipant ?? 0 ?></h3>
                    <p>Peserta Sedang Diproses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
            </div>
        </div>

        <!-- Kartu belum -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3><?= $notYetParticipant ?? 0 ?></h3>
                    <p>Peserta Belum Proses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Jumlah Jersey per Ukuran</h5>
                </div>
                <div class="card-body p-2">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Ukuran Jersey</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jerseyCounts as $row): ?>
                                <tr>
                                    <td><?= esc($row['jerseySize']) ?></td>
                                    <td><strong><?= esc($row['total']) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Jumlah Jersey yang diambil</h5>
                </div>
                <div class="card-body p-2">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Ukuran Jersey</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jerseyTaken as $row): ?>
                                <tr>
                                    <td><?= esc($row['jerseySize']) ?></td>
                                    <td><strong><?= esc($row['total']) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Info Event Aktif</h3>
        </div>
        <div class="card-body">
            <p><strong>Event:</strong> <?= $eventName ?? '-' ?></p>
            <p><strong>Tanggal:</strong> <?= $eventDate ?? '-' ?></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>