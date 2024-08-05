<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $authToken = $input['authToken'];
    $_SESSION['authToken'] = $authToken;
    echo json_encode(['status' => 'success']);
}
?>
