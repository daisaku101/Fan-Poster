<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fansly Post Creator</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/media.css">
</head>
<body>
    <header>
        <h1>Fansly Post Creator</h1>
        <nav>
            <a href="index.php" class="btn btn-primary">Home</a>
            <a href="settings.php" class="btn btn-primary">Settings</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </nav>
    </header>
