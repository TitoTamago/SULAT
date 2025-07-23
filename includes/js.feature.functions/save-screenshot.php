<?php
require_once '../init.php'; // include your PDO connection here
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['screenshot'])) {
        try {
            $base64 = $_POST['screenshot'];

            // Extract mime type and base64 data
            if (preg_match('/^data:(image\/\w+);base64,(.+)$/', $base64, $matches)) {
                $mimeType = $matches[1]; // e.g., image/png
                $base64Data = $matches[2];
            } else {
                throw new Exception("Invalid base64 format.");
            }

            // Decode base64 image
            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                throw new Exception("Failed to decode base64.");
            }

            // Save to database
            $stmt = $pdo->prepare("INSERT INTO tbl_screenshot (user_id, screenshot_data, image_type, created_at) VALUES (:user_id, :screenshot_data, :image_type, NOW())");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':screenshot_data', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':image_type', $mimeType);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Insert failed.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No screenshot provided.']);
    }
} else {
    echo "no data was POST";
}
?>