$(function() {
    // Handle parent checkbox click
    $('.parent-checkbox').on('click', function() {
        var parentId = $(this).data('parent-id');
        var isChecked = $(this).prop('checked');
        // console.log('Parent checkbox clicked: ' + parentId + ', Checked: ' + isChecked);
        $('.child-checkbox[data-parent-id="' + parentId + '"]').prop('checked', isChecked);
        $('.select-all-questions[data-parent-id="' + parentId + '"]').prop('checked', isChecked);
        $("#count_topic").text($('.parent-checkbox:checked').length + " selected");
    });

    // Handle child checkbox click
    $('.child-checkbox').on('click', function() {
        var parentId = $(this).data('parent-id');
        var childId = $(this).val();
        var allChecked = $('.child-checkbox[data-parent-id="' + parentId + '"]').length === $('.child-checkbox[data-parent-id="' + parentId + '"]:checked').length;
        console.log('Child checkbox clicked: ' + childId + ', Parent ID: ' + parentId + ', All Checked: ' + allChecked);
        $('.parent-checkbox[data-parent-id="' + parentId + '"]').prop('checked', allChecked);
        $('.select-all-questions[data-parent-id="' + parentId + '"]').prop('checked', allChecked);
        $("#count_question").text($('.child-checkbox:checked').length + " selected");

    });

    // Handle select all topics checkbox click
    $('#select-all-topics').on('click', function() {
        var isChecked = $(this).prop('checked');
        console.log('Select all topics clicked, Checked: ' + isChecked);
        $('.parent-checkbox, .child-checkbox, .select-all-questions').prop('checked', isChecked);
        $("#count_topic").text($('.parent-checkbox:checked').length + " selected");

    });

    // Handle select all questions checkbox click
    $('.select-all-questions').on('click', function() {
        var parentId = $(this).data('parent-id');
        var isChecked = $(this).prop('checked');
        console.log('Select all questions clicked: ' + parentId + ', Checked: ' + isChecked);
        $('.child-checkbox[data-parent-id="' + parentId + '"]').prop('checked', isChecked);
        $('.parent-checkbox[data-parent-id="' + parentId + '"]').prop('checked', isChecked);

        $("#count_question").text($('.child-checkbox:checked').length + " selected");
    });

    // Uncheck specific select-all-questions and its children based on demand
    $('#uncheck-specific').on('click', function() {
        var excludeId = 'exclude-this-id'; // Replace with the actual ID to exclude
        console.log('Uncheck specific clicked, Exclude ID: ' + excludeId);
        $('.select-all-questions[data-parent-id="' + excludeId + '"]').prop('checked', false);
        $('.child-checkbox[data-parent-id="' + excludeId + '"]').prop('checked', false);
        $('.parent-checkbox[data-parent-id="' + excludeId + '"]').prop('checked', false);
    });

    // Handle form submission via AJAX
    $('#submit-selection').on('click', function() {
        var selectedTopics = $('input[name="selected_topics[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        var selectedQuestions = $('input[name="selected_questions[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        console.log('Selected Topics: ', selectedTopics);
        console.log('Selected Questions: ', selectedQuestions);
        //let test_id = $('select[name="test_id"]').val();
        let test_id = $('#test').val();
        let type_id = $('#type').val();
        let difficulty_id = $('#difficulty').val();
        $.ajax({
            url: question_assignment_url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                selected_topics: selectedTopics,
                selected_questions: selectedQuestions,
                test_id: test_id,
                type_id: type_id,
                difficulty_id: difficulty_id,

            },
            success: function(response) {
                console.log('Success:', response);
                toast().fire({
                    icon: "success",
                    title: response.success
                });
            },
            error: function(xhr, status, error) {
                console.log('Error:', error.error);
                toast().fire({
                    icon: "error",
                    title: error.err
                });
            }
        });
    });
});

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