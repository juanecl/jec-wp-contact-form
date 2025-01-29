<?php
/**
 * Plugin Name: Contact Form
 * Description: A contact form plugin with form submission management in the admin area.
 * Version: 1.0
 * Author: Juan E. Chomon Del Campo
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
 * Function to activate the plugin.
 * This function runs when the plugin is activated and is responsible for creating the necessary tables in the database.
 */
register_activation_hook( __FILE__, ['Contact_Form_Activator', 'activate'] );

/**
 * Function to initialize the plugin.
 * This function runs on the 'init' hook of WordPress and is responsible for initializing the contact form.
 */
function mpc_init() {
    // Initialize the contact form
    new Contact_Form();
}
add_action( 'init', 'mpc_init' );
?>