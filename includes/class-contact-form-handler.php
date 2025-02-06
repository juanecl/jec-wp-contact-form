<?php
/**
 * Class Contact_Form_Handler
 *
 * This class handles the processing of contact form submissions.
 */
require_once 'class-email-factory.php';
require_once 'class-recaptcha.php';

class Contact_Form_Handler {
    /**
     * Handle the contact form submission.
     *
     * This method validates and processes the form data, and inserts it into the database.
     */
    public static function handle() {
        try {
            // Verify nonce for security
            if (!check_ajax_referer('contact_form_nonce', 'nonce_value', false)) {
                throw new Exception(__('Nonce verification failed.', 'jec-contact-form'));
            }

            // Validate and process form data
            $required_fields = ['first_name', 'last_name', 'email_address', 'description_text', 'recaptcha_response'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception(__('Please fill in all required fields.', 'jec-contact-form'));
                }
            }

            $first_name = sanitize_text_field($_POST['first_name']);
            $last_name = sanitize_text_field($_POST['last_name']);
            $email_address = sanitize_email($_POST['email_address']);
            if (!is_email($email_address)) {
                throw new Exception(__('Invalid email address.', 'jec-contact-form'));
            }
            $company_name = sanitize_text_field($_POST['company_name']);
            $contact_reason = sanitize_text_field($_POST['contact_reason']);
            $other_reason = sanitize_text_field($_POST['other_reason']);
            $description_text = sanitize_textarea_field($_POST['description_text']);
            $receive_copy = isset($_POST['receive_copy']) ? 1 : 0;
            $nonce_value = sanitize_text_field($_POST['nonce_value']);
            $user_ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
            $browser_info = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
            $form_url = sanitize_text_field($_SERVER['HTTP_REFERER']);
            $recaptcha_response = sanitize_text_field($_POST['recaptcha_response']);

            // Verify reCAPTCHA
            $recaptcha = new ReCaptcha();
            if (!$recaptcha->verify($recaptcha_response)) {
                error_log(__('reCAPTCHA verification failed.', 'jec-contact-form'));
                error_log(__('Token: ', 'jec-contact-form') . $recaptcha_response);
                error_log(__('IP: ', 'jec-contact-form') . $user_ip);
                error_log(__('User Agent: ', 'jec-contact-form') . $browser_info);
                error_log(__('Form URL: ', 'jec-contact-form') . $form_url);
                error_log(__('Email Address: ', 'jec-contact-form') . $email_address);
                error_log(__('First Name: ', 'jec-contact-form') . $first_name);
                error_log(__('Last Name: ', 'jec-contact-form') . $last_name);
                error_log(__('Company Name: ', 'jec-contact-form') . $company_name);
                error_log(__('Contact Reason: ', 'jec-contact-form') . $contact_reason);
                error_log(__('Other Reason: ', 'jec-contact-form') . $other_reason);
                error_log(__('Description: ', 'jec-contact-form') . $description_text);
                error_log(__('Receive Copy: ', 'jec-contact-form') . $receive_copy);

                wp_send_json_error(__('reCAPTCHA verification failed. Please try again.', 'jec-contact-form'));
            } 

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
                'form_url' => $form_url,
                'created_at' => current_time('mysql'),
            ]);

            if ($result === false) {
                wp_send_json_error(__('Failed to save data to the database.', 'jec-contact-form'));
            }

            // Send emails
            self::send_emails($first_name, $last_name, $email_address, $company_name, $contact_reason, $other_reason, $description_text, $receive_copy);

            // Send JSON success response
            wp_send_json_success(['message' => __('Form submitted successfully!', 'jec-contact-form')]);
        } catch (Exception $e) {
            // Log the error
            error_log(__('Error in Contact_Form_Handler: ', 'jec-contact-form') . $e->getMessage());
            // Send JSON error response
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }

    /**
     * Send emails using the configured provider.
     *
     * @param string $first_name
     * @param string $last_name
     * @param string $email_address
     * @param string $company_name
     * @param string $contact_reason
     * @param string $other_reason
     * @param string $description_text
     * @param bool $receive_copy
     */
    private static function send_emails($first_name, $last_name, $email_address, $company_name, $contact_reason, $other_reason, $description_text, $receive_copy) {
        $provider = get_option('email_provider');
        $from_email = 'no-reply@juane.cl';
        $from_name = 'Contact Form';

        // Email content
        $email_content = "
            " . __('First Name', 'jec-contact-form') . ": $first_name\n
            " . __('Last Name', 'jec-contact-form') . ": $last_name\n
            " . __('Email Address', 'jec-contact-form') . ": $email_address\n
            " . __('Company Name', 'jec-contact-form') . ": $company_name\n
            " . __('Contact Reason', 'jec-contact-form') . ": $contact_reason\n
            " . __('Other Reason', 'jec-contact-form') . ": $other_reason\n
            " . __('Description', 'jec-contact-form') . ": $description_text\n
        ";

        // Send email to admin
        EmailFactory::send_email($provider, $from_email, $from_name, 'hola@juane.cl', __('New Contact Form Submission', 'jec-contact-form'), $email_content);

        // Send confirmation email to user
        EmailFactory::send_email($provider, $from_email, $from_name, $email_address, __('Your Contact Form Submission', 'jec-contact-form'), __('Thank you for your submission. We have received your message and will get back to you shortly.', 'jec-contact-form'));

        // Send copy of submission to user if requested
        if ($receive_copy) {
            EmailFactory::send_email($provider, $from_email, $from_name, $email_address, __('Copy of Your Contact Form Submission', 'jec-contact-form'), $email_content);
        }
    }
}
?>