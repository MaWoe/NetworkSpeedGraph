<?php

$handle = fopen(__DIR__ . '/../log.log', 'r');

$time = $upload = $download = $ping = $name = $location = $distance = null;
while ($line = fgets($handle)) {
    $line = trim($line);
    // So 20. Dez 23:00:01 CET 2020
    if (preg_match('/Dez .* CET/', $line)) {
        $time = DateTime::createFromFormat('* d. M H:i:s e Y', str_replace('Dez', 'Dec', $line));
    } elseif (preg_match('/^Hosted by (.+) \((.+)\) \[(.+) km]: (.+) ms$/', $line, $matches)) {
        // Hosted by green.ch AG (Lupfig) [67.79 km]: 34.923 ms
        $name = $matches[1];
        $location = $matches[2];
        $distance = $matches[3];
        $ping = $matches[4];
    } elseif (preg_match('/^Download: (.*) Mbit\/s$/', $line, $matches)) {
        // Download: 69.94 Mbit/s
        $download = $matches[1] * 1000 * 1000;
    } elseif (preg_match('/^Upload: (.*) Mbit\/s$/', $line, $matches)) {
        // Download: 69.94 Mbit/s
        $upload = $matches[1] * 1000 * 1000;
        echo sprintf(
            "0,%s,%s,%s,%s,%s,%s,%s,,\n",
            $name,
            $location,
            $time->format('Y-m-d H:i:s'),
            $distance,
            $ping,
            $download,
            $upload
        );
        $time = $upload = $download = $ping = $name = $location = $distance = null;
    }
}
