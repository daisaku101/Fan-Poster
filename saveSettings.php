<?php
include 'db.php';
session_start();
$db = new DB();

header('Content-Type: application/json'); // Ensure proper content type for JSON response

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['authToken'])) {
    if (!isset($_SESSION['username'])) {
        echo json_encode(["status" => "error", "message" => "User not logged in"]);
        exit;
    }
    $authToken = $_POST['authToken'];
    $username = $_SESSION['username']; // Assumes username is stored in session
    $result = $db->saveAuthToken($username, $authToken);
    echo $result;
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request or missing authToken"]);
    exit;
}
?>
