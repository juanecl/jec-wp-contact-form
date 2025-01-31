<?php
class Contact_Form_Scripts {
    public static function enqueue() {
        wp_enqueue_script('jec-contact-form-scripts', plugin_dir_url(__FILE__) . '../assets/js/scripts.js', ['jquery'], null, true);
        wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        // Include reCAPTCHA v3 script here
        $site_key = get_option('recaptcha_site_key', '');
        if (!empty($site_key)) {
            wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . esc_attr($site_key), [], null, true);
        }
    }
}
?>