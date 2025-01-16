$(document).ready(function() {
    $('#module').on('change', function() {
        var moduleId = $(this).val(); // Get selected module ID
        $('#topic').html('<option value="">Loading...</option>'); // Show loading
        if (moduleId) {
            // Send AJAX request
            $.ajax({
                url: "{{ route('get.topics') }}",
                method: 'get',
                data: {
                    module_id: moduleId,
                },
                success: function(data) {
                    // Populate the topic select box
                    $('#topic').html('<option value="">Select Topic</option>');
                    $.each(data, function(key, topic) {
                        $('#topic').append('<option value="' + topic.id + '">' +
                            topic.name + '</option>');
                    });
                },
                error: function() {
                    alert('Error loading topics');
                    $('#topic').html('<option value="">Select Topic</option>');
                }
            });
        } else {
            // If no module is selected, reset topics
            $('#topic').html('<option value="">Select Topic</option>');
        }
    });

});