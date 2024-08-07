<?php
interface SocialMediaPoster {
    public function fetchMedia();
    public function uploadMedia($mediaData, $isBundle);
    public function postContent($postData);
}

class FanslyPoster implements SocialMediaPoster {
    private $proxyUrl;
    private $baseUrl;
    private $authToken;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->proxyUrl = '';
        $this->baseUrl = 'https://apiv3.fansly.com/api/v1/';
        if (!isset($_SESSION['authToken'])) {
            throw new Exception("Authentication token not set in the session.");
        }
        $this->authToken = $_SESSION['authToken'];
    }

    public function fetchMedia() {
        $url = $this->baseUrl . "vaultnew?albumId=654235541616730112&mediaType=&search=&before=0&after=0&ngsw-bypass=true";
        return $this->makeCurlRequest($url);
    }

    public function uploadMedia($mediaData, $isBundle) {
        $url = $isBundle ? $this->baseUrl . "account/media/bundle?ngsw-bypass=true" : $this->baseUrl . "account/media?ngsw-bypass=true";

        // Log the request URL and data
        error_log("Uploading media to: " . $url);
        error_log("Media Data: " . json_encode($mediaData));

        return $this->makeCurlRequest($url, $mediaData);
    }

    public function postContent($postData) {
        $url = $this->baseUrl . "post";
        return $this->makeCurlRequest($url, $postData);
    }

    private function makeCurlRequest($url, $postData = null) {
        $ch = curl_init($url);
        $authToken = $this->authToken;

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization:' . $authToken,
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Host: apiv3.fansly.com'
        ]);

        if ($postData) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($response === false || $httpCode >= 400) {
            error_log("Curl Error: " . $curlError);
            error_log("HTTP Code: " . $httpCode);
            error_log("Response: " . $response);
            return json_encode(['error' => 'Curl error: ' . $curlError . ' HTTP Code: ' . $httpCode, 'response' => $response]);
        }

        return $response;
    }
}
?>
