<?php
include 'db.php';

// Get POST data
$post_data = json_decode(file_get_contents('php://input'), true);
$content = $post_data['content'];
$attachments = $post_data['attachments'];

// Insert post content
$sql = "INSERT INTO posts (content) VALUES ('$content')";
if ($conn->query($sql) === TRUE) {
    $post_id = $conn->insert_id;

    // Insert attachments
    foreach ($attachments as $attachment) {
        $contentId = $attachment['contentId'];
        $contentType = $attachment['contentType'];
        $pos = $attachment['pos'];
        $sql = "INSERT INTO attachments (post_id, content_id, content_type, pos) VALUES ('$post_id', '$contentId', '$contentType', '$pos')";
        $conn->query($sql);
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
?>
