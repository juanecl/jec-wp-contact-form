<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Contact_Form_Submissions {
    private static $instance = null;

    private function __construct() {
        // Registrar la acción AJAX
        add_action('wp_ajax_send_follow_up_email', array($this, 'send_follow_up_email'));
    }

    /**
     * Get the singleton instance of the class.
     *
     * @return Contact_Form_Submissions The singleton instance.
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

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

    /**
     * Send follow-up email.
     */
    public function send_follow_up_email() {
        // Verificar nonce para seguridad
        check_ajax_referer('send_follow_up_email_nonce', 'security');

        // Obtener los datos del formulario
        $email = sanitize_email($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = wp_kses_post($_POST['message']);

        // Enviar el correo
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $sent = wp_mail($email, $subject, $message, $headers);

        if ($sent) {
            wp_send_json_success(__('Email sent successfully.', 'jec-contact-form'));
        } else {
            wp_send_json_error(__('Failed to send email.', 'jec-contact-form'));
        }
    }
}

// Instanciar la clase para que el constructor se ejecute
Contact_Form_Submissions::get_instance();
?>