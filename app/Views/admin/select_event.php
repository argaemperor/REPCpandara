<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pilih Event</h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('admin/select-event/save') ?>">
            <div class="form-group">
                <label for="eventID">Pilih Event Aktif</label>
                <select name="eventID" class="form-control" required>
                    <option value="">- Pilih Event -</option>
                    <?php foreach ($events as $event): ?>
                        <option value="<?= $event['eventId'] ?>">
                            <?= $event['eventName'] ?> (<?= $event['eventYears'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Pilih Event</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>