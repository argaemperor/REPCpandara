$(document).ready(function () {
  if ($('#userTable').length) {
    $('#userTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: base_url + 'admin/ajaxMasterUserList',
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
                  data-username="${data.Username}"
                  data-level="${data.Level}"
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

function deleteUser(id) {
  if (confirm('Yakin ingin menghapus user ini?')) {
    $.post(base_url + 'admin/delete-user/' + id, function (res) {
      if (res.status) {
        $('#userTable').DataTable().ajax.reload();
        alert(res.message);
      } else {
        alert('Gagal menghapus user.');
      }
    }).fail(function () {
      alert('Terjadi kesalahan pada server.');
    });
  }
}
