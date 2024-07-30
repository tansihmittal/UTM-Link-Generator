jQuery(document).ready(function($) {
    // Intercept form submission and generate UTM link asynchronously
    $('#utm-link-generator-form').submit(function(event) {
        event.preventDefault(); // Prevent default form submission

        // Retrieve form data
        var formData = {
            'utm-url': $('#utm-url').val(),
            'utm-source': $('#utm-source').val(),
            'utm-medium': $('#utm-medium').val(),
            'utm-campaign': $('#utm-campaign').val(),
            'utm-term': $('#utm-term').val(),
            'utm-content': $('#utm-content').val(),
        };

        // Send form data via AJAX to generate UTM link
        $.ajax({
            type: 'POST',
            url: utm_link_generator_ajax.ajax_url, // WordPress AJAX URL
            data: {
                action: 'generate_utm_link', // AJAX action name
                formData: formData, // Form data to be sent
            },
            success: function(response) {
                $('#utm-generated-link').html(response); // Display generated link
            }
        });
    });

    // Copy URL button functionality
    $('#copy-utm-url').click(function() {
        var urlField = document.getElementById('utm-generated-link').querySelector('a');
        var tempInput = document.createElement('input');
        tempInput.value = urlField.href;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        alert('URL copied to clipboard!');
    });

    // Clear Fields button functionality
    $('#clear-fields').click(function() {
        $('form#utm-link-generator-form')[0].reset();
        $('#utm-generated-link').empty();
    });
});
