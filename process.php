<?php
require 'config.php';
require 'statistics.php';

// Utility function to fetch IDs
function get_or_insert_id($connection, $tableName, $value, $domainId = null) {
    $column = "name";
    if ($tableName === "url") {
        $query = $connection->prepare("INSERT IGNORE INTO url (domain_id, name) VALUES (?, ?)");
        $query->bind_param('is', $domainId, $value);
    } else {
        $query = $connection->prepare("INSERT IGNORE INTO $tableName ($column) VALUES (?)");
        $query->bind_param('s', $value);
    }
    $query->execute();

    $idQuery = $connection->prepare("SELECT id FROM $tableName WHERE $column = ?");
    $idQuery->bind_param('s', $value);
    $idQuery->execute();
    $result = $idQuery->get_result();
    $row = $result->fetch_assoc();
    return $row['id'];
}

try {
    // 1. Receive and validate input
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    $element = filter_input(INPUT_POST, 'element', FILTER_SANITIZE_STRING);

    if (!$url || !$element) {
        throw new Exception("Invalid input.");
    }

    $domain = parse_url($url, PHP_URL_HOST);

    // Fetch DomainId, URLId, and ElementId
    $domainId = get_or_insert_id($connection, "domain", $domain);
    $urlId = get_or_insert_id($connection, "url", $url, $domainId);
    $elementId = get_or_insert_id($connection, "element", $element);

    // Check if the same request was made within the last 5 minutes
    $checkQuery = $connection->prepare("SELECT count, time, duration FROM request 
        WHERE domain_id = ? 
        AND url_id = ? 
        AND element_id = ?
        ORDER BY time DESC 
        LIMIT 1");
    $checkQuery->bind_param('iii', $domainId, $urlId, $elementId);
    $checkQuery->execute();
    $recentRequest = $checkQuery->get_result()->fetch_assoc();

    if ($recentRequest && (time() - strtotime($recentRequest['time'])) < 300) {
        echo "URL $url fetched on " . $recentRequest['time'] . ", took " . $recentRequest['duration'] . "msec. Element <$element> appeared " . $recentRequest['count'] . " times in the page.";
        echo generate_statistics($connection, $domainId, $domain, $elementId, $element);
        exit;
    }

    // 2. Fetch the specified URL using cURL
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36'
    ]);

    $start_time = microtime(true);
    $content = curl_exec($ch);
    $end_time = microtime(true);

    if (curl_errno($ch)) {
        throw new Exception('CURL error: ' . curl_error($ch));
    }
    curl_close($ch);

    // 3. Parse the fetched content
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    if (!$content) {
        throw new Exception('Failed to fetch content from the URL');
    }
    $dom->loadHTML($content);
    $count = $dom->getElementsByTagName($element)->length;

    // Insert the new request into the `request` table
    $requestQuery = $connection->prepare("INSERT INTO request (domain_id, url_id, element_id, time, duration, count) 
    VALUES (?, ?, ?, NOW(), ?, ?)");
    $response_time = ($end_time - $start_time) * 1000;
    $requestQuery->bind_param('iiidi', $domainId, $urlId, $elementId, $response_time, $count);
    $requestQuery->execute();

    echo "URL $url fetched on " . date("Y-m-d H:i:s") . ", took " . round($response_time) . "msec. Element <$element> appeared $count times in the page.";
    echo generate_statistics($connection, $domainId, $domain, $elementId, $element);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $connection->close();
}
?>
