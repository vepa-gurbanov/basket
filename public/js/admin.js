$(document).ready(function (e) {
    e.preventDefault();

    $('#dataTable').DataTable();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });

});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
    }
});

// function userDelete() {
//     $('a.user-delete').on('click', function () {
//         var button = $(this).attr('id');
//         var form = $('#' + button);
//         $.ajax({
//             type: form.attr('method'),
//             url: form.attr('action'),
//             data: {
//                 'auth_id': form.find('input:hidden[name=auth_id]').first().val(),
//                 'user_id': form.find('input:hidden[name=user_id]').first().val(),
//             },
//             error: function (response) {
//                 console.log('Error:' + response.message)
//             }
//
//         });
//
//     });
// }
//
// userDelete();
