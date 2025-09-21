<?php
function googleSearch($query) {
    $JSON_KEY = __DIR__ . '/search-api.json';
    $LIMIT_FILE = __DIR__ . '/search-limit.json'; // store usage here
    $DAILY_LIMIT = 100;

    // --- Load usage counter ---
    $usage = [
        'date' => date('Y-m-d'),
        'count' => 0
    ];
    if (file_exists($LIMIT_FILE)) {
        $usage = json_decode(file_get_contents($LIMIT_FILE), true) ?: $usage;
    }

    // Reset count if new day
    if ($usage['date'] !== date('Y-m-d')) {
        $usage['date'] = date('Y-m-d');
        $usage['count'] = 0;
    }

    // Check if limit reached
    if ($usage['count'] >= $DAILY_LIMIT) {
        return "Search API daily limit reached (100 requests).";
    }

    // --- Load config ---
    if (!file_exists($JSON_KEY)) {
        return "Search config file missing.";
    }
    $config = json_decode(file_get_contents($JSON_KEY), true);
    $GOOGLE_API_KEY   = $config['api_key'] ?? null;
    $SEARCH_ENGINE_ID = $config['search_engine_id'] ?? null;

    if (empty($GOOGLE_API_KEY) || empty($SEARCH_ENGINE_ID)) {
        return "Search API key or Search Engine ID missing.";
    }

    // --- Perform request ---
    $url = "https://www.googleapis.com/customsearch/v1"
         . "?key=" . urlencode($GOOGLE_API_KEY)
         . "&cx=" . urlencode($SEARCH_ENGINE_ID)
         . "&q=" . urlencode($query);

    $response = @file_get_contents($url);
    if ($response === false) {
        return "Search API request failed.";
    }

    $data = json_decode($response, true);
    if (isset($data['error'])) {
        return "Search API error: " . $data['error']['message'];
    }
    if (empty($data['items'])) {
        return "No results found.";
    }

    // Update usage counter
    $usage['count']++;
    file_put_contents($LIMIT_FILE, json_encode($usage));

    // Take only top 5 results
    $searchSummary = "";
    foreach (array_slice($data['items'], 0, 5) as $index => $item) {
        $searchSummary .= ($index+1) . ". " . $item['title']
                        . " - " . $item['snippet']
                        . " (" . $item['link'] . ")\n";
    }

    return $searchSummary;
}

function checkSearchLimit() {
    $usageFile = __DIR__ . '/search-limit.json';
    $limitPerDay = 100;
    $today = date('Y-m-d');

    if (!file_exists($usageFile)) {
        return false; // no limit hit
    }

    $usage = json_decode(file_get_contents($usageFile), true);

    if ($usage['date'] === $today && $usage['count'] >= $limitPerDay) {
        return true; // limit reached
    }

    return false; // limit not reached
}