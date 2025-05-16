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

  // Modal event (Bootstrap 5)
  $('#editModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    $('#modal-id').val(button.data('id'));
    $('#modal-field').val(button.data('field'));
    $('#modal-value').val(button.data('value'));
    $('#modal-label').text('Edit ' + button.data('label'));
  });

  // Bootstrap validation
  (function () {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(function (form) {
      form.addEventListener(
        'submit',
        function (e) {
          if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
          }
          form.classList.add('was-validated');
        },
        false,
      );
    });
  })();
});
