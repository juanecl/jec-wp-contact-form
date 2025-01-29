<?php
class Contact_Form_Renderer {
    /**
     * Register the shortcode.
     */
    public static function register_shortcode() {
        add_shortcode('jec-contact-form', [self::class, 'render_shortcode']);
    }

    /**
     * Render the contact form shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string The contact form HTML.
     */
    public static function render_shortcode($atts) {
        $atts = shortcode_atts([
            'id' => 'contact-form',
        ], $atts, 'jec-contact-form');

        return self::render($atts['id']);
    }

    /**
     * Render the contact form.
     *
     * This method outputs the contact form HTML and enqueues the necessary scripts.
     *
     * @param string $id The ID of the form.
     * @return string The contact form HTML.
     */
    public static function render($id = 'contact-form') {
        // Enqueue the JavaScript file for form validation and dynamic fields
        wp_enqueue_script('jec-contact-form-js', plugin_dir_url(__FILE__) . '../assets/js/contact-form.js', array('jquery'), null, true);

        // Start output buffering
        ob_start();

        // Include the contact form template
        ?>
        <div class="bg-dark-muted py-1">
        <?php
            include plugin_dir_path(__FILE__) . 'templates/contact-form.php';
        ?>
        </div>
        <?php

        // Return the buffered content
        return ob_get_clean();
    }
}

// Register the shortcode
Contact_Form_Renderer::register_shortcode();
?>