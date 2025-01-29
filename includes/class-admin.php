<?php
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
     * Initializes the class by adding the admin menu action.
     */
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
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
            'Envíos de Contacto',
            'Contact Form Submissions',
            'manage_options',
            'jec-contact-form',
            array($this, 'render_submissions_page'),
            'dashicons-email-alt',
            6
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
}

// Initialize the singleton instance
ContactFormAdmin::get_instance();
?>