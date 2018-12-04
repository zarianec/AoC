<?php
/**
 *
 * AoC
 *
 * @author: Artem Stoianov <stoianov.artem@gmail.com>
 *
 */
$start = microtime(true);

$log = explode("\n", file_get_contents('./input.txt'));
$parsed = [];

foreach ($log as $entry) {
    $timestamp = substr($entry, 1, 16);
    $data = substr($entry, 19);
    $time = new DateTime($timestamp);

    $parsed[$time->format('YmdHi')] = [
        'time' => $timestamp,
        'data' => $data,
    ];
}

ksort($parsed);
$parsed = array_values($parsed);

$collectionMost = [];
$collectionByMinute = [];

$curGuardId = null;
for ($i = 0; $i < count($parsed); $i++) {
    $msg = $parsed[$i]['data'];
    /** @var \DateTime $time */
    $time = $parsed[$i]['time'];

    if (strpos($msg, 'begins shift') !== false) {
        preg_match('/#(\d{1,})/', $msg, $matches);
        $curGuardId = $matches[1];
    }

    if ($msg == 'falls asleep') {
        $wakesUpAt = $parsed[$i + 1]['time'];

        $sleepMin = (int) substr($time, -2);
        $wakeMin = (int) substr($wakesUpAt, -2);

        $collectionMost[$curGuardId] += $wakeMin - $sleepMin;

        for ($k = $sleepMin; $k < $wakeMin; $k++) {
            $collectionByMinute[$curGuardId . '_' . $k] += 1;
        }

        $i++;
    }
}

$max = 0;
$guardId = null;
foreach ($collectionMost as $key => $value) {
    if ($max < $value) {
        $max = $value;
        $guardId = $key;
    }
}

$max = 0;
$minute = 0;
foreach ($collectionByMinute as $key => $value) {
    $data = explode('_', $key);
    if ($data[0] != $guardId) {
        continue;
    }

    if ($max < $value) {
        $max = $value;
        $minute = $data[1];
    }
}

echo "Part 1:" . PHP_EOL;
echo "Target ID: " . $guardId . "; ";
echo "Target min: " . $minute;
echo PHP_EOL;

$max = 0;
$target = null;
foreach ($collectionByMinute as $key => $value) {
    if ($max < $value) {
        $max = $value;
        $target = $key;
    }
}

echo "Part 2:" . PHP_EOL;
echo "Target: " . $target;
echo PHP_EOL;

echo "Execution time: ";
echo (microtime(true) - $start) * 1000;
echo PHP_EOL;