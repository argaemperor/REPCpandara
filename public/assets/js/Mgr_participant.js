$(document).ready(function () {
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
        {
          data: null,
          orderable: false,
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
    $.ajax({
      url: base_url + 'EventManager/checkoutMultiple',
      method: 'POST',
      data: {
        ids: selected,
      },
      success: function (res) {
        alert(res.message);
        $('#ParticipantMGRTable').DataTable().ajax.reload();
      },
      error: function () {
        alert('Gagal memproses checkout.');
      },
    });
  }
});
