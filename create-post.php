<?php
session_start();

if (!isset($_SESSION['authToken'])) {
    echo json_encode(['error' => 'Authentication token not set.']);
    exit;
}

$authToken = $_SESSION['authToken'];
$postContent = json_decode(file_get_contents('php://input'), true);

$ch = curl_init('https://apiv3.fansly.com/api/v1/post?ngsw-bypass=true');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postContent));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: ' . $authToken,
]);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode >= 400) {
        echo json_encode(['error' => 'HTTP error: ' . $httpCode . ' - ' . $response]);
    } else {
        echo $response;
    }
}

curl_close($ch);
?>
