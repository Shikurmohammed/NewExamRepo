$(document).ready(function() {
    $('#module').on('change', function() {
        var moduleId = $(this).val(); // Get selected module ID
        console.log('module id::', moduleId)
        $('#topic').html('<option value="">Loading...</option>'); // Show loading
        if (moduleId) {
            // Send AJAX request
            $.ajax({
                url: getTopicsUrl,
                method: 'get',
                data: {
                    module_id: moduleId,
                },
                success: function(data) {
                    // Populate the topic select box
                    $('#topic').html('<option value="">Select Topic</option>');
                    $.each(data, function(key, topic) {
                        $('#topic').append('<option value="' + topic.id + '">' + topic.name + '</option>');
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
    $('#topic').on('change', function() {
        var topicId = $(this).val();

        $('question').html('<option value="">Loading</option>');
        if (topicId) {
            console.log('topic id::', topicId)
                //Send ajax to route controller
            $.ajax({
                url: getQuestionsUrl,
                method: 'get',
                data: {
                    topic_id: topicId
                },
                success: function(data) {
                    //alert("success");
                    $('#question').html('<option value="">Select question</option>');
                    $.each(data, function(key, question) {
                        $('#question').append('<option value="' + question.id + '">' + question.description + '</option>');
                    });
                },
                error: function() {
                    // alert('fail');
                    $('#question').html('<option value="">Select question</option>');
                }
            });
        } else {
            $('question').html('<option value="">Select question...</option>');
        }
    });
});