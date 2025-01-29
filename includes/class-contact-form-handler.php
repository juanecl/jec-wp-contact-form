<?php
/**
 * Class Contact_Form_Handler
 *
 * This class handles the processing of contact form submissions.
 */
class Contact_Form_Handler {
    /**
     * Handle the contact form submission.
     *
     * This method validates and processes the form data, and inserts it into the database.
     */
    public static function handle() {
        error_log('Handling contact form submission...');
        try {
            // Verify nonce for security
            if (!check_ajax_referer('contact_form_nonce', 'nonce_value', false)) {
                error_log('Nonce verification failed.');
                throw new Exception('Nonce verification failed.');
            }
            error_log('Nonce verification passed.');

            // Validate and process form data
            $required_fields = ['first_name', 'last_name', 'email_address', 'description_text'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    error_log("Required field $field is missing.");
                    throw new Exception('Please fill in all required fields.');
                }
            }
            error_log('Required fields are present.');

            $first_name = sanitize_text_field($_POST['first_name']);
            $last_name = sanitize_text_field($_POST['last_name']);
            $email_address = sanitize_email($_POST['email_address']);
            if (!is_email($email_address)) {
                error_log('Invalid email address: ' . $email_address);
                throw new Exception('Invalid email address.');
            }
            $company_name = sanitize_text_field($_POST['company_name']);
            $contact_reason = sanitize_text_field($_POST['contact_reason']);
            $other_reason = sanitize_text_field($_POST['other_reason']);
            $description_text = sanitize_textarea_field($_POST['description_text']);
            $receive_copy = isset($_POST['receive_copy']) ? 1 : 0;
            $nonce_value = sanitize_text_field($_POST['nonce_value']);
            $user_ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
            $browser_info = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);

            // Log sanitized data
            error_log('Sanitized data: ' . print_r([
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
            ], true));

            // Insert data into the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'mpc_submissions';
            $result = $wpdb->insert($table_name, [
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
                'created_at' => current_time('mysql'),
            ]);

            if ($result === false) {
                error_log('Failed to save data to the database.');
                throw new Exception('Failed to save data to the database.');
            }

            error_log('Data saved to the database.');

            // Send JSON success response
            wp_send_json_success(['message' => 'Form submitted successfully!']);
        } catch (Exception $e) {
            // Log the error
            error_log('Error in Contact_Form_Handler: ' . $e->getMessage());
            // Send JSON error response
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
}
?>