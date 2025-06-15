<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../inc/functions.php';

$conn = db();
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, occasion, top_path, bottom_path, dress_path, shoes_path FROM saved_outfits WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$outfits = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Opgeslagen outfits - Mis-Match</title>
    <link rel="stylesheet" href="../../styling/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php renderSidebar(); ?>

        <main class="dashboard-content">
            <h1>Opgeslagen outfits</h1>
            <p>Hier zie je een overzicht van alle outfits die je hebt opgeslagen.</p>

            <?php if (empty($outfits)): ?>
                <p>Je hebt nog geen outfits opgeslagen.</p>
            <?php else: ?>
                <div class="clothing-grid">
                    <?php foreach ($outfits as $outfit): ?>
                        <div class="clothing-item">
                            <p><strong><?= htmlspecialchars(ucfirst($outfit['occasion'])) ?></strong></p>
                            <?php if ($outfit['dress_path']): ?>
                                <img src="<?= htmlspecialchars($outfit['dress_path']) ?>" class="clothing-img" alt="Dress">
                            <?php else: ?>
                                <?php if ($outfit['top_path']): ?>
                                    <img src="<?= htmlspecialchars($outfit['top_path']) ?>" class="clothing-img" alt="Top">
                                <?php endif; ?>
                                <?php if ($outfit['bottom_path']): ?>
                                    <img src="<?= htmlspecialchars($outfit['bottom_path']) ?>" class="clothing-img" alt="Bottom">
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($outfit['shoes_path']): ?>
                                <img src="<?= htmlspecialchars($outfit['shoes_path']) ?>" class="clothing-img" alt="Shoes">
                            <?php endif; ?>

                            <form action="../../inc/delete_outfit.php" method="POST">
                                <input type="hidden" name="outfit_id" value="<?= $outfit['id'] ?>">
                                <button type="submit" class="btn delete">Verwijder outfit</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
