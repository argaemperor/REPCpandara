<?= $this->extend('layouts/LayMgr') ?>
<?= $this->section('content') ?>

<div class="card mt-3">
    <div class="card-header">
        <h3>Scan & Validasi BIB</h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('EventManager/processCheckoutScan') ?>" id="scanForm">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Nama</th>
                        <th>BIB Sistem</th>
                        <th>Scan / Input BIB</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $p): ?>
                        <tr class="bib-row">
                            <td><?= esc($p['invoice']) ?></td>
                            <td><?= esc($p['firstname'] . ' ' . $p['lastname']) ?></td>
                            <td class="system-bib"><strong><?= esc($p['bib']) ?></strong></td>
                            <td>
                                <input
                                    type="text"
                                    class="form-control input-bib"
                                    data-id="<?= $p['id'] ?>"
                                    data-bib="<?= $p['bib'] ?>"
                                    placeholder="Scan/Input BIB"
                                    name="scanned_bib[<?= $p['id'] ?>]"
                                    required>

                                <input
                                    type="hidden"
                                    name="validated[<?= $p['id'] ?>]"
                                    class="bib-valid-<?= $p['id'] ?>"
                                    value="">

                            </td>
                            <td class="status text-danger"><span>Belum Valid</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success mt-3" id="btnConfirm" disabled>Konfirmasi Checkout</button>
        </form>
    </div>
</div>

<!-- Tambahkan Script Validasi -->
<script>
    function validateBIBs() {
        let isAllValid = true;

        $('.bib-row').each(function() {
            const systemBIB = $(this).find('.system-bib').text().trim();
            const inputBIB = $(this).find('.input-bib').val().trim();
            const statusEl = $(this).find('.status');
            const inputHidden = $(this).find('input[type="hidden"]');

            if (systemBIB === inputBIB && inputBIB !== '') {
                statusEl.html('<span class="text-success">Valid</span>');
                inputHidden.val('1'); // menandakan valid
            } else {
                statusEl.html('<span class="text-danger">Belum Valid</span>');
                inputHidden.val('');
                isAllValid = false;
            }
        });

        // Enable tombol hanya jika semua valid
        $('#btnConfirm').prop('disabled', !isAllValid);
    }

    $(document).on('input', '.input-bib', function() {
        validateBIBs();
    });

    // Optional: validasi ulang saat halaman selesai dimuat
    $(document).ready(function() {
        validateBIBs();
    });
</script>

<?= $this->endSection() ?>