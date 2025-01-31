<?php
/**
 * Class ReCaptcha
 *
 * This class handles the integration of Google reCAPTCHA v3 for the contact form plugin.
 */
class ReCaptcha {
    /**
     * The site key for reCAPTCHA.
     *
     * @var string
     */
    private $site_key;

    /**
     * The secret key for reCAPTCHA.
     *
     * @var string
     */
    private $secret_key;

    /**
     * Constructor method.
     * 
     * Initializes the class by retrieving the reCAPTCHA site key and secret key from the WordPress options.
     */
    public function __construct() {
        $this->site_key = get_option('recaptcha_site_key');
        $this->secret_key = get_option('recaptcha_secret_key');
    }

    /**
     * Display reCAPTCHA.
     * 
     * Outputs the necessary JavaScript to render the reCAPTCHA widget and generate a token.
     */
    public function display() {
        echo '<script src="https://www.google.com/recaptcha/api.js?render=' . esc_attr($this->site_key) . '"></script>';
        echo '<script>
                grecaptcha.ready(function() {
                    grecaptcha.execute("' . esc_attr($this->site_key) . '", {action: "submit"}).then(function(token) {
                        document.getElementById("recaptcha-response").value = token;
                    });
                });
              </script>';
        echo '<input type="hidden" id="recaptcha-response" name="recaptcha_response">';
    }

    /**
     * Verify reCAPTCHA.
     * 
     * Verifies the reCAPTCHA token with Google's reCAPTCHA API.
     *
     * @param string $token The reCAPTCHA token to verify.
     * @return bool True if the token is valid, false otherwise.
     */
    public function verify($token) {
        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $this->secret_key,
                'response' => $token,
            ],
        ]);

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body);

        return $result->success;
    }
}
?>