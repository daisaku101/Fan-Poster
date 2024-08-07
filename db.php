<?php
class DB {
    private $conn;

    public function __construct() {
        // Connection setup
        $this->conn = new mysqli("localhost", "root", "", "fansly_poster");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function saveAuthToken($username, $token) {
        // Prepared statement to update the auth token
        $stmt = $this->conn->prepare("UPDATE users SET auth_token = ? WHERE username = ?");
        if (!$stmt) {
            return json_encode(["status" => "error", "message" => "Database prepare error"]);
        }
        $stmt->bind_param("ss", $token, $username);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return json_encode(["status" => "success", "message" => "Token updated successfully"]);
        } else {
            $stmt->close();
            return json_encode(["status" => "error", "message" => "Token update failed or no changes made"]);
        }
    }

    public function getAuthToken($username) {
        // Prepared statement to fetch the auth token
        $stmt = $this->conn->prepare("SELECT auth_token FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['auth_token'] : '';
    }

    public function getMedia() {
        // Fetch media from the database
        $stmt = $this->conn->prepare("SELECT filename, path, fansly_media_id FROM media");
        $stmt->execute();
        $result = $stmt->get_result();
        $mediaList = [];
        while ($row = $result->fetch_assoc()) {
            $mediaList[] = $row;
        }
        $stmt->close();
        return $mediaList;
    }

    public function __destruct() {
        // Close connection
        $this->conn->close();
    }
}
?>
