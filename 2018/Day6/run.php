<?php
$start = microtime(true);

$coordinates = file_get_contents('./input.txt');
$coordinates = explode("\n", $coordinates);

///
/// Helpers
///

function dist($x1, $y1, $x2, $y2)
{
    return abs($x1 - $x2) + abs($y1 - $y2);
}

function closest($x, $y, $points)
{
    $best = $points[0];
    $shared = false;
    for ($i = 1; $i < count($points); $i++) {
        $p = $points[$i];

        $dist = dist($x, $y, $p[0], $p[1]);
        $bDist = dist($x, $y, $best[0], $best[1]);
        if ($dist < $bDist) {
            $best = $p;
            $shared = false;
        } elseif ($dist == $bDist) {
            $shared = true;
        }
    }

    if ($shared) {
        return [-1, -1];
    } else {
        return $best;
    }
}

////////////

$minX = 1e9;
$minY = 1e9;
$maxX = 0;
$maxY = 0;
$coordinates = array_map(function ($coord) use (&$minX, &$maxX, &$minY, &$maxY) {
    $point = explode(", ", $coord);

    $minX = min($minX, $point[0]);
    $minY = min($minY, $point[1]);

    $maxX = max($maxX, $point[0]);
    $maxY = max($maxY, $point[1]);

    return $point;
}, $coordinates);

$scores = [];
$region = 0;
for ($x = (int)$minX; $x <= $maxX; $x++) {
    for ($y = (int)$minY; $y <= $maxY; $y++) {
        $closest = closest($x, $y, $coordinates);

        $key = $closest[0] . '_' . $closest[1];
        $scores[$key]['x'][] = $x;
        $scores[$key]['y'][] = $y;

        // Calculate part 2 as well
        $d = 0;
        for ($i = 0; $i < count($coordinates); $i++) {
            $d += dist($x, $y, $coordinates[$i][0], $coordinates[$i][1]);

            if ($d > 10000) {
                break;
            }
        }

        if ($d < 10000) {
            $region += 1;
        }
    }
}

$best = 0;
foreach ($scores as $key => $val) {
    if (
        in_array($minX, $val['x']) || in_array($maxX, $val['x']) ||
        in_array($minY, $val['y']) || in_array($maxY, $val['y'])
    ) {
        unset($scores[$key]);
        continue;
    }

    $best = max($best, count($val['x']));
}

echo "Largest area size: " . $best . PHP_EOL;
echo "Best region size: " . $region . PHP_EOL;

echo PHP_EOL;
echo "Execution time: ";
echo round((microtime(true) - $start) * 1000, 2) . 'ms';
echo PHP_EOL;