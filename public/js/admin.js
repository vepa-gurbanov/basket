$(document).ready(function (e) {
    e.preventDefault();

    $('#dataTable').DataTable();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });

});
