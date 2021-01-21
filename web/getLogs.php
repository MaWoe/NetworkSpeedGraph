<?php
function bitsToMbitsFloat($bitsAsString)
{
    return round(($bitsAsString) / 1024 / 1024, 2);
}

$events = require __DIR__ . '/../logs/aggregateLog.php';

$time = $upload = $download = $ping = [];
$now = time();
$maxAge = 60 * 60 * 24 * 10; // 10 days
foreach ($events as $event) {
    $timestamp = $event['timestamp'];
    if ($now - (new DateTime())->getTimestamp() > $maxAge) {
        continue;
    }
    $upload[] = bitsToMbitsFloat($event['upload']);
    $download[] = bitsToMbitsFloat($event['download']);
    $ping[] = $event['ping'];
    $time[] = $timestamp;
}

header('Content-Type: text/javascript');
echo json_encode(
    [
        'x' => $time,
        'download' => $download,
        'upload' => $upload,
        'ping' => $ping
    ]
);
