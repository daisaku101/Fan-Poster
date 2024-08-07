<?php
$url = "https://apiv3.fansly.com/api/v1/post";
$data = [
    "content" => "My baby delicious",
    "fypFlags" => 0,
    "inReplyTo" => null,
    "quotedPostId" => null,
    "attachments" => [
        [
            "contentId" => "677072063369846784",
            "contentType" => 1,
            "pos" => 0
        ]
    ],
    "scheduledFor" => 0,
    "expiresAt" => 0,
    "postReplyPermissionFlags" => [],
    "pinned" => 0,
    "wallIds" => []
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data)),
    'Authorization: Njc2NDI4NTE0NjA1NDEyMzUzOjE6Mjo1NTliMDY0ZWU1OGQ3ZWM1YjU0OTEwZWQ5NDFhNzM',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
    'Host: apiv3.fansly.com'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (only for debugging, not recommended for production)

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

echo json_encode(['response' => $response, 'httpCode' => $httpCode, 'error' => $curlError]);
?>
