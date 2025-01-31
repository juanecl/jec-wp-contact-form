<?php
require_once 'vendor/autoload.php';
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendGrid\Mail\Mail;

/**
 * Class EmailFactory
 *
 * This class handles the sending of emails using different email providers.
 */
class EmailFactory {
    /**
     * Send an email using the specified provider.
     *
     * @param string $provider The email provider to use ('sendgrid' or 'brevo').
     * @param string $from_email The sender's email address.
     * @param string $from_name The sender's name.
     * @param array|string $to_emails The recipient(s) email address(es).
     * @param string $subject The subject of the email.
     * @param string $content The content of the email.
     * @return mixed The response from the email provider or false on failure.
     * @throws Exception If an invalid email provider is specified.
     */
    public static function send_email($provider, $from_email, $from_name, $to_emails, $subject, $content) {
        // Ensure $to_emails is an array
        if (!is_array($to_emails)) {
            $to_emails = [$to_emails];
        }

        switch ($provider) {
            case 'sendgrid':
                return self::send_with_sendgrid($from_email, $from_name, $to_emails, $subject, $content);
            case 'brevo':
                return self::send_with_brevo($from_email, $from_name, $to_emails, $subject, $content);
            default:
                throw new Exception(__('Invalid email provider specified.', 'jec-contact-form'));
        }
    }

    /**
     * Send an email using SendGrid.
     *
     * @param string $from_email The sender's email address.
     * @param string $from_name The sender's name.
     * @param array $to_emails The recipient(s) email address(es).
     * @param string $subject The subject of the email.
     * @param string $content The content of the email.
     * @return mixed The response status code from SendGrid or false on failure.
     */
    private static function send_with_sendgrid($from_email, $from_name, $to_emails, $subject, $content) {
        $sendgrid_api_key = get_option('sendgrid_api_key');
        $email = new Mail();
        $email->setFrom(get_option('from_email'), get_option('from_name'));
        $email->setSubject($subject);
        foreach ($to_emails as $to_email) {
            $email->addTo($to_email);
        }
        $email->addContent('text/plain', $content);
        $email->setReplyTo(get_option('reply_to_email'), get_option('reply_to_name'));

        $sendgrid = new \SendGrid($sendgrid_api_key);
        try {
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (Exception $e) {
            error_log(__('Failed to send email with SendGrid: ', 'jec-contact-form') . $e->getMessage());
            return false;
        }
    }

    /**
     * Send an email using Brevo (SendinBlue).
     *
     * @param string $from_email The sender's email address.
     * @param string $from_name The sender's name.
     * @param array $to_emails The recipient(s) email address(es).
     * @param string $subject The subject of the email.
     * @param string $content The content of the email.
     * @return mixed The message ID from Brevo or false on failure.
     */
    private static function send_with_brevo($from_email, $from_name, $to_emails, $subject, $content) {
        $brevo_api_key = get_option('brevo_api_key');
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $brevo_api_key);
        $apiInstance = new TransactionalEmailsApi(new GuzzleHttp\Client(), $config);

        $email = new SendSmtpEmail([
            'subject' => $subject,
            'sender' => ['email' => get_option('from_email'), 'name' => get_option('from_name')],
            'replyTo' => ['email' => get_option('reply_to_email'), 'name' => get_option('reply_to_name')],
            'to' => array_map(function($to_email) {
                return ['email' => $to_email];
            }, $to_emails),
            'htmlContent' => nl2br($content),
        ]);

        try {
            $response = $apiInstance->sendTransacEmail($email);
            return $response->getMessageId();
        } catch (Exception $e) {
            error_log(__('Failed to send email with Brevo: ', 'jec-contact-form') . $e->getMessage());
            return false;
        }
    }
}
?>