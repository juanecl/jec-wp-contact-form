<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.juane.cl
 * @since      1.0.0
 *
 * @package    Jec_Contact_Form
 * @subpackage Jec_Contact_Form/includes
 */

class Contact_Form_Activator {

    /**
     * Activate the plugin.
     *
     * This method creates the necessary database table for storing contact form submissions.
     */
    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mpc_submissions';
        $charset_collate = $wpdb->get_charset_collate();

        // Drop the table if it exists
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // Create the table
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name tinytext NOT NULL,
            last_name tinytext NOT NULL,
            email_address varchar(100) NOT NULL,
            company_name tinytext,
            contact_reason tinytext NOT NULL,
            other_reason tinytext,
            description_text text NOT NULL,
            receive_copy boolean DEFAULT false,
            nonce_value varchar(100) NOT NULL,
            user_ip varchar(45) NOT NULL,
            browser_info text NOT NULL,
            form_url text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}
?>