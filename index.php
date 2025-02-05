<?php
/*
Plugin Name: jec-contact-form
Plugin URI: https://github.com/juanecl/jec-wp-contact-form
Description: A contact form plugin with form submission management in the admin area.
Version: 1.0
Author: Juan Enrique Chomon Del Campo
Author URI: https://www.juane.cl
License: GPL2
Text Domain: jec-contact-form
Domain Path: /languages
*/

// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants for plugin paths
define( 'MPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MPC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include necessary files
require_once MPC_PLUGIN_DIR . 'includes/class-admin.php'; // Class for plugin administration
require_once MPC_PLUGIN_DIR . 'includes/class-contact-form.php'; // Main class for the contact form
require_once MPC_PLUGIN_DIR . 'includes/class-contact-form-scripts.php'; // Class for managing scripts and styles
require_once MPC_PLUGIN_DIR . 'includes/class-contact-form-renderer.php'; // Class for rendering the form
require_once MPC_PLUGIN_DIR . 'includes/class-contact-form-handler.php'; // Class for handling form submission
require_once MPC_PLUGIN_DIR . 'includes/class-contact-form-activator.php'; // Class for plugin activation

/**
 * Main plugin class.
 */
class JEC_Contact_Form {
    /**
     * The single instance of the class.
     *
     * @var JEC_Contact_Form
     */
    private static $instance = null;

    /**
     * Constructor method.
     * 
     * Initializes the plugin by setting up hooks and loading text domain.
     */
    private function __construct() {
        // Load plugin text domain for translations
        add_action('plugins_loaded', array($this, 'load_textdomain'));

        // Initialize the contact form
        add_action('init', array('Contact_Form', 'get_instance'));

        // Register activation hook
        register_activation_hook(__FILE__, array('Contact_Form_Activator', 'activate'));
    }

    /**
     * Get the single instance of the class.
     *
     * @return JEC_Contact_Form
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load plugin text domain for translations.
     */
    public function load_textdomain() {
        load_plugin_textdomain('jec-contact-form', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}

// Initialize the singleton instance
JEC_Contact_Form::get_instance();
?>