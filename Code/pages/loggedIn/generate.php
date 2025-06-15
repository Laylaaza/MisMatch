<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../inc/functions.php';
require_once '../../inc/generate_outfit.php';

$conn = db();
$userId = $_SESSION['user_id'];

$outfit = null;
$occasion = $_POST['occasion'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $occasion) {
    $outfit = generateOutfit($conn, $userId, $occasion);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Outfit genereren - Mis-Match</title>
    <link rel="stylesheet" href="../../styling/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dashboard-container">
    <?php renderSidebar(); ?>

    <main class="dashboard-content">
        <h1>Genereer een outfit</h1>
        <form method="POST" class="filter-bar">
            <label for="occasion">Gelegenheid</label>
            <select name="occasion" id="occasion" required>
                <option value="">-- Kies gelegenheid --</option>
                <option value="casual" <?= $occasion === 'casual' ? 'selected' : '' ?>>Casual</option>
                <option value="formeel" <?= $occasion === 'formeel' ? 'selected' : '' ?>>Formeel</option>
                <option value="feest" <?= $occasion === 'feest' ? 'selected' : '' ?>>Feest</option>
                <option value="werk" <?= $occasion === 'werk' ? 'selected' : '' ?>>Werk</option>
            </select>
            <button type="submit" class="btn">Genereer</button>
        </form>

        <?php if ($outfit === null && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p style="color:red;">Niet genoeg kledingstukken om een outfit te genereren.</p>
        <?php elseif ($outfit): ?>
            <div class="outfit-preview">
                <div class="clothing-grid">
                    <?php if ($outfit['dress']): ?>
                        <div class="clothing-item">
                            <img src="<?= htmlspecialchars($outfit['dress']['image_path']) ?>" class="clothing-img">
                            <p><strong>Dress</strong></p>
                            <button class="change-btn" data-category="dress" data-id="<?= $outfit['dress']['id'] ?>">Verander</button>
                        </div>
                    <?php else: ?>
                        <div class="clothing-item">
                            <img src="<?= htmlspecialchars($outfit['top']['image_path']) ?>" class="clothing-img">
                            <p><strong>Top</strong></p>
                            <button class="change-btn" data-category="top" data-id="<?= $outfit['top']['id'] ?>">Verander</button>
                        </div>
                        <div class="clothing-item">
                            <img src="<?= htmlspecialchars($outfit['bottom']['image_path']) ?>" class="clothing-img">
                            <p><strong>Bottom</strong></p>
                            <button class="change-btn" data-category="bottom" data-id="<?= $outfit['bottom']['id'] ?>">Verander</button>
                        </div>
                    <?php endif; ?>
                    <div class="clothing-item">
                        <img src="<?= htmlspecialchars($outfit['shoes']['image_path']) ?>" class="clothing-img">
                        <p><strong>Shoes</strong></p>
                        <button class="change-btn" data-category="shoes" data-id="<?= $outfit['shoes']['id'] ?>">Verander</button>
                    </div>
                </div>

                <div class="save-container">
                    <form method="POST" action="../../inc/save_outfit.php">
                        <input type="hidden" name="outfit_data" value='<?= json_encode($outfit) ?>'>
                        <button type="submit" class="save-btn">Outfit opslaan</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<div class="popup-overlay" id="popupOverlay" style="display: none;">
    <div class="popup-content">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <div class="popup-grid" id="popupGrid"></div>
    </div>
</div>

<script src="../../inc/functions.js"></script>
</body>
</html>
