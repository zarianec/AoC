<?php
/**
 *
 * AoC
 *
 * @author: Artem Stoianov <stoianov.artem@gmail.com>
 *
 */

/**
 * Do the Alchemy
 *
 * @param string $polymer
 * @return string
 */
function reactPolymer(string $polymer): string
{
    for ($i = 0; $i < strlen($polymer); $i++) {
        $cur = reset(unpack("C*", $polymer[$i]));
        $next = reset(unpack("C*", $polymer[$i + 1]));

        // Should remove them
        if (abs($cur - $next) == 32) {
            $polymer = substr_replace($polymer, '', $i, 2);
            $i = max($i - 2, -1);
        }
    }

    return $polymer;
}

$start = microtime(true);

$polymer = file_get_contents('./input.txt');

/**
 * Part 1
 */
$polymer = reactPolymer($polymer);

echo "Part 1:" . PHP_EOL;
echo "Polymer size: " . strlen($polymer) . PHP_EOL;

/**
 * Part 2
 */
$elements = 'abcdefghijklmnopqrstuvwxyz';
$minSize = strlen($polymer);
for ($i = 0; $i < strlen($elements); $i++) {
    $pos = $elements[$i];
    $neg = strtoupper($pos);

    $testPolymer = strtr($polymer, [
        $pos => '',
        $neg => '',
    ]);
    $res = reactPolymer($testPolymer);

    if ($minSize > strlen($res)) {
        $minSize = strlen($res);
    }
}

echo "Part 2:" . PHP_EOL;
echo "Fixed polymer size: " . $minSize . PHP_EOL;

echo PHP_EOL;
echo "Execution time: ";
echo (microtime(true) - $start) * 1000;
echo PHP_EOL;