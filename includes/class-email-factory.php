<?php
require_once 'vendor/autoload.php';
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendGrid\Mail\Mail;

class EmailFactory {
    public static function send_email($provider, $from_email, $from_name, $to_emails, $subject, $content) {
        // Asegurarse de que $to_emails sea un array
        if (!is_array($to_emails)) {
            $to_emails = [$to_emails];
        }

        switch ($provider) {
            case 'sendgrid':
                return self::send_with_sendgrid($from_email, $from_name, $to_emails, $subject, $content);
            case 'brevo':
                return self::send_with_brevo($from_email, $from_name, $to_emails, $subject, $content);
            default:
                throw new Exception('Invalid email provider specified.');
        }
    }

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
            error_log('Failed to send email with SendGrid: ' . $e->getMessage());
            return false;
        }
    }

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
            error_log('Failed to send email with Brevo: ' . $e->getMessage());
            return false;
        }
    }
}
?>