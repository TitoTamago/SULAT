<?php
header('Content-Type: application/json');
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

// Step 1: Get the base64 image from JSON body
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['image'])) {
    echo json_encode(['success' => false, 'error' => 'No image data received']);
    exit;
}

$imageData = $data['image'];

// Step 2: Extract and decode the image
if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
    $type = strtolower($type[1]); // jpg, png, etc.

    if (!in_array($type, ['png', 'jpg', 'jpeg'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid image type']);
        exit;
    }

    $imageData = substr($imageData, strpos($imageData, ',') + 1);
    $imageData = base64_decode($imageData);
    if ($imageData === false) {
        echo json_encode(['success' => false, 'error' => 'Base64 decode failed']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid image data format']);
    exit;
}

// Step 3: Load image from decoded data
$sourceImage = imagecreatefromstring($imageData);
if (!$sourceImage) {
    echo json_encode(['success' => false, 'error' => 'Failed to create image from data']);
    exit;
}

// Step 4: Create a new image with white background
$width = imagesx($sourceImage);
$height = imagesy($sourceImage);

$whiteBgImage = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($whiteBgImage, 255, 255, 255);
imagefill($whiteBgImage, 0, 0, $white);

// Copy the original (possibly transparent) image onto white background
imagecopy($whiteBgImage, $sourceImage, 0, 0, 0, 0, $width, $height);

// Step 5: Save the image
$fileName = 'latest_canvas'.$user_id.'.png';
$filePath = __DIR__ . '/' . $fileName;

if (imagepng($whiteBgImage, $filePath)) {
    imagedestroy($whiteBgImage);
    imagedestroy($sourceImage);

    // Step 6: Include OCR handler (must define $text)
    ob_start();
    require_once('ocr-handler.php');
    ob_end_clean(); // prevent double echo from OCR file

    echo json_encode([
        'success' => true,
        'file' => $fileName,
        'text' => isset($text) ? $text : 'No text extracted'
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save image file']);
}