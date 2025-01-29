<?php
class ReCaptcha {
    private $site_key;
    private $secret_key;

    public function __construct($site_key, $secret_key) {
        $this->site_key = $site_key;
        $this->secret_key = $secret_key;
    }

    public function display() {
        echo '<script src="https://www.google.com/recaptcha/api.js?render=' . $this->site_key . '"></script>';
        echo '<script>
                grecaptcha.ready(function() {
                    grecaptcha.execute("' . $this->site_key . '", {action: "submit"}).then(function(token) {
                        document.getElementById("recaptcha-response").value = token;
                    });
                });
              </script>';
        echo '<input type="hidden" id="recaptcha-response" name="recaptcha_response">';
    }

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