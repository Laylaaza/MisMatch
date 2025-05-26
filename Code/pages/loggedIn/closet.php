<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../inc/functions.php';

$conn = db();
$userId = $_SESSION['user_id'];

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    deleteClothingItem($conn, $userId, intval($_POST['item_id']));
    header("Location: closet.php");
    exit;
}

// Filters
$categoryFilter = $_GET['category'] ?? '';
$occasionFilter = $_GET['occasion'] ?? '';

$query = "SELECT * FROM clothing_items WHERE user_id = ?";
$params = [$userId];

if ($categoryFilter) {
    $query .= " AND category = ?";
    $params[] = $categoryFilter;
}
if ($occasionFilter) {
    $query .= " AND occasion = ?";
    $params[] = $occasionFilter;
}

$stmt = $conn->prepare($query);
if (count($params) === 3) {
    $stmt->bind_param("iss", ...$params);
} elseif (count($params) === 2) {
    $stmt->bind_param("is", ...$params);
} else {
    $stmt->bind_param("i", $userId);
}
$stmt->execute();
$result = $stmt->get_result();
$clothingItems = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Kledingkast - Mis-Match</title>
    <link rel="stylesheet" href="../../styling/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php renderSidebar(); ?>

        <main class="dashboard-content">
            <h1>Jouw kledingkast</h1>

            <form method="GET" class="filter-bar">
                <select name="category">
                    <option value="">Alle categorieën</option>
                    <option value="top" <?= $categoryFilter === 'top' ? 'selected' : '' ?>>Top</option>
                    <option value="bottom" <?= $categoryFilter === 'bottom' ? 'selected' : '' ?>>Bottom</option>
                    <option value="shoes" <?= $categoryFilter === 'shoes' ? 'selected' : '' ?>>Shoes</option>
                    <option value="dress" <?= $categoryFilter === 'dress' ? 'selected' : '' ?>>Dress</option>
                </select>

                <select name="occasion">
                    <option value="">Alle gelegenheden</option>
                    <option value="casual" <?= $occasionFilter === 'casual' ? 'selected' : '' ?>>Casual</option>
                    <option value="formeel" <?= $occasionFilter === 'formeel' ? 'selected' : '' ?>>Formeel</option>
                    <option value="feest" <?= $occasionFilter === 'feest' ? 'selected' : '' ?>>Feest</option>
                    <option value="werk" <?= $occasionFilter === 'werk' ? 'selected' : '' ?>>Werk</option>
                </select>

                <button type="submit" class="btn">Filter</button>
            </form>

            <div class="clothing-grid">
                <?php foreach ($clothingItems as $item): ?>
                    <div class="clothing-item fade-in">
                        <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="kledingstuk" class="clothing-img">
                        <p><strong>Categorie:</strong> <?= htmlspecialchars($item['category']) ?></p>
                        <p><strong>Kleur:</strong> <?= htmlspecialchars($item['colour']) ?></p>
                        <p><strong>Materiaal:</strong> <?= htmlspecialchars($item['material']) ?></p>
                        <p><strong>Gelegenheid:</strong> <?= htmlspecialchars($item['occasion']) ?></p>
                        <form method="POST" onsubmit="return confirm('Weet je zeker dat je dit kledingstuk wilt verwijderen?');">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn delete">Verwijder</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
