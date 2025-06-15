<?php

function generateOutfit($conn, $userId, $occasion) {
    $tops = getItemsByCategoryAndOccasion($conn, $userId, 'top', $occasion);
    $bottoms = getItemsByCategoryAndOccasion($conn, $userId, 'bottom', $occasion);
    $shoes = getItemsByCategoryAndOccasion($conn, $userId, 'shoes', $occasion);
    $dresses = getItemsByCategoryAndOccasion($conn, $userId, 'dress', $occasion);

    $canMakeSet1 = !empty($tops) && !empty($bottoms) && !empty($shoes);
    $canMakeSet2 = !empty($dresses) && !empty($shoes);

    if (!$canMakeSet1 && !$canMakeSet2) {
        return null;
    }

    $colourCompatibility = [
        'black' => ['white', 'red', 'blue', 'grey', 'beige'],
        'white' => ['black', 'red', 'blue', 'grey', 'beige', 'green', 'brown', 'yellow'],
        'red'   => ['black', 'white', 'beige', 'grey', 'blue'],
        'blue'  => ['white', 'beige', 'grey', 'brown'],
        'green' => ['white', 'beige', 'brown', 'grey'],
        'yellow'=> ['white', 'beige', 'grey', 'blue'],
        'grey'  => ['white', 'black', 'red', 'blue', 'yellow'],
        'beige' => ['white', 'black', 'red', 'green', 'blue', 'grey'],
        'brown' => ['beige', 'green', 'blue', 'white'],
    ];

    $dutchToEnglish = [
        'zwart' => 'black',
        'wit' => 'white',
        'rood' => 'red',
        'blauw' => 'blue',
        'groen' => 'green',
        'geel' => 'yellow',
        'grijs' => 'grey',
        'beige' => 'beige',
        'bruin' => 'brown'
    ];

    function coloursMatch($c1, $c2, $map, $translator) {
        $eng1 = $translator[strtolower($c1)] ?? null;
        $eng2 = $translator[strtolower($c2)] ?? null;

        return $eng1 && $eng2 && isset($map[$eng1]) && in_array($eng2, $map[$eng1]);
    }

    // Randomly decide which set to try first
    $setChoice = 0;
    if ($canMakeSet1 && $canMakeSet2) {
        $setChoice = rand(0, 1);
    } elseif ($canMakeSet2) {
        $setChoice = 1;
    }

    // Try selected set first, fallback to the other
    if ($setChoice === 0 && $canMakeSet1) {
        shuffle($tops);
        shuffle($bottoms);
        shuffle($shoes);

        foreach ($tops as $top) {
            foreach ($bottoms as $bottom) {
                if (!coloursMatch($top['colour'], $bottom['colour'], $colourCompatibility, $dutchToEnglish)) continue;

                foreach ($shoes as $shoe) {
                    if (
                        coloursMatch($top['colour'], $shoe['colour'], $colourCompatibility, $dutchToEnglish) &&
                        coloursMatch($bottom['colour'], $shoe['colour'], $colourCompatibility, $dutchToEnglish)
                    ) {
                        return [
                            'top' => $top,
                            'bottom' => $bottom,
                            'dress' => null,
                            'shoes' => $shoe
                        ];
                    }
                }
            }
        }
    }

    if ($setChoice === 1 && $canMakeSet2) {
        shuffle($dresses);
        shuffle($shoes);

        foreach ($dresses as $dress) {
            foreach ($shoes as $shoe) {
                if (coloursMatch($dress['colour'], $shoe['colour'], $colourCompatibility, $dutchToEnglish)) {
                    return [
                        'top' => null,
                        'bottom' => null,
                        'dress' => $dress,
                        'shoes' => $shoe
                    ];
                }
            }
        }
    }

    return null; // No valid outfit found
}
