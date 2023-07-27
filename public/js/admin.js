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

// in users page,
function selectedUsers() {
    let users = [];
    let usersCached = [];
    let checkAllBtn = $('input:checkbox#UsersCheckAll');
    let userInput = $('input:checkbox[name=user_ids]');
    checkAllBtn.on('change', function () {
        if ($(this).prop('checked')) {
            userInput.prop('checked', true);
            users = [];
            userInput.each(function () {
                usersCached.push($(this).attr('id'));
                users.push($(this).attr('id'));
            })
        } else {
            userInput.prop('checked', false);
            users = [];
        }
        console.log('User IDs: ' + users);
    })

    userInput.on('change', function() {
        let user = $(this);
        if (users.includes(user.attr('id'))) {
            users.splice(users.indexOf(user.attr('id')), 1);
        } else {
            users.push(user.attr('id'));
        }

        users.every(function (element, index) {
            if (!$.inArray(element, usersCached)) {
                checkAllBtn.prop('checked', false);
            }
            checkAllBtn.prop('checked', true);
        });

        console.log('User IDs: ' + users);
    });
}
selectedUsers();
