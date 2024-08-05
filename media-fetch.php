<?php
$proxyUrl = 'http://localhost/fansly-poster/proxy.php?url=';
$targetUrl = 'https://apiv3.fansly.com/api/v1/media/vaultnew?albumId=654235541616730112&mediaType=&search=&before=0&after=0&ngsw-bypass=true';
$url = $proxyUrl . urlencode($targetUrl);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fansly_poster";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch media data
function fetchMedia($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// Function to save media to the filesystem and database
function saveMedia($conn, $filename, $mediaBlob, $filetype) {
    $path = 'media/' . $filename;
    
    // Ensure the directory exists
    if (!is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }

    // Save the media file to the filesystem
    if (file_put_contents($path, $mediaBlob) === false) {
        error_log("Failed to save media to filesystem: " . $filename);
        return false;
    }

    // Save the media metadata to the database
    $stmt = $conn->prepare("INSERT INTO media (filename, filetype, path) VALUES (?, ?, ?)");
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        return false;
    }
    $stmt->bind_param("sss", $filename, $filetype, $path);
    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        $stmt->close();
        return false;
    }
    $stmt->close();
    return true;
}

// Check if media already exists in the database
function mediaExists($conn, $filename) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM media WHERE filename = ?");
    if (!$stmt) {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        return false;
    }
    $stmt->bind_param("s", $filename);
    if (!$stmt->execute()) {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        $stmt->close();
        return false;
    }
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

$mediaData = fetchMedia($url);
$mediaArray = json_decode($mediaData, true);

if ($mediaArray['success']) {
    foreach ($mediaArray['response']['media'] as $media) {
        $mediaUrl = $proxyUrl . urlencode($media['locations'][0]['location']);
        $mediaBlob = fetchMedia($mediaUrl);
        
        $filename = $media['filename'];
        $filetype = $media['mimetype'];

        // Check if the media already exists
        if (!mediaExists($conn, $filename)) {
            if (!saveMedia($conn, $filename, $mediaBlob, $filetype)) {
                echo "Failed to save media: " . $filename . "<br>";
            }
        }
    }
    echo "Media fetched and saved successfully.";
} else {
    echo "Failed to fetch media.";
}

$conn->close();
?>
