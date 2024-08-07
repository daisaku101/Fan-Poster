<?php
require 'db.php';

// Initialize DB connection
$db = new DB();

// Fetch media from the database
$mediaList = $db->getMedia();

echo json_encode(['media' => $mediaList]);
?>
