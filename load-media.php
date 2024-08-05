<?php
$mediaDir = 'media';
$files = array_values(array_diff(scandir($mediaDir), ['.', '..'])); // Ensure $files is an array
echo json_encode(['media' => $files]);
?>
