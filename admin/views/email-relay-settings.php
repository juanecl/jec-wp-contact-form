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
        <?php submit_button(); ?>
    </form>
</div>