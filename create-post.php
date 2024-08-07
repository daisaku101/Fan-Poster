<?php
require 'FanslyPoster.php';

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['content']) || empty($data['attachments'])) {
    echo json_encode(['error' => 'Content or attachments missing']);
    exit;
}

try {
    $fanslyPoster = new FanslyPoster();

    $mediaIds = array_column($data['attachments'], 'contentId');
    $mediaPrices = array_column($data['attachments'], 'price'); // Ensure prices are extracted
    $isBundle = count($mediaIds) > 1;
    $price = isset($data['price']) ? $data['price'] : 0;

    if ($isBundle) {
        $accountMediaModels = array_map(function ($id, $price) {
            return [
                "mediaId" => $id,
                "previewId" => null,
                "permissionFlags" => 0,
                "price" => $price,
                "whitelist" => []
            ];
        }, $mediaIds, $mediaPrices);

        $mediaUploadData = [
            "previewId" => null,
            "permissionFlags" => 0,
            "price" => $price,
            "accountMediaModels" => $accountMediaModels,
            "whitelist" => [],
            "permissions" => ["permissionFlags" => [["flags" => 0]]],
            "tags" => []
        ];
    } else {
        $mediaUploadData = [
            [
                "mediaId" => $mediaIds[0],
                "previewId" => null,
                "permissionFlags" => 0,
                "price" => $mediaPrices[0], // Add price for single media
                "whitelist" => [],
                "permissions" => ["permissionFlags" => [["flags" => 0]]], // Use correct flags
                "tags" => []
            ]
        ];
    }

    // Log the payload
    error_log("Media Upload Data: " . json_encode($mediaUploadData));

    $uploadResponse = json_decode($fanslyPoster->uploadMedia($mediaUploadData, $isBundle), true);

    // Detailed logging for debugging
    error_log("Upload Response: " . print_r($uploadResponse, true));

    if ($isBundle && isset($uploadResponse['response']['accountMedia'])) {
        $newMediaIds = array_column($uploadResponse['response']['accountMedia'], 'id');
    } elseif (!$isBundle && isset($uploadResponse['response'][0]['id'])) {
        $newMediaIds = [$uploadResponse['response'][0]['id']];
    } else {
        echo json_encode(['error' => 'Failed to upload media.', 'details' => $uploadResponse]);
        exit;
    }

    foreach ($data['attachments'] as $index => &$attachment) {
        $attachment['contentId'] = $newMediaIds[$index];
    }

    // Prepare the post content data
    $postContentData = [
        "content" => $data['content'],
        "price" => $price,
        "attachments" => $data['attachments'],
        "fypFlags" => 0,
        "inReplyTo" => null,
        "quotedPostId" => null,
        "scheduledFor" => 0,
        "expiresAt" => 0,
        "postReplyPermissionFlags" => [],
        "pinned" => 0,
        "wallIds" => []
    ];

    // Log the post content payload
    error_log("Post Content Data: " . json_encode($postContentData));

    $response = $fanslyPoster->postContent($postContentData);
    echo $response;
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
