<?php
include 'header.php';
require_once 'db.php'; // Make sure db.php is included and has the DB class that connects to your database.

$db = new DB(); // Instantiate your database class.

// Fetch auth token from the database
$authToken = $db->getAuthToken($_SESSION['username']); // Ensure this method exists and correctly fetches the token.

?>

<div class="container">
    <h2>Settings</h2>
    <form action="saveSettings.php" method="post">
        <div class="form-group">
            <label for="auth-token">Auth Token</label>
            <input type="text" id="auth-token" name="authToken" placeholder="Enter Auth Token" value="<?php echo htmlspecialchars($authToken); ?>">
        </div>
        <div class="form-group">
            <button id="save-btn" type="submit" class="btn btn-success">Save Settings</button>
        </div>
        <!-- Status message placeholder -->
        <p id="status-msg"></p>
    </form>
</div>
<script src="js/settings.js"></script> <!-- Ensure this path is correct and the file is correctly implemented -->
<?php include 'footer.php'; ?>
