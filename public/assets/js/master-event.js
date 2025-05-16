$(document).ready(function () {
  $('#masterEventTable').DataTable({
    ajax: base_url + 'admin/Event/ajaxEventList',
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

function toggleEventStatus(id) {
  console.log('Klik toggle ID:', id);
  if (confirm('Apakah kamu yakin ingin mengganti status event ini?')) {
    $.post(base_url + 'admin/event/toggle/' + id, function (res) {
      $('#masterEventTable').DataTable().ajax.reload();
      alert(res.message);
    });
  }
}

function editEvent(id, name, year, status) {
  $('#editEventId').val(id);
  $('#editEventName').val(name);
  $('#editEventYears').val(year);
  $('#editEventActive').val(status);
  $('#modalEditEvent').modal('show');
}

$('#formEditEvent').on('submit', function (e) {
  e.preventDefault();
  $.post(base_url + 'admin/event/update', $(this).serialize(), function (res) {
    $('#modalEditEvent').modal('hide');
    $('#masterEventTable').DataTable().ajax.reload();
    alert(res.message);
  });
});

function deleteEvent(id) {
  if (confirm('Yakin ingin menghapus event ini?')) {
    $.post(base_url + 'admin/event/delete/' + id, function (res) {
      $('#masterEventTable').DataTable().ajax.reload();
      alert(res.message);
    });
  }
}
