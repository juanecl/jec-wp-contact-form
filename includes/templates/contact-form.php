<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<style>
    .container-contact-form {
        display: block;
        position: relative;
    }
    .container-contact-form::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url(https://www.juane.cl/wp-content/uploads/2025/02/bg-3-scaled.jpg);
        background-size: cover;
        background-position: center;
        opacity: .15; /* Ajusta la opacidad aquí */
        z-index: -1;
    }
</style>
<div class="container-contact-form container-fluid section-padding">
    <h2 class="mb-3 text-left"><?php _e("Let's talk!", 'jec-contact-form'); ?></h2>
    <form id="contact-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" class="needs-validation" novalidate>
        <?php wp_nonce_field('contact_form_nonce', 'nonce_value'); ?>
        <input type="hidden" name="action" value="send_jec_contact_form">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="first_name"><?php _e('First Name', 'jec-contact-form'); ?></label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php _e('Enter your first name', 'jec-contact-form'); ?>" required>
                <div class="invalid-feedback"><?php _e('Please enter your first name.', 'jec-contact-form'); ?></div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="last_name"><?php _e('Last Name', 'jec-contact-form'); ?></label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php _e('Enter your last name', 'jec-contact-form'); ?>" required>
                <div class="invalid-feedback"><?php _e('Please enter your last name.', 'jec-contact-form'); ?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email_address"><?php _e('Email', 'jec-contact-form'); ?></label>
                <input type="email" class="form-control" id="email_address" name="email_address" placeholder="<?php _e('Enter your email address', 'jec-contact-form'); ?>" required>
                <div class="invalid-feedback"><?php _e('Please enter a valid email address.', 'jec-contact-form'); ?></div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="company_name"><?php _e('Company', 'jec-contact-form'); ?></label>
                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="<?php _e('Enter your company name', 'jec-contact-form'); ?>">
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="contact_reason"><?php _e('Reason', 'jec-contact-form'); ?></label>
            <select class="form-control" id="contact_reason" name="contact_reason" required>
                <option value=""><?php _e('Select a reason', 'jec-contact-form'); ?></option>
                <option value="SOS Web"><?php _e('SOS Web', 'jec-contact-form'); ?></option>
                <option value="SOS Wordpress"><?php _e('SOS Wordpress', 'jec-contact-form'); ?></option>
                <option value="Security Consulting"><?php _e('Security Consulting', 'jec-contact-form'); ?></option>
                <option value="Project Evaluation Consulting"><?php _e('Project Evaluation Consulting', 'jec-contact-form'); ?></option>
                <option value="Web Consulting"><?php _e('Web Consulting', 'jec-contact-form'); ?></option>
                <option value="Other"><?php _e('Other', 'jec-contact-form'); ?></option>
            </select>
            <div class="invalid-feedback"><?php _e('Please select a reason.', 'jec-contact-form'); ?></div>
        </div>
        <div class="form-group mb-3" id="reason_other_group" style="display:none;">
            <label for="other_reason"><?php _e('Specify', 'jec-contact-form'); ?></label>
            <input type="text" class="form-control" id="other_reason" name="other_reason" placeholder="<?php _e('Specify your reason', 'jec-contact-form'); ?>">
        </div>
        <div class="form-group mb-3">
            <label for="description_text"><?php _e('Description', 'jec-contact-form'); ?></label>
            <textarea class="form-control" id="description_text" name="description_text" placeholder="<?php _e('Enter a detailed description for your request', 'jec-contact-form'); ?>" required></textarea>
            <div class="invalid-feedback"><?php _e('Please enter a description.', 'jec-contact-form'); ?></div>
        </div>
        <div class="form-group form-check mb-3">
            <input type="checkbox" class="form-check-input" id="receive_copy" name="receive_copy">
            <label class="form-check-label" for="receive_copy"><?php _e('Receive a copy', 'jec-contact-form'); ?></label>
        </div>
        <button type="submit" class="btn btn-primary"><?php _e('Send', 'jec-contact-form'); ?></button>
    </form>
</div>