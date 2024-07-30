<?php
/*
Plugin Name: UTM Link Generator
Description: Add a form to generate UTM links.
Version: 1.0
 * Author: <a href="https://shotcut.in/" target="_blank">Shotcut</a>
*/

// Enqueue CSS and JavaScript files
function utm_link_generator_scripts() {
    wp_enqueue_style('utm-link-generator-style', plugins_url('utm-link-generator-style.css', __FILE__));
    wp_enqueue_script('utm-link-generator-script', plugins_url('utm-link-generator-script.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('utm-link-generator-script', 'utm_link_generator_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'utm_link_generator_scripts');

// Create the form HTML
function utm_link_generator_form() {
    ob_start();
    ?>
    <div id="utm-link-generator-container">
        <form id="utm-link-generator-form" method="post">
            <div class="utm-form-group">
                <label for="utm-url">Website URL*:</label>
                <input type="url" id="utm-url" name="utm-url" required>
            </div>
            <div class="utm-form-group">
                <label for="utm-source">Campaign Source:</label>
                <input type="text" id="utm-source" name="utm-source" placeholder="e.g., google, emailnewsletter2, facebook">
            </div>
            <div class="utm-form-group">
                <label for="utm-medium">Campaign Medium:</label>
                <input type="text" id="utm-medium" name="utm-medium" placeholder="e.g., cpc, banner, email, social">
            </div>
            <div class="utm-form-group">
                <label for="utm-campaign">Campaign Name:</label>
                <input type="text" id="utm-campaign" name="utm-campaign" placeholder="e.g., product, promo code, slogan">
            </div>
            <div class="utm-form-group">
                <label for="utm-term">Campaign Term (optional):</label>
                <input type="text" id="utm-term" name="utm-term" placeholder="Identify the paid keywords">
            </div>
            <div class="utm-form-group">
                <label for="utm-content">Campaign Content (optional):</label>
                <input type="text" id="utm-content" name="utm-content" placeholder="Use to differentiate ads">
            </div>
            <div class="utm-form-group">
                <input type="submit" id="generate-btn" value="Generate UTM Link">
                <button type="button" id="copy-utm-url">Copy URL</button>
                <button type="button" id="clear-fields">Clear Fields</button>
            </div>
        </form>
        <div id="utm-generated-link"></div> <!-- Container for generated link -->
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('utm_link_generator', 'utm_link_generator_form');

// AJAX handler for generating UTM link
add_action('wp_ajax_generate_utm_link', 'generate_utm_link_ajax');

function generate_utm_link_ajax() {
    // Retrieve form data from AJAX request
    $form_data = $_POST['formData'];

    // Generate UTM link
    $utm_link = generate_utm_link($form_data);

    // Output the generated link
    echo '<p class="utm-generated-link">Generated UTM Link: <a href="' . esc_url($utm_link) . '">' . esc_html($utm_link) . '</a></p>';

    wp_die(); // Always include this line to terminate AJAX processing
}

// Generate UTM link function
function generate_utm_link($form_data) {
    $url = esc_url_raw($form_data['utm-url']);
    $utm_params = array();

    // Add parameters only if they have values
    if (!empty($form_data['utm-source'])) {
        $utm_params['utm_source'] = sanitize_text_field($form_data['utm-source']);
    }
    if (!empty($form_data['utm-medium'])) {
        $utm_params['utm_medium'] = sanitize_text_field($form_data['utm-medium']);
    }
    if (!empty($form_data['utm-campaign'])) {
        $utm_params['utm_campaign'] = sanitize_text_field($form_data['utm-campaign']);
    }
    if (!empty($form_data['utm-term'])) {
        $utm_params['utm_term'] = sanitize_text_field($form_data['utm-term']);
    }
    if (!empty($form_data['utm-content'])) {
        $utm_params['utm_content'] = sanitize_text_field($form_data['utm-content']);
    }

    // Construct the URL with parameters
    return add_query_arg($utm_params, $url);
}
