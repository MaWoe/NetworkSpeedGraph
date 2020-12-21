<?php

$logDir = __DIR__ . '/../logs';
$aggregateLog = $logDir . '/aggregateLog.php';
$textLog = $logDir . '/stats.txt';

if (file_exists($aggregateLog)) {
    $aggregate = require $aggregateLog;
} else {
    $aggregate = [];
}

if (file_exists($textLog)) {
    $textLogLines = file($textLog);
} else {
    $textLogLines = [];
}

$inputHandle = fopen('php://stdin', 'r');

$lines = [];
while ($line = fgets($inputHandle)) {
    $lines[] = $line;
}

fclose($inputHandle);

$event = json_decode(implode('', $lines), true);
// 2020-12-21T17:01:18.206896Z
$dateTime = new DateTime($event['timestamp']);
$event['timestamp'] = $dateTime->modify('+ 1 hour')->format(
    'Y-m-d H:i:s'
);
$index = sprintf("%s.%sZ", $dateTime->getTimestamp(), $dateTime->format('u'));
$aggregate[$index] = $event;
asort($aggregate);
file_put_contents($aggregateLog, sprintf('<?php return %s;', var_export($aggregate, true)));

$textLogEntryLines = [];
$textLogEntryLines[] = $dateTime->format('Y-m-d H:i:s');
$textLogEntryLines[] = '===================';
$textLogEntryLines[] = sprintf(
    "%s (%s) [%d km]: %s ms",
    $event['server']['sponsor'],
    $event['server']['name'],
    $event['server']['d'],
    $event['ping']
);
$textLogEntryLines[] = sprintf('Download: %.2f Mbit/s', round($event['download'] / 1000 / 1000, 2));
$textLogEntryLines[] = sprintf('Upload:    %.2f Mbit/s', round($event['upload'] / 1000 / 1000, 2));
$textLogEntryLines[] = '';

foreach (array_reverse($textLogEntryLines) as $line) {
    array_unshift($textLogLines, $line . PHP_EOL);
}
file_put_contents($textLog, implode('', $textLogLines));

echo sprintf("Processed event %s\n", $event['timestamp']);
