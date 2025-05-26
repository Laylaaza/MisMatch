<?php
function generateOutfit($conn, $userId, $occasion) {
    $tops = getItemsByCategoryAndOccasion($conn, $userId, 'top', $occasion);
    $bottoms = getItemsByCategoryAndOccasion($conn, $userId, 'bottom', $occasion);
    $shoes = getItemsByCategoryAndOccasion($conn, $userId, 'shoes', $occasion);
    $dresses = getItemsByCategoryAndOccasion($conn, $userId, 'dress', $occasion);

    $canMakeSet1 = !empty($tops) && !empty($bottoms) && !empty($shoes); // top + bottom + shoes
    $canMakeSet2 = !empty($dresses) && !empty($shoes);                  // dress + shoes

    if (!$canMakeSet1 && !$canMakeSet2) {
        return null; // not enough items
    }

    // Pick randomly between the two sets (only if both are possible)
    if ($canMakeSet1 && $canMakeSet2) {
        $choice = rand(0, 1);
    } elseif ($canMakeSet1) {
        $choice = 0;
    } else {
        $choice = 1;
    }

    if ($choice === 0) {
        return [
            'top' => $tops[array_rand($tops)],
            'bottom' => $bottoms[array_rand($bottoms)],
            'dress' => null,
            'shoes' => $shoes[array_rand($shoes)],
        ];
    } else {
        return [
            'top' => null,
            'bottom' => null,
            'dress' => $dresses[array_rand($dresses)],
            'shoes' => $shoes[array_rand($shoes)],
        ];
    }
}


function getItemsByCategoryAndOccasion($conn, $userId, $category, $occasion) {
    $stmt = $conn->prepare("SELECT * FROM clothing_items WHERE user_id = ? AND category = ? AND occasion = ?");
    $stmt->bind_param("iss", $userId, $category, $occasion);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
