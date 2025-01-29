<?php
/**
 * Class Contact_Form
 *
 * This class handles the registration of the contact form shortcode and AJAX actions.
 */
class Contact_Form {
    /**
     * Constructor.
     *
     * This method initializes the class by registering the shortcode and AJAX actions.
     */
    public function __construct() {
        add_shortcode('jec-contact-form', [$this, 'render_form']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_send_jec_contact_form', [$this, 'handle_form_submission']);
        add_action('wp_ajax_nopriv_send_jec_contact_form', [$this, 'handle_form_submission']);
    }

    /**
     * Enqueue scripts.
     *
     * This method enqueues the necessary scripts for the contact form.
     */
    public function enqueue_scripts() {
        Contact_Form_Scripts::enqueue();
    }

    /**
     * Render the form.
     *
     * This method renders the contact form using the Contact_Form_Renderer class.
     *
     * @return string The rendered form HTML.
     */
    public function render_form() {
        return Contact_Form_Renderer::render();
    }

    /**
     * Handle form submission.
     *
     * This method handles the AJAX form submission by delegating to the Contact_Form_Handler class.
     */
    public function handle_form_submission() {
        Contact_Form_Handler::handle();
    }
}
?>