<?php
interface SocialMediaPoster {
    public function fetchMedia();
    public function postContent($content);
}

class FanslyPoster implements SocialMediaPoster {
    private $proxyUrl;
    private $baseUrl;
    private $authToken;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->proxyUrl = 'http://localhost/fansly-poster/proxy.php?url=';
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

    public function postContent($content) {
        $url = $this->baseUrl . "post";
        return $this->makeCurlRequest($url, $content);
    }

    private function makeCurlRequest($url, $postData = null) {
        $url = $this->proxyUrl . urlencode($url);
        $ch = curl_init($url);
        $authToken = $this->authToken;

        var_dump($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization:' . $authToken,
        ]);

        if ($postData) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode >= 400) {
            return json_encode(['error' => 'Curl error: ' . curl_error($ch) . ' HTTP Code: ' . $httpCode]);
        }

        return $response;
    }
}
?>
