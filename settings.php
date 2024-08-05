<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fansly_poster";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $new_username = $_POST['username'];
    $auth_token = $_POST['auth_token'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, auth_token = ?, password = ? WHERE username = ?");
    $stmt->bind_param("ssssss", $first_name, $last_name, $new_username, $auth_token, $hashed_password, $username);

    if ($stmt->execute()) {
        $_SESSION['username'] = $new_username;
        $message = "Settings updated successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch user information
$stmt = $conn->prepare("SELECT first_name, last_name, username, auth_token FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($first_name, $last_name, $username, $auth_token);
$stmt->fetch();
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Settings</h2>
        <p><?php echo $message; ?></p>
        <form method="POST" action="settings.php">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label for="auth_token">Authentication Token</label>
                <input type="text" id="auth_token" name="auth_token" value="<?php echo htmlspecialchars($auth_token); ?>">
                <button type="button" onclick="showInstructions()">How to find your token</button>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Update Settings</button>
        </form>
    </div>

    <div id="instructions-modal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h2>How to find your Authentication Token</h2>
            <p>1. Open the Fansly website and log in.</p>
            <p>2. Open the developer tools (F12 or right-click and select "Inspect").</p>
            <p>3. Go to the "Network" tab.</p>
            <p>4. Filter for "api" calls by typing "apiv3.fansly" in the filter box.</p>
            <p>5. Click on one of the network calls to see its details.</p>
            <p>6. Find the "Authorization" header in the request headers.</p>
            <p>7. Copy the value of the "Authorization" header. It should look something like this:</p>
            <p><code>Njc2NDI4NTE0NjA1NDEyMzUzOjE6Mjo1NTliMDY0ZWU1OGQ3ZWM1YjU0OTEwZWQ5NDFhNzM=</code></p>
        </div>
    </div>

    <script>
        function showInstructions() {
            document.getElementById('instructions-modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('instructions-modal').style.display = 'none';
        }
    </script>
</body>
</html>
