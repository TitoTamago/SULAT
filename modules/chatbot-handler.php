<?php
include('../includes/init.php');
include('../includes/google-search-api/search-handler.php');
session_start();

// Make sure the user is logged in
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $input = trim($_POST['message']);
    if ($input === '') {
        echo json_encode(['error' => 'Empty message']);
        exit;
    }

    $JSON_KEY = __DIR__ . '/openAI-key.json';

    if (!file_exists($JSON_KEY)) {
        throw new Exception("Config file not found: $JSON_KEY");
    }

    $configRaw = file_get_contents($JSON_KEY);
    $config = json_decode($configRaw, true);

    // 🔍 Step 1: Get search results
    $searchSummary = googleSearch($input);

    // If the search failed (starts with "Search API"), fallback to GPT only
    if (strpos($searchSummary, "Search API") === 0) {
        $searchSummary = "No search results available.";
    }

    // 🤖 Step 2: Ask OpenAI using search results
    $OPENAI_API_KEY = $config['api_key'] ?? null; // Replace with your real API key

    $ch = curl_init();

    $payload = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant. Use the search results provided when answering.'],
            ['role' => 'user', 'content' => "Question: $input\n\nSearch results:\n$searchSummary\n\nPlease provide the best possible answer based on these results."]
        ],
        'temperature' => 0.3,
        'max_tokens' => 512
    ];

    curl_setopt_array($ch, [
        CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $OPENAI_API_KEY"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $api_response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo json_encode(['error' => 'Curl error: ' . $error]);
        exit;
    }

    $responseData = json_decode($api_response, true);
    if (!isset($responseData['choices'][0]['message']['content'])) {
        echo json_encode(['error' => 'Invalid response from OpenAI']);
        exit;
    }

    $ai_response = trim($responseData['choices'][0]['message']['content']);

    // 💾 Save to DB
    $stmt = $pdo->prepare("INSERT INTO tbl_conversation (user_id, input_text, ai_response, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $input, $ai_response]);

    // Return response to frontend
    echo json_encode([
        'user' => htmlspecialchars($input),
        'bot' => htmlspecialchars($ai_response),
        'search_result' => htmlspecialchars($searchSummary),
    ]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);