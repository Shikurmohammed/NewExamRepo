$(function() {
    $('#select-all').on('click', function() {
        $('.user-checkbox').prop('checked', $(this).prop('checked'));
        updateHiddenInput();
    });

    $('.user-checkbox').on('change', function() {
        updateHiddenInput();
    });

    function updateHiddenInput() {
        var selectedIds = [];
        $('.user-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        $('#selected_users').val(selectedIds.join(','));
    }

});
//Select2 for group select box
function addSelectedUsersTo(e) {
    // console.log("Adding :", event)
    e.preventDefault();
    let form_data = $("#user_groups_form").serialize();
    $.ajax({
        url: addToGroupUrl,
        data: form_data,
        method: 'post',
        success: function(response) {
            console.log(response);
            toast().fire({
                icon: "success",
                title: response
            });
        }
    }, {
        error: function(error) {
            console.log(error);
            toast().fire({
                icon: "error",
                title: error
            });
        }
    });



}

function deleteUserFromGroups(e) {
    e.preventDefault();
    let form_data = $("#user_groups_form").serialize();
    console.log(form_data)
    $.ajax({
        url: deletFromGroupUrl,
        data: form_data,
        method: 'delete',
        success: function(response) {
            console.log(response);
            toast().fire({
                icon: "success",
                title: response
            });
        }
    }, {
        error: function(error) {
            console.log(error);
            toast().fire({
                icon: "error",
                title: error
            });
        }
    });



}

function toast() {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        heightAuto: false,
        height: 10,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    return Toast;
}