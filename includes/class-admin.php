<?php
/**
 * Class ContactFormAdmin
 *
 * This class handles the administration of the contact form plugin, including menu items, settings, and scripts.
 */
class ContactFormAdmin {
    /**
     * The single instance of the class.
     *
     * @var ContactFormAdmin
     */
    private static $instance = null;

    /**
     * Constructor method.
     * 
     * Initializes the class by adding the admin menu, registering settings, and enqueuing scripts.
     */
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Get the single instance of the class.
     *
     * @return ContactFormAdmin
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add Admin Menu.
     * 
     * Adds a new menu item in the WordPress admin dashboard for managing contact form submissions.
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Contact Form Submissions', 'jec-contact-form'),
            __('Contact Form Submissions', 'jec-contact-form'),
            'manage_options',
            'jec-contact-form',
            array($this, 'render_submissions_page'),
            'dashicons-email-alt',
            6
        );

        add_submenu_page(
            'jec-contact-form',
            __('Email Relay Settings', 'jec-contact-form'),
            __('Email Relay Settings', 'jec-contact-form'),
            'manage_options',
            'email-relay-settings',
            array($this, 'render_email_relay_settings_page')
        );
    }

    /**
     * Render Submissions Page.
     * 
     * Includes the template file to display the contact form submissions in the admin dashboard.
     */
    public function render_submissions_page() {
        include_once plugin_dir_path(__FILE__) . '../admin/views/submissions.php';
    }

    /**
     * Render Email Relay Settings Page.
     * 
     * Includes the template file to display the email relay settings in the admin dashboard.
     */
    public function render_email_relay_settings_page() {
        include_once plugin_dir_path(__FILE__) . '../admin/views/email-relay-settings.php';
    }

    /**
     * Register Settings.
     * 
     * Registers the settings for the email relay configuration and reCAPTCHA keys.
     */
    public function register_settings() {
        // Register email relay settings
        register_setting('email_relay_settings', 'email_provider');
        register_setting('email_relay_settings', 'sendgrid_api_key');
        register_setting('email_relay_settings', 'brevo_api_key');
        register_setting('email_relay_settings', 'from_email');
        register_setting('email_relay_settings', 'from_name');
        register_setting('email_relay_settings', 'reply_to_email');
        register_setting('email_relay_settings', 'reply_to_name');

        // Register reCAPTCHA settings
        register_setting('general', 'recaptcha_site_key');
        register_setting('general', 'recaptcha_secret_key');

        // Add settings fields for reCAPTCHA
        add_settings_field(
            'recaptcha_site_key',
            __('reCAPTCHA Site Key', 'jec-contact-form'),
            array($this, 'recaptcha_site_key_callback'),
            'general'
        );

        add_settings_field(
            'recaptcha_secret_key',
            __('reCAPTCHA Secret Key', 'jec-contact-form'),
            array($this, 'recaptcha_secret_key_callback'),
            'general'
        );
    }

    /**
     * Callback for reCAPTCHA Site Key field.
     * 
     * Displays the input field for the reCAPTCHA Site Key.
     */
    public function recaptcha_site_key_callback() {
        $value = get_option('recaptcha_site_key', '');
        echo '<input type="text" id="recaptcha_site_key" name="recaptcha_site_key" value="' . esc_attr($value) . '" />';
    }

    /**
     * Callback for reCAPTCHA Secret Key field.
     * 
     * Displays the input field for the reCAPTCHA Secret Key.
     */
    public function recaptcha_secret_key_callback() {
        $value = get_option('recaptcha_secret_key', '');
        echo '<input type="text" id="recaptcha_secret_key" name="recaptcha_secret_key" value="' . esc_attr($value) . '" />';
    }

    /**
     * Enqueue Admin Scripts and Styles.
     * 
     * Enqueues the necessary scripts and styles for the admin pages.
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook === 'toplevel_page_jec-contact-form' || $hook === 'contact-form-submissions_page_email-relay-settings') {
            wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
            wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array(), null, true);
            wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
            wp_enqueue_script('jec-contact-form-submissions', plugin_dir_url(__FILE__) . '../admin/js/submissions.js', array('jquery', 'bootstrap-js'), null, true);
            wp_enqueue_style('jec-contact-form-submissions', plugin_dir_url(__FILE__) . '../admin/css/submissions.css');
            wp_enqueue_style('email-relay-settings-css', plugin_dir_url(__FILE__) . '../admin/css/email-relay-settings.css');
            wp_enqueue_script('email-relay-settings-js', plugin_dir_url(__FILE__) . '../admin/js/email-relay-settings.js', array('jquery'), false, true);
        }
    }
}

// Initialize the singleton instance
ContactFormAdmin::get_instance();
?>