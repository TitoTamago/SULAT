<?php
include('../includes/init.php');
include('../includes/google-search-api/search-handler.php');
session_start();

header('Content-Type: application/json');

// ============================
// CHECK LOGIN
// ============================
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($user_id == 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// ============================
// FOLLOW-UP QUESTION DETECTION
// ============================
function isFollowUp($text) {
    $patterns = array(
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
    );

    foreach ($patterns as $p) {
        if (preg_match($p, $text)) return true;
    }

    return false;
}

// ============================
// HANDLE POST REQUEST
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {

    $input = trim($_POST['message']);

    if ($input === '') {
        echo json_encode(['error' => 'Empty message']);
        exit;
    }

    // ============================
    // LOAD OPENAI API KEY FROM JSON
    // ============================
    $JSON_KEY = __DIR__ . '/openAI-key.json';

    if (!file_exists($JSON_KEY)) {
        echo json_encode(['error' => 'OpenAI config file not found']);
        exit;
    }

    $config = json_decode(file_get_contents($JSON_KEY), true);

    if (!isset($config['api_key'])) {
        echo json_encode(['error' => 'API key missing in config']);
        exit;
    }

    $OPENAI_API_KEY = $config['api_key'];

    // ============================
    // INITIALIZE SESSION MEMORY
    // ============================
    if (!isset($_SESSION['conversation_history'])) {
        $_SESSION['conversation_history'] = array();
    }

    $conversationHistory = $_SESSION['conversation_history'];

    // ============================
    // FOLLOW-UP HANDLING
    // ============================
    $lastUserMessage = null;

    foreach (array_reverse($conversationHistory) as $msg) {
        if ($msg['role'] === 'user') {
            $lastUserMessage = $msg['content'];
            break;
        }
    }

    $searchQuery = $input;

    if ($lastUserMessage && isFollowUp($input)) {
        $searchQuery = $lastUserMessage . " " . $input;
    }

    // ============================
    // GOOGLE SEARCH
    // ============================
    $searchSummary = googleSearch($searchQuery);

    if (!$searchSummary || strpos($searchSummary, "Failed") !== false) {
        $searchSummary = "No reliable search results available.";
    }

    // ============================
    // BUILD INPUT WITH MEMORY
    // ============================
    $messages = array();

    // system message
    $messages[] = array(
        "role" => "system",
        "content" => "You are a helpful AI assistant. Use search results if relevant. Keep answers factual and concise."
    );

    // add conversation history
    foreach ($conversationHistory as $msg) {
        $messages[] = array(
            "role" => $msg['role'],
            "content" => $msg['content']
        );
    }

    // add current message with search context
    $messages[] = array(
        "role" => "user",
        "content" =>
            "Question: " . $input . "\n\n" .
            "Search results:\n" . $searchSummary . "\n\n" .
            "Provide the best clear answer."
    );

    // ============================
    // OPENAI RESPONSES API CALL
    // ============================
    $payload = array(
        "model" => "gpt-4o-mini",
        "input" => $messages,
        "temperature" => 0.3,
        "max_output_tokens" => 512
    );

    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://api.openai.com/v1/responses",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Authorization: Bearer " . $OPENAI_API_KEY
        ),
        CURLOPT_POSTFIELDS => json_encode($payload)
    ));

    $api_response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode([
            'error' => 'Curl error: ' . curl_error($ch)
        ]);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    $responseData = json_decode($api_response, true);

    if (!isset($responseData['output'][0]['content'][0]['text'])) {
        echo json_encode([
            'error' => 'Invalid OpenAI response',
            'debug' => $responseData
        ]);
        exit;
    }

    $ai_response = trim($responseData['output'][0]['content'][0]['text']);

    // ============================
    // SAVE TO DATABASE
    // ============================
    $stmt = $pdo->prepare("
        INSERT INTO tbl_conversation
        (user_id, input_text, ai_response, created_at)
        VALUES (?, ?, ?, NOW())
    ");

    $stmt->execute(array(
        $user_id,
        $input,
        $ai_response
    ));

    // ============================
    // SAVE TO SESSION MEMORY
    // ============================
    $conversationHistory[] = array(
        "role" => "user",
        "content" => $input
    );

    $conversationHistory[] = array(
        "role" => "assistant",
        "content" => $ai_response
    );

    $_SESSION['conversation_history'] = $conversationHistory;

    // ============================
    // RETURN RESPONSE
    // ============================
    echo json_encode(array(
        "user" => htmlspecialchars($input),
        "bot" => htmlspecialchars($ai_response),
        "search_result" => nl2br(htmlspecialchars($searchSummary))
    ));

    exit;
}

// ============================
// INVALID REQUEST
// ============================
echo json_encode(array(
    "error" => "Invalid request"
));