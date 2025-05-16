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

  if ($('#tableParticipant').length) {
    $('#tableParticipant').DataTable({
      ...tableOptions,
      ajax: base_url + 'operator/getParticipantsAjax',
      columns: [
        {
          data: 'invoice',
          render: function (data) {
            return `<strong>${data}</strong>`;
          },
          orderable: true, // sudah bisa sort karena ada 'data'
        },
        {
          data: null,
          render: function (data) {
            return `${data.firstname} ${data.lastname}`;
          },
          orderable: true, // ⬅️ WAJIB, karena pakai data: null
        },
        {
          data: null,
          render: function (data) {
            return `${data.bib} ${data.chipscode}`;
          },
          orderable: true,
        },
        { data: 'idNumber', orderable: true },
        { data: 'phonenumber', orderable: false },
        { data: 'jerseySize', orderable: false },
        { data: 'race_category', orderable: true },
        {
          data: null,
          render: function (row) {
            const isSelesai =
              row.status_repc === 'Done' && row.processed_End !== null;
            const isSedangProses =
              row.processed_at !== null && row.processed_End === null;

            if (isSelesai) {
              return `<span class="badge bg-success">Selesai</span>`;
            } else if (isSedangProses) {
              return `<span class="badge bg-warning text-dark">Sedang Diproses</span>`;
            } else {
              return `<span class="badge bg-secondary">Belum</span>`;
            }
          },
          orderable: false, // karena pakai render custom
        },
      ],
    });
  }
});
