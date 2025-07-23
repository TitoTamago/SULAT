<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Image;
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/sulat-ocr-key.json');

$client = new ImageAnnotatorClient();

// Load your test image

$user_id = intval($user_id); // make sure it's sanitized

$filePath = __DIR__ . '/latest_canvas' . $user_id . '.png';

// Check if file exists to prevent error
if (file_exists($filePath)) {
$content = file_get_contents($filePath);
$image = (new Image())->setContent($content);
} else {
    // Handle missing file
    throw new Exception("User canvas image not found for user ID: " . $user_id);
}

// Request TEXT_DETECTION
$feature = (new Feature())->setType(Feature\Type::TEXT_DETECTION);

$request = (new AnnotateImageRequest())
    ->setImage($image)
    ->setFeatures([$feature]);

$batchRequest = (new BatchAnnotateImagesRequest())
    ->setRequests([$request]);

// Send request
$batchResponse = $client->batchAnnotateImages($batchRequest);

// Store result in variable instead of echoing
$text = '';
foreach ($batchResponse->getResponses() as $response) {
    $texts = $response->getTextAnnotations();
    if (count($texts) > 0) {
        $text = $texts[0]->getDescription(); // Store in $text
    } else {
        $text = 'No text detected.';
    }
}