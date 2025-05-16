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
  if ($('#ParticipantTable').length) {
    $('#ParticipantTable').DataTable({
      ...tableOptions,
      ajax: base_url + 'participant/ajaxList',
      columns: [
        { data: 'no' },
        { data: 'fullname' },
        { data: 'race_category' },
        { data: 'bib' },
        { data: 'phonenumber' },
        { data: 'jerseySize' },
        { data: 'idNumber' },
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
      $('#ParticipantTable').DataTable().ajax.reload();
    }).fail(function () {
      alert('Terjadi kesalahan saat menyimpan data.');
    });
  });
});
