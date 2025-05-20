console.log('Operator.js loaded!');

// Auto-submit saat barcode di-scan
$('#keyword').on('keypress', function (e) {
  if (e.which === 13) e.preventDefault();
});
$('#keyword').on('change', function () {
  $('#search-form').submit();
});

// Submit form pencarian peserta
$('#search-form').on('submit', function (e) {
  e.preventDefault();

  const keyword = $('#keyword').val().trim();
  if (keyword === '') return;

  // Tampilkan spinner loading
  $('#result .col-md-10').html(`
    <div class="text-center my-4">
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  `);

  // AJAX GET ke backend
  $.get(base_url + 'operator/search-ajax', { keyword }, function (data) {
    let html = '';
    if (data.length > 0) {
      data.forEach(function (row) {
        const isSelesai =
          row.status_repc === 'Done' && row.processed_End !== null;
        const isSedangProses =
          row.processed_at !== null &&
          row.status_repc === 'Proses' &&
          parseInt(row.processed_by_id) !== parseInt(session_user_id);

        let statusText = '';
        if (isSelesai) {
          statusText = `
            <div class="d-flex align-items-center">
              <span class="badge badge-success px-3 py-2 me-3" style="font-size: 1.05rem;">
                <i class="fas fa-check-circle"></i> Selesai <strong>Oleh </strong> ${
                  row.processed_by ?? '-'
                }
              </span>
            </div>`;
        } else if (isSedangProses) {
          statusText = `
            <span class="badge badge-warning px-3 py-2" style="font-size: 1.1rem;">
              <i class="fas fa-spinner fa-spin"></i> Sedang diproses ${
                row.processed_by ?? '-'
              }
            </span>`;
        }

        const disabled = isSelesai || isSedangProses ? 'disabled' : '';

        html += `
      <div class="card mb-4 shadow-sm">
        <div class="card-body" style="font-size: 1.15rem;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="font-size: 1.5rem; font-weight: 700;">
              ${row.firstname ?? ''} ${row.lastname ?? ''}
            </h5>
            <span class="text-muted">
              <i class="fas fa-file-invoice"></i> 
              <strong>Invoice:</strong> ${row.invoice ?? ''}
            </span>
          </div>

          <div class="row">
            <div class="col-md-6 mb-2">
              <strong><i class="fas fa-id-card"></i> ID Number:</strong><br>
              ${row.idNumber ?? ''}
            </div>
            <div class="col-md-6 mb-2">
              <strong><i class="fas fa-envelope"></i> Email:</strong><br>
              ${row.email_address ?? ''}
            </div>
            <div class="col-md-6 mb-2">
              <strong><i class="fas fa-phone"></i> Phone:</strong><br>
              ${row.phonenumber ?? ''}
            </div>
            <div class="col-md-6 mb-2">
              <strong><i class="fas fa-tshirt"></i> Jersey:</strong><br>
              ${row.jerseySize ?? ''}
            </div>
            <div class="col-md-6 mb-2">
              <strong><i class="fas fa-running"></i> Race Category:</strong><br>
              ${row.race_category ?? ''}
            </div>
            <div class="col-md-6 mb-2">
              <strong><i class="fas fa-hashtag"></i> Bib:</strong><br>
              <span class="badge badge-primary px-3 py-2" style="font-size: 1.15rem;">${
                row.bib ?? ''
              }</span>
              <span class="badge badge-info px-3 py-2" style="font-size: 1.15rem;">${
                row.chipscode ?? ''
              }</span>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-4">
            <div>${statusText}</div>
            <div>
              <button class="btn btn-secondary btn-print px-4 me-2" style="font-size: 1.05rem;"
                      data-name="${row.firstname} ${row.lastname}" 
                      data-jersey="${row.jerseySize}" 
                      data-bib="${row.bib}" 
                      data-chip="${row.chipscode}">
                <i class="fas fa-print"></i> Reprint Label
              </button>
              <button class="btn btn-success btn-checkout px-4" style="font-size: 1.05rem;"
                      data-id="${row.id}" 
                      data-bib="${row.bib}" ${disabled}>
                <i class="fas fa-check-circle"></i> Check Out
              </button>
            </div>
          </div>
        </div>
      </div>`;
      });
    } else {
      html = `<div class="alert alert-warning text-center" style="font-size: 1.15rem;">
                Tidak ditemukan peserta dengan kata kunci: <strong>${keyword}</strong>
              </div>`;
    }

    $('#result .col-md-10').html(html);
  });
});

$(document).on('click', '.btn-checkout', function () {
  const id = $(this).data('id');
  const bib = $(this).data('bib');
  $.post(base_url + 'operator/checkout-start', { id });
  $('#checkoutParticipantId').val(id);
  $('#expectedBib').val(bib);
  $('#confirmBib').val('');
  $('#bibMismatch').addClass('d-none');
  $('#wakilFields').addClass('d-none');
  $('#pengambilanOleh').val('sendiri');
  $('#modalCheckout').modal('show');
  setTimeout(() => $('#confirmBib').focus(), 300);
});

$('#pengambilanOleh').on('change', function () {
  $('#wakilFields').toggleClass('d-none', $(this).val() !== 'diwakilkan');
});

$('#confirmBib').on('input', function () {
  const expected = $('#expectedBib').val().trim();
  const input = $(this).val().trim();
  $(this).toggleClass('is-invalid', input !== expected);
  $('#bibMismatch').toggleClass('d-none', input === expected);
});

$('#btnConfirmCheckout').on('click', function () {
  const id = $('#checkoutParticipantId').val();
  const expectedBib = $('#expectedBib').val().trim();
  const inputBib = $('#confirmBib').val().trim();
  const pengambilan = $('#pengambilanOleh').val();
  const namaWakil = $('#namaWakil').val().trim();
  const telpWakil = $('#telpWakil').val().trim();

  // ✅ Validasi BIB
  if (inputBib !== expectedBib) {
    $('#confirmBib').addClass('is-invalid');
    $('#bibMismatch').removeClass('d-none');
    return;
  }

  // ✅ Bersihkan validasi jika cocok
  $('#confirmBib').removeClass('is-invalid');
  $('#bibMismatch').addClass('d-none');

  // ✅ Kirim AJAX POST
  $.post(base_url + 'operator/checkout', {
    id,
    wakil: pengambilan,
    nama_pengambil: namaWakil,
    telp_pengambil: telpWakil,
    bib_scan: inputBib,
  })
    .done(function (res) {
      if (res.status) {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: res.message,
          timer: 2000,
          showConfirmButton: false,
        }).then(() => {
          window.location.href =
            res.redirect_url || base_url + 'Operator/search-participant';
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: res.message,
        });
      }
    })
    .fail(function () {
      Swal.fire({
        icon: 'error',
        title: 'Server Error',
        text: 'Terjadi kesalahan pada server.',
      });
    });
});
