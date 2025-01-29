<div class="wrap">
    <h1><?php _e('Email Relay Settings', 'jec-contact-form'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('email_relay_settings');
        do_settings_sections('email_relay_settings');
        ?>
        <div class="form-group">
            <label for="email_provider"><?php _e('Email Provider', 'jec-contact-form'); ?></label>
            <select id="email_provider" name="email_provider" class="form-control">
                <option value="sendgrid" <?php selected(get_option('email_provider'), 'sendgrid'); ?>><?php _e('SendGrid', 'jec-contact-form'); ?></option>
                <option value="brevo" <?php selected(get_option('email_provider'), 'brevo'); ?>><?php _e('Brevo', 'jec-contact-form'); ?></option>
            </select>
        </div>
        <div id="sendgrid-settings" class="form-group">
            <label for="sendgrid_api_key"><?php _e('SendGrid API Key', 'jec-contact-form'); ?></label>
            <input type="text" id="sendgrid_api_key" name="sendgrid_api_key" class="form-control" value="<?php echo esc_attr(get_option('sendgrid_api_key')); ?>" />
        </div>
        <div id="brevo-settings" class="form-group">
            <label for="brevo_api_key"><?php _e('Brevo API Key', 'jec-contact-form'); ?></label>
            <input type="text" id="brevo_api_key" name="brevo_api_key" class="form-control" value="<?php echo esc_attr(get_option('brevo_api_key')); ?>" />
        </div>
        <div class="form-group">
            <label for="from_email"><?php _e('From Email', 'jec-contact-form'); ?></label>
            <input type="email" id="from_email" name="from_email" class="form-control" value="<?php echo esc_attr(get_option('from_email')); ?>" />
        </div>
        <div class="form-group">
            <label for="from_name"><?php _e('From Name', 'jec-contact-form'); ?></label>
            <input type="text" id="from_name" name="from_name" class="form-control" value="<?php echo esc_attr(get_option('from_name')); ?>" />
        </div>
        <div class="form-group">
            <label for="reply_to_email"><?php _e('Reply-To Email', 'jec-contact-form'); ?></label>
            <input type="email" id="reply_to_email" name="reply_to_email" class="form-control" value="<?php echo esc_attr(get_option('reply_to_email')); ?>" />
        </div>
        <div class="form-group">
            <label for="reply_to_name"><?php _e('Reply-To Name', 'jec-contact-form'); ?></label>
            <input type="text" id="reply_to_name" name="reply_to_name" class="form-control" value="<?php echo esc_attr(get_option('reply_to_name')); ?>" />
        </div>
        <?php submit_button(); ?>
    </form>
</div>