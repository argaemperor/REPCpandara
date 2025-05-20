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
  $(document).ready(function () {
    const selectedIds = new Set(); // Menyimpan ID peserta terpilih

    // Inisialisasi DataTable untuk Checkout
    const tableCheckout = $('#ParticipantMGRTableCO').DataTable({
      processing: true,
      serverSide: true,
      ajax: base_url + 'EventManager/ajaxListChecout',
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
      columns: [
        {
          data: null,
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            const checked = selectedIds.has(row.id) ? 'checked' : '';
            return `<input type="checkbox" class="participant-check" value="${row.id}" ${checked}>`;
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
          render: function (data) {
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

    // Tangani perubahan checkbox: tambah/hapus dari Set
    $('#ParticipantMGRTableCO').on('change', '.participant-check', function () {
      const id = parseInt($(this).val());
      if ($(this).is(':checked')) {
        selectedIds.add(id);
      } else {
        selectedIds.delete(id);
      }
    });

    // Restore checkbox tercentang saat tabel di-draw ulang (paging, search, dll)
    tableCheckout.on('draw', function () {
      $('#ParticipantMGRTableCO .participant-check').each(function () {
        const id = parseInt($(this).val());
        $(this).prop('checked', selectedIds.has(id));
      });
    });

    // Tombol Checkout
    $('#btnCheckoutSelected').click(function () {
      if (selectedIds.size === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Tidak ada peserta dipilih',
          text: 'Silakan pilih minimal satu peserta.',
        });
        return;
      }

      Swal.fire({
        title: 'Konfirmasi Checkout',
        text: 'Yakin ingin melakukan checkout untuk peserta terpilih?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Checkout!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          const form = $('<form>', {
            method: 'POST',
            action: base_url + 'EventManager/checkoutScan',
          });

          Array.from(selectedIds).forEach(function (id) {
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
    });
  });

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
    Swal.fire({
      icon: 'warning',
      title: 'Tidak ada peserta dipilih',
      text: 'Silakan pilih minimal satu peserta.',
    });
    return;
  }

  Swal.fire({
    title: 'Konfirmasi Checkout',
    text: 'Yakin ingin melakukan checkout untuk peserta terpilih?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, Checkout!',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
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

    Swal.fire({
      icon: 'warning',
      title: 'Checkout Ditolak',
      text: 'Pastikan semua BIB sudah valid sebelum checkout.',
      confirmButtonText: 'OK',
    });
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
// JS Untuk menapilkan List Event
$(document).ready(function () {
  $('#masterEventTableMgr').DataTable({
    ajax: base_url + 'EventManager/ajaxEventList',
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: 'eventName' },
      { data: 'eventYears' },
      {
        data: 'eventActive',
        render: function (data) {
          return data == 1
            ? '<span class="badge badge-success">Aktif</span>'
            : '<span class="badge badge-secondary">Nonaktif</span>';
        },
      },
      {
        data: null,
        render: function (data) {
          let btnClass = data.eventActive == 1 ? 'btn-danger' : 'btn-success';
          let btnText = data.eventActive == 1 ? 'Nonaktifkan' : 'Aktifkan';
          return `
           <button class="btn btn-sm btn-info mr-1" onclick="editEvent(${data.eventId}, '${data.eventName}', '${data.eventYears}', ${data.eventActive})">Report</button>
        <button class="btn btn-sm btn-info mr-1" onclick="editEvent(${data.eventId}, '${data.eventName}', '${data.eventYears}', ${data.eventActive})">Edit</button>
        <button class="btn btn-sm ${btnClass} mr-1" onclick="toggleEventStatus(${data.eventId})">${btnText}</button>
        <button class="btn btn-sm btn-danger" onclick="deleteEvent(${data.eventId})">Hapus</button>
      `;
        },
      },
    ],
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
  });
});
// JS merubah Status Event
function toggleEventStatus(id) {
  Swal.fire({
    title: 'Yakin ingin mengubah status event ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Ubah!',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(base_url + 'EventManager/toggle/' + id, function (res) {
        $('#masterEventTableMgr').DataTable().ajax.reload();

        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: res.message,
          timer: 2000,
          showConfirmButton: false,
        });
      });
    }
  });
}

// Menampilkan modal edit dengan data event
function editEvent(id, name, year, status) {
  $('#editEventId').val(id);
  $('#editEventName').val(name);
  $('#editEventYears').val(year);
  $('#editEventActive').val(status);
  $('#modalEditEvent').modal('show');
}

// Submit form edit event
$('#formEditEvent').on('submit', function (e) {
  e.preventDefault();

  $.post(base_url + 'EventManager/update', $(this).serialize(), function (res) {
    $('#modalEditEvent').modal('hide');
    $('#masterEventTableMgr').DataTable().ajax.reload();

    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: res.message,
      timer: 2000,
      showConfirmButton: false,
    });
  });
});

// Hapus event dengan konfirmasi SweetAlert
function deleteEvent(id) {
  Swal.fire({
    title: 'Yakin ingin menghapus event ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(base_url + 'EventManager/delete/' + id, function (res) {
        $('#masterEventTableMgr').DataTable().ajax.reload();

        Swal.fire({
          icon: 'success',
          title: 'Terhapus!',
          text: res.message,
          timer: 2000,
          showConfirmButton: false,
        });
      });
    }
  });
}

//JS unutk List Operator
$(document).ready(function () {
  if ($('#OpratorTableMgr').length) {
    $('#OpratorTableMgr').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: base_url + 'EventManager/ajaxMasterUserList',
        type: 'GET',
        error: function (xhr, error, thrown) {
          console.error('Gagal mengambil data:', error);
        },
      },
      columns: [
        { data: 'no' },
        { data: 'name' },
        { data: 'email' },
        {
          data: 'Level',
          render: function (data) {
            switch (parseInt(data)) {
              case 1:
                return '<span class="badge badge-success">Administrator</span>';
              case 2:
                return '<span class="badge badge-primary">Event Manager</span>';
              case 3:
                return '<span class="badge badge-warning">Registration Operator</span>';
              case 4:
                return '<span class="badge badge-info">Guest / Viewer</span>';
              default:
                return '<span class="badge badge-secondary">Unknown</span>';
            }
          },
        },
        { data: 'phone' },
        { data: 'address' },
        {
          data: null,
          render: function (data) {
            return `
              <button class="btn btn-sm btn-warning btn-edit-user"
                data-id="${data.id}"
                data-name="${data.name}"
                data-email="${data.email}"
                data-phone="${data.phone}"
                data-address="${data.address}"
                data-username="${data.username}"
                data-level="${data.level}"
                data-toggle="modal" data-target="#modalEditUser">
                Edit
              </button>
              <button class="btn btn-sm btn-danger" onclick="deleteUser(${data.id})">Hapus</button>
            `;
          },
          orderable: false,
          searchable: false,
        },
      ],
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
    });
  }

  //  Modal Edit Handler
  $(document).on('click', '.btn-edit-user', function () {
    $('#edit-id').val($(this).data('id'));
    $('#edit-name').val($(this).data('name'));
    $('#edit-email').val($(this).data('email'));
    $('#edit-phone').val($(this).data('phone'));
    $('#edit-address').val($(this).data('address'));
    $('#edit-username').val($(this).data('username'));
    $('#edit-level').val($(this).data('level'));
    $('#modalEditUser').modal('show');
  });
});

//Deelete user

function deleteUser(id) {
  Swal.fire({
    title: 'Yakin ingin menghapus user ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(base_url + 'admin/delete-user/' + id, function (res) {
        if (res.status) {
          $('#userTable').DataTable().ajax.reload();

          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: res.message,
            timer: 2000,
            showConfirmButton: false,
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: res.message || 'Gagal menghapus user.',
          });
        }
      }).fail(function () {
        Swal.fire({
          icon: 'error',
          title: 'Server Error!',
          text: 'Terjadi kesalahan pada server.',
        });
      });
    }
  });
}
