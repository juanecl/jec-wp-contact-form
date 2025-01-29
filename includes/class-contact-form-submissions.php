<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Contact_Form_Submissions {
    /**
     * Get all submissions from the database.
     *
     * @return array The list of submissions.
     */
    public static function get_submissions() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'mpc_submissions';
        return $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created_at DESC" );
    }
}
?>