<?php
$start = microtime(true);

$ids = explode("\n", file_get_contents('./input.txt'));

$twice = 0;
$three = 0;

for ($i = 0; $i < count($ids); $i++) {
    /**
     * Calculate hash
     */
    $lettersCount = array_count_values(str_split($ids[$i]));

    if (in_array(2, array_values($lettersCount))) {
        $twice++;
    }

    if (in_array(3, array_values($lettersCount))) {
        $three++;
    }

    /**
     * Find similar
     */
    for ($k = $i + 1; $k < count($ids); $k++) {
        $id = $ids[$i];
        $id2 = $ids[$k];

        $mismatches = 0;
        for ($j = 0; $j < strlen($id); $j++) {
            if ($id[$j] !== $id2[$j]) {
                $mismatches++;
            }

            if ($mismatches > 1) {
                break;
            }

            if ($j == strlen($id) - 1) {
                $matches[] = $id;
                $matches[] = $id2;
            }
        }
    }
}

$intersection = array_intersect(str_split($matches[0]), str_split($matches[1]));

echo $twice * $three;
echo PHP_EOL;

echo implode($intersection);
echo PHP_EOL;

echo "Execution time: ";
echo (microtime(true) - $start) * 1000;
echo PHP_EOL;