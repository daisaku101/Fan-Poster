<?php
require_once 'FanslyPoster.php';
session_start();

if (!isset($_SESSION['authToken'])) {
    echo json_encode(['error' => 'Authentication token not set.']);
    exit;
}

$poster = new FanslyPoster($_SESSION['authToken']);
$postContent = json_decode(file_get_contents('php://input'), true);
$response = $poster->postContent($postContent);

echo $response;
?>
