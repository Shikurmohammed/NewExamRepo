function confirmDelete(e) {
    e.preventDefault();
    var td = e.currentTarget.closest('td');
    var id = td.getAttribute('id');
    console.log(id)
        //href="{{url('delete_test', $test->id)}}"

    $.ajax({
        url: '/delete_test/' + id,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'DELETE',

        },
        success: function(response) {
            console.log(response);
            td.closest('tr').remove();
            toast().fire({
                icon: "success",
                title: response.success
            });
        },
        error: function(err) {
            console.log(err);
            toast().fire({
                icon: "error",
                title: err.error
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