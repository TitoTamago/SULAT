<?php
/* ============================================================
    GOOGLE SEARCH API (Custom Crawler or SerpAPI style)
    Optimized with caching, rate-limit protection, and safe summary building
    PHP 5.3 COMPATIBLE
   ============================================================ */

$CACHE_DIR = __DIR__ . "/cache/"; // existing
$CACHE_SECONDS = 3600;            // 1 hour
$CACHE_MAX_FILES = 500;           // max number of cache files


/* ============================================================
    ENSURE CACHE FOLDER EXISTS
   ============================================================ */
if (!is_dir($CACHE_DIR)) {
    mkdir($CACHE_DIR, 0777, true);
}

/* ============================================================
    CLEAN OLD CACHE FILES (Auto-clean)
   ============================================================ */
function autoCleanCache($CACHE_DIR, $CACHE_MAX_FILES) {
    $files = glob($CACHE_DIR . "*.json");
    if (count($files) <= $CACHE_MAX_FILES) return;

    // sort oldest first
    usort($files, function($a, $b) {
        return filemtime($a) - filemtime($b);
    });

    $deleteCount = intval(count($files) * 0.3);
    for ($i = 0; $i < $deleteCount; $i++) {
        @unlink($files[$i]);
    }
}


/* ============================================================
    THE SEARCH FUNCTION
   ============================================================ */
function googleSearch($query) {
    global $CACHE_DIR, $CACHE_SECONDS, $CACHE_MAX_FILES;

    $query = trim($query);
    if ($query == "") return "Empty query.";

    /* ------------------------------------------------------------
        1. CACHE CHECK
       ------------------------------------------------------------ */
    $cacheKey = md5($query);
    $cacheFile = $CACHE_DIR . $cacheKey . ".json";

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $CACHE_SECONDS) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if ($data && isset($data["summary"])) {
            return $data["summary"];
        }
    }

    /* ------------------------------------------------------------
        2. RATE LIMIT PROTECTION
       ------------------------------------------------------------ */
    if (!checkSearchLimit()) {
        return "Search limit reached. Try again later.";
    }

    /* ------------------------------------------------------------
        3. CALL YOUR SEARCH API (EDIT THIS PART IF USING ANOTHER API)
       ------------------------------------------------------------ */

    // -------------------------------------------------------------
    // Replace this with your real search provider
    // Example: SerpAPI curl request
    // -------------------------------------------------------------
    $apiKey = "AIzaSyDmkjSRo0sPT1BHJr0rLIM8s02YLGXE_jw";
    $cx     = "1332a581dd3474b9c";

    $url = "https://www.googleapis.com/customsearch/v1?key=" . urlencode($apiKey) .
           "&cx=" . urlencode($cx) . "&q=" . urlencode($query);

    $json = file_get_contents($url);

    if (!$json) {
        return "Failed to fetch search results.";
    }

    $results = json_decode($json, true);

    /* ------------------------------------------------------------
        4. FORMAT SEARCH SUMMARY
       ------------------------------------------------------------ */
    $summary = "";

    if (!isset($results["items"])) {
        $summary = "No search results found.";
    } else {
        $count = 0;
        foreach ($results["items"] as $item) {
            if ($count >= 5) break; // only top 5 results

            $title = isset($item["title"]) ? $item["title"] : "";
            $snippet = isset($item["snippet"]) ? $item["snippet"] : "";

            $summary .= "- **" . strip_tags($title) . "**: " .
                        strip_tags($snippet) . "\n";

            $count++;
        }

        if ($summary == "") {
            $summary = "Search results found, but no readable summary.";
        }
    }

    /* ------------------------------------------------------------
        5. SAVE TO CACHE
       ------------------------------------------------------------ */
    $cacheData = array(
        "query" => $query,
        "summary" => $summary,
        "timestamp" => time()
    );

    file_put_contents($cacheFile, json_encode($cacheData));

    // Clean old cache files
    autoCleanCache($CACHE_DIR, $CACHE_MAX_FILES);

    return $summary;
}

/* ============================================================
    RATE-LIMIT TRACKING (PREVENT SPAM)
   ============================================================ */
function checkSearchLimit() {
    $LIMIT_FILE  = __DIR__ . '/search-limit.json';
    $DAILY_LIMIT = 100;
    $today       = date('Y-m-d');

    if (!file_exists($LIMIT_FILE)) {
        file_put_contents($LIMIT_FILE, json_encode(array(
            "date" => $today,
            "count" => 0
        )));
        return true;
    }

    $usage = json_decode(file_get_contents($LIMIT_FILE), true);

    if ($usage['date'] !== $today) {
        $usage['date'] = $today;
        $usage['count'] = 0;
    }

    if ($usage['count'] >= $DAILY_LIMIT) {
        return false;
    }

    $usage['count']++;

    file_put_contents($LIMIT_FILE, json_encode($usage));

    return true;
}