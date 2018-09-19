<?php

if (!function_exists('hash_equals')) {
    defined('USE_MB_STRING') or define('USE_MB_STRING', function_exists('mb_strlen'));

    function hash_equals($knownString, $userString)
    {
        $strlen = function ($string) {
            if (USE_MB_STRING) {
                return mb_strlen($string, '8bit');
            }

            return strlen($string);
        };

        // Compare string lengths
        if (($length = $strlen($knownString)) !== $strlen($userString)) {
            return false;
        }

        $diff = 0;

        // Calculate differences
        for ($i = 0; $i < $length; $i++) {
            $diff |= ord($knownString[$i]) ^ ord($userString[$i]);
        }
        return $diff === 0;
    }
}

class LINEBotMini
{
    public function __construct()
    {
        $channelAccessToken = 'Your channelAccessToken Here';
        $channelSecret = 'Your channelSecret Here';
        $this->channelAccessToken = $channelAccessToken;
        $this->channelSecret = $channelSecret;
    }

    public function parseEvents()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            error_log("Method not allowed");
            exit();
        }

        $entityBody = file_get_contents('php://input');

        if (strlen($entityBody) === 0) {
            http_response_code(400);
            error_log("Missing request body");
            exit();
        }

        if (!hash_equals($this->sign($entityBody), $_SERVER['HTTP_X_LINE_SIGNATURE'])) {
            http_response_code(400);
            error_log("Invalid signature value");
            exit();
        }

        $data = json_decode($entityBody, true);
        if (!isset($data['events'])) {
            http_response_code(400);
            error_log("Invalid request body: missing events property");
            exit();
        }
        return $data['events'];
    }

    public function replyMessage($message)
    {
        $api_url = 'https://api.line.me/v2/bot/message/reply';
        $this->sendMessage($message, $api_url);
    }

    public function pushMessage($message)
    {
        $api_url = 'https://api.line.me/v2/bot/message/push';
        $this->sendMessage($message, $api_url);
    }

    public function multicastMessage($message)
    {
        $api_url = 'https://api.line.me/v2/bot/message/multicast';
        $this->sendMessage($message, $api_url);
    }

    private function sendMessage($message, $api_url)
    {
        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer ' . $this->channelAccessToken,
        );

        $context = stream_context_create(array(
            "http" => array(
                "method" => "POST",
                "header" => implode("\r\n", $header),
                "content" => json_encode($message),
            ),
        ));

        $response = file_get_contents($api_url, false, $context);
        if (strpos($http_response_header[0], '200') === false) {
            http_response_code(500);
            error_log("Request failed: " . $response);
        }
    }

    public function getProfile($userId)
    {
        $api_url = 'https://api.line.me/v2/bot/profile/' . $userId;

        $context = stream_context_create(array(
            "http" => array(
                "method" => "GET",
                "header" => 'Authorization: Bearer ' . $this->channelAccessToken,
            ),
        ));

        $response = file_get_contents($api_url, false, $context);
        if (strpos($http_response_header[0], '200') === false) {
            http_response_code(500);
            error_log("Request failed: " . $response);
        }

        $profile = json_decode($response, true);
        return $profile;
    }

    private function sign($body)
    {
        $hash = hash_hmac('sha256', $body, $this->channelSecret, true);
        $signature = base64_encode($hash);
        return $signature;
    }
}

?>
