<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // OPTIONS request is for CORS preflight check
}

if (!isset($_GET['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing target URL']);
    exit;
}

$targetUrl = urldecode($_GET['url']);
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $targetUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Njc2NDI4NTE0NjA1NDEyMzUzOjE6Mjo1NTliMDY0ZWU1OGQ3ZWM1YjU0OTEwZWQ5NDFhNzM',
    'Content-Type: application/json',
    'Referer: https://fansly.com/', // Adding Referer header to mimic request coming from fansly.com
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36' // Adding a common User-Agent header
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification

$response = curl_exec($ch);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch media', 'details' => curl_error($ch)]);
    exit;
}

http_response_code(curl_getinfo($ch, CURLINFO_HTTP_CODE));
curl_close($ch);

header('Content-Type: application/json');
echo $response;
