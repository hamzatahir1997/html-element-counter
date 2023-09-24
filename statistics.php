<?php
function generate_statistics($connection, $domainId, $domain, $elementId, $element) {
    $statistics = "<h3>General Statistics</h3>";

    // URLs from domain
    $urlCountQuery = $connection->prepare("SELECT COUNT(DISTINCT url_id) as url_count FROM request WHERE domain_id = ?");
    $urlCountQuery->bind_param('i', $domainId);
    $urlCountQuery->execute();
    $urlCountResult = $urlCountQuery->get_result();
    $urlCountRow = $urlCountResult->fetch_assoc();
    $totalUrls = $urlCountRow['url_count'];
    $statistics .= "$totalUrls different URLs from $domain have been fetched.<br>";

    // Average fetch time
    $avgTimeQuery = $connection->prepare("SELECT AVG(duration) as avg_time FROM request WHERE domain_id = ? AND time > NOW() - INTERVAL 24 HOUR");
    $avgTimeQuery->bind_param('i', $domainId);
    $avgTimeQuery->execute();
    $avgTimeResult = $avgTimeQuery->get_result();
    $avgTimeRow = $avgTimeResult->fetch_assoc();
    $averageTime = round($avgTimeRow['avg_time']);
    $statistics .= "Average fetch time from $domain during the last 24 hours is {$averageTime}ms.<br>";

    // Total count of element from this domain
    $elementDomainCountQuery = $connection->prepare("SELECT SUM(count) as total_count FROM request WHERE domain_id = ? AND element_id = ?");
    $elementDomainCountQuery->bind_param('ii', $domainId, $elementId);
    $elementDomainCountQuery->execute();
    $elementDomainCountResult = $elementDomainCountQuery->get_result();
    $elementDomainCountRow = $elementDomainCountResult->fetch_assoc();
    $totalElementDomainCount = $elementDomainCountRow['total_count'];
    $statistics .= "There was a total of $totalElementDomainCount '$element' elements from $domain.<br>";

    // Total count of element from all domains
    $elementTotalCountQuery = $connection->prepare("SELECT SUM(count) as total_count FROM request WHERE element_id = ?");
    $elementTotalCountQuery->bind_param('i', $elementId);
    $elementTotalCountQuery->execute();
    $elementTotalCountResult = $elementTotalCountQuery->get_result();
    $elementTotalCountRow = $elementTotalCountResult->fetch_assoc();
    $totalElementCount = $elementTotalCountRow['total_count'];
    $statistics .= "Total of $totalElementCount '$element' elements counted in all requests ever made.<br>";

    return $statistics;
}
?>
