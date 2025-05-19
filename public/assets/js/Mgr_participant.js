$(document).ready(function () {
  const selectedParticipants = []; // simpan ID yang terpilih
  const tableOptions = {
    processing: true,
    serverSide: true,
    language: {
      search: 'Cari:',
      lengthMenu: 'Tampilkan _MENU_ data',
      zeroRecords: 'Tidak ditemukan data',
      info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
      paginate: {
        first: 'Pertama',
        last: 'Terakhir',
        next: '›',
        previous: '‹',
      },
    },
  };

  // DataTable - Participant
  if ($('#ParticipantMGRTable').length) {
    $('#ParticipantMGRTable').DataTable({
      ...tableOptions,
      ajax: base_url + 'EventManager/ajaxList',
      columns: [
        { data: 'invoice' },
        { data: 'fullname' },
        { data: 'race_category', orderable: false },
        { data: 'bib' },
        { data: 'phonenumber', orderable: false },
        { data: 'jerseySize', orderable: false },
        { data: 'idNumber' },
        { data: 'event_name', orderable: false },
        {
          data: 'status_repc',
          render: function (data, type, row) {
            let badgeClass = '';
            let label = data ?? 'Unknown';

            switch (data) {
              case 'Pending':
                badgeClass = 'badge badge-warning';
                break;
              case 'Proses':
                badgeClass = 'badge badge-info';
                break;
              case 'Done':
                badgeClass = 'badge badge-success';
                break;
              default:
                badgeClass = 'badge badge-secondary';
                label = 'Unknown';
            }

            return `<span class="${badgeClass}">${label}</span>`;
          },
        },

        {
          data: null,
          render: function (data) {
            return `
                <button class="btn btn-sm btn-warning" onclick='editParticipant(${JSON.stringify(
                  data,
                )})'>
                  Edit
                </button>
              `;
          },
          orderable: false,
          searchable: false,
        },
      ],
    });
  }

  // DataTable - Participant Check out
  if ($('#ParticipantMGRTableCO').length) {
    $('#ParticipantMGRTableCO').DataTable({
      ...tableOptions,
      ajax: base_url + 'EventManager/ajaxListChecout',
      columns: [
        {
          data: null,
          orderable: false,
          searchable: false,
          className: 'no-sort',
          render: function (data, type, row) {
            return `<input type="checkbox" class="participant-check" value="${row.id}">`;
          },
        },
        { data: 'invoice' },
        { data: 'fullname' },
        { data: 'race_category', orderable: false },
        { data: 'bib' },
        { data: 'phonenumber', orderable: false },
        { data: 'jerseySize', orderable: false },
        { data: 'idNumber' },
        { data: 'event_name', orderable: false },
        {
          data: 'status_repc',
          render: function (data, type, row) {
            let badgeClass = '';
            let label = data ?? 'Unknown';

            switch (data) {
              case 'Pending':
                badgeClass = 'badge badge-warning';
                break;
              case 'Proses':
                badgeClass = 'badge badge-info';
                break;
              case 'Done':
                badgeClass = 'badge badge-success';
                break;
              default:
                badgeClass = 'badge badge-secondary';
                label = 'Unknown';
            }

            return `<span class="${badgeClass}">${label}</span>`;
          },
        },
      ],
    });
  }

  // Panggil saat modal dibuka
  function editParticipant(data) {
    $.get(base_url + 'participant/jersey-options', function (res) {
      const jerseySelect = $('#editJersey');
      jerseySelect.empty();
      jerseySelect.append('<option value="">- Pilih Ukuran -</option>');
      res.forEach(function (item) {
        jerseySelect.append(
          `<option value="${item.jerseySize}">${item.jerseySize}</option>`,
        );
      });
      jerseySelect.val(data.jerseySize); // Set nilai default
    });

    // Isi field lain

    $('#editParticipantId').val(data.id);
    $('#editFirstname').val(data.firstname);
    $('#editlastname').val(data.lastname);
    $('#editRaceCategory').val(data.race_category);
    $('#editPhone').val(data.phonenumber);
    $('#editIdNumber').val(data.idNumber);
    $('#editBib').val(data.bib);
    $('#editParticipantModal').modal('show');
  }
  window.editParticipant = editParticipant;

  // Handle form submit with validation
  $('#editParticipantForm').on('submit', function (e) {
    e.preventDefault();
    const form = this;

    if (!form.checkValidity()) {
      e.stopPropagation();
      form.classList.add('was-validated');
      return;
    }

    $.post($(form).attr('action'), $(form).serialize(), function (res) {
      $('#editParticipantModal').modal('hide');
      $('#ParticipantMGRTable').DataTable().ajax.reload();
    }).fail(function () {
      alert('Terjadi kesalahan saat menyimpan data.');
    });
  });
});

// Simpan/ambil data checkbox saat klik
$(document).on('change', '.participant-check', function () {
  const id = $(this).val();
  if ($(this).is(':checked')) {
    if (!selectedParticipants.includes(id)) {
      selectedParticipants.push(id);
    }
  } else {
    const index = selectedParticipants.indexOf(id);
    if (index !== -1) {
      selectedParticipants.splice(index, 1);
    }
  }
});

//multiple chekout
$('#btnCheckoutSelected').click(function () {
  const selected = [];
  $('.participant-check:checked').each(function () {
    selected.push($(this).val());
  });

  if (selected.length === 0) {
    alert('Pilih minimal satu peserta.');
    return;
  }

  if (confirm('Yakin ingin melakukan checkout untuk peserta terpilih?')) {
    // Kirim ke halaman scan via POST form
    const form = $('<form>', {
      method: 'POST',
      action: base_url + 'EventManager/checkoutScan',
    });

    selected.forEach(function (id) {
      form.append(
        $('<input>', {
          type: 'hidden',
          name: 'ids[]',
          value: id,
        }),
      );
    });

    $('body').append(form);
    form.submit();
  }
});

//Cek Input bib
function validateBIBs() {
  let isAllValid = true;

  $('.bib-row').each(function () {
    const systemBIB = $(this).find('.system-bib').text().trim();
    const inputBIB = $(this).find('.input-bib').val().trim();
    const statusEl = $(this).find('.status');

    if (systemBIB === inputBIB && inputBIB !== '') {
      statusEl.text('Valid').css('color', 'green');
    } else {
      statusEl.text('Belum Valid').css('color', 'red');
      isAllValid = false;
    }
  });

  return isAllValid;
}

// jalankan setiap kali user input
$(document).on('input', '.input-bib', function () {
  validateBIBs();
});

// saat klik tombol konfirmasi
$('#btnKonfirmasiCheckout').click(function (e) {
  if (!validateBIBs()) {
    e.preventDefault();
    alert('Pastikan semua BIB sudah valid sebelum checkout.');
  }
});

function validateBIBs() {
  let isAllValid = true;

  $('.bib-row').each(function () {
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

$(document).on('input', '.input-bib', function () {
  validateBIBs();
});

// Optional: validasi ulang saat halaman selesai dimuat
$(document).ready(function () {
  validateBIBs();
});

$(document).ready(function () {
  // Cek setiap kali input berubah
  $('.scan-bib').on('input', function () {
    const inputVal = $(this).val();
    const expectedVal = $(this).data('bib');
    const id = $(this).data('id');

    if (inputVal === expectedVal) {
      $('#status-' + id).html('<span class="text-success">Valid</span>');
      $('.bib-valid-' + id).val('1');
    } else {
      $('#status-' + id).html('<span class="text-danger">Belum Valid</span>');
      $('.bib-valid-' + id).val('');
    }

    checkAllValid(); // panggil pengecekan
  });

  // Fungsi cek semua valid
  function checkAllValid() {
    let allValid = true;
    $('.scan-bib').each(function () {
      const id = $(this).data('id');
      if ($('.bib-valid-' + id).val() !== '1') {
        allValid = false;
      }
    });

    $('#btnConfirm').prop('disabled', !allValid);
  }

  // Cek awal ketika halaman dimuat (jaga-jaga)
  checkAllValid();
});
