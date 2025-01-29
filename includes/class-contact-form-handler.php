<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Contact_Form_Handler {

    public static function handle_form_submission() {
        if ( isset( $_POST['contact_form_nonce'] ) && wp_verify_nonce( $_POST['contact_form_nonce'], 'submit_contact_form' ) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'mpc_submissions';

            $first_name = sanitize_text_field( $_POST['first_name'] );
            $last_name = sanitize_text_field( $_POST['last_name'] );
            $email_address = sanitize_email( $_POST['email_address'] );
            $company_name = sanitize_text_field( $_POST['company_name'] );
            $contact_reason = sanitize_text_field( $_POST['contact_reason'] );
            $other_reason = sanitize_text_field( $_POST['other_reason'] );
            $description_text = sanitize_textarea_field( $_POST['description_text'] );
            $receive_copy = isset( $_POST['receive_copy'] ) ? 1 : 0;
            $nonce_value = sanitize_text_field( $_POST['contact_form_nonce'] );
            $user_ip = $_SERVER['REMOTE_ADDR'];
            $browser_info = $_SERVER['HTTP_USER_AGENT'];
            $form_url = esc_url_raw( $_SERVER['HTTP_REFERER'] );

            $wpdb->insert(
                $table_name,
                array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email_address' => $email_address,
                    'company_name' => $company_name,
                    'contact_reason' => $contact_reason,
                    'other_reason' => $other_reason,
                    'description_text' => $description_text,
                    'receive_copy' => $receive_copy,
                    'nonce_value' => $nonce_value,
                    'user_ip' => $user_ip,
                    'browser_info' => $browser_info,
                    'form_url' => $form_url,
                )
            );
        }
    }
}
?>