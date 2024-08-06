<?php
session_start();
include 'db.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$db = new DB();
$authToken = $db->getAuthToken($_SESSION['username']); // This method needs to be defined in your DB class
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <script src="js/settings.js" defer></script>
</head>
<body>
    <header>
        <h1>Settings</h1>
        <nav>
            <a href="index.php" class="btn btn-primary">Home</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </nav>
    </header>
    <div class="container">
        <form id="settings-form">
            <div class="form-group">
                <label for="auth-token">Auth Token</label>
                <input type="text" id="auth-token" placeholder="Enter Auth Token" value="<?php echo htmlspecialchars($authToken); ?>">
            </div>
            <div class="form-group">
                <button type="button" id="save-btn" class="btn btn-success" disabled>Save Settings</button>
            </div>
            <p id="status-msg"></p>
        </form>
    </div>
</body>
</html>
