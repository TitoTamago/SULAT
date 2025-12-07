<?php
include('../includes/init.php');
include('../includes/google-search-api/search-handler.php');
session_start();

// Check if logged in
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// ----------------------------
// FOLLOW-UP QUESTION DETECTION
// ----------------------------
function isFollowUp($text) {
    $patterns = [
        '/what year/i',
        '/when/i',
        '/how about/i',
        '/tell me more/i',
        '/and/i',
        '/what else/i',
        '/who/i',
        '/why/i',
        '/where/i',
        '/what about/i',
        '/and then/i',
        '/continue/i',
        '/is there a reason/i',
        '/can you explain/i',
        '/more details/i',
        '/how so/i'
    ];
    foreach ($patterns as $p) {
        if (preg_match($p, $text)) return true;
    }
    return false;
}

// ----------------------------
// HANDLE POST REQUEST
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $input = trim($_POST['message']);
    if ($input === '') {
        echo json_encode(['error' => 'Empty message']);
        exit;
    }

    // Load API key
    $JSON_KEY = __DIR__ . '/openAI-key.json';
    if (!file_exists($JSON_KEY)) {
        throw new Exception("Config file not found: $JSON_KEY");
    }
    $config = json_decode(file_get_contents($JSON_KEY), true);
    $OPENAI_API_KEY = $config['api_key'] ?? null;

    // ----------------------------
    // LOAD OR INITIALIZE CONVERSATION HISTORY
    // ----------------------------
    if (!isset($_SESSION['conversation_history'])) {
        $_SESSION['conversation_history'] = [];
    }

    $conversationHistory = $_SESSION['conversation_history'];

    // ----------------------------
    // FOLLOW-UP QUERY HANDLING
    // ----------------------------
    $filtered = array_filter($conversationHistory, fn($m) => $m['role'] === 'user');
    $last_question = !empty($filtered) ? end($filtered)['content'] : null;

    $searchQuery = $input;
    if ($last_question && isFollowUp($input)) {
        $searchQuery = $last_question . " " . $input;
    }


    // ----------------------------
    // GOOGLE SEARCH
    // ----------------------------
    $searchSummary = googleSearch($searchQuery);
    if (strpos($searchSummary, "Search API") === 0 || empty($searchSummary)) {
        $searchSummary = "No search results available.";
    }

    // ----------------------------
    // BUILD GPT MESSAGES WITH HISTORY
    // ----------------------------
    $gptMessages = [
        ['role' => 'system', 'content' => 'You are a helpful AI assistant. Use the search results if relevant. Keep answers factual and concise.']
    ];

    // Add conversation history
    foreach ($conversationHistory as $msg) {
        $gptMessages[] = $msg;
    }

    // Add current user message with search context
    $gptMessages[] = [
        'role' => 'user',
        'content' => "Question: $input\n\nSearch results:\n$searchSummary\n\nProvide the best answer based on the question and search results."
    ];

    // ----------------------------
    // GPT REQUEST
    // ----------------------------
    $payload = [
        'model' => 'gpt-3.5-turbo',
        'messages' => $gptMessages,
        'temperature' => 0.3,
        'max_tokens' => 512
    ];

    $ch = curl_init();
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

    // ----------------------------
    // SAVE TO DATABASE
    // ----------------------------
    $stmt = $pdo->prepare("INSERT INTO tbl_conversation (user_id, input_text, ai_response, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $input, $ai_response]);

    // ----------------------------
    // UPDATE SESSION MEMORY
    // ----------------------------
    $conversationHistory[] = ['role' => 'user', 'content' => $input];
    $conversationHistory[] = ['role' => 'assistant', 'content' => $ai_response];
    $_SESSION['conversation_history'] = $conversationHistory;
    $_SESSION['last_question'] = $input;
    $_SESSION['last_bot'] = $ai_response;

    // ----------------------------
    // RETURN RESPONSE
    // ----------------------------
    echo json_encode([
        'user' => htmlspecialchars($input),
        'bot' => htmlspecialchars($ai_response),
        'search_result' => nl2br(htmlspecialchars($searchSummary)),
    ]);
    exit;
}

echo json_encode(['error' => 'Invalid request']);
?>