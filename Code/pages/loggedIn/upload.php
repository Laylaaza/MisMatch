<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = db();
    $userId = $_SESSION['user_id'];

    $result = handleClothingUpload(
        $conn,
        $userId,
        $_FILES['image'],
        $_POST['category'],
        $_POST['colour'],
        $_POST['occasion'],
        '' // Materiaal verwijderd
    );

    if ($result === true) {
        header("Location: closet.php");
        exit;
    } else {
        $error = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Kledingstuk uploaden - Mis-Match</title>
    <link rel="stylesheet" href="../../styling/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="../../inc/functions.js" defer></script>
</head>
<body>
<div class="dashboard-container modern-upload">
    <?php renderSidebar(); ?>

    <main class="dashboard-content modern-upload-content">
        <h1>Kledingstuk uploaden</h1>
        <p>Upload een foto en geef de kenmerken van je kledingstuk op.</p>

        <?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="modern-upload-form">
            <div class="left-panel">
                <label for="image">Kledingfoto</label>
                <input type="file" name="image" id="image" accept="image/*" required>
                <img id="preview" class="modern-preview" style="display: none;">
            </div>

            <div class="right-panel">
                <label for="category">Categorie</label>
                <select name="category" id="category" required>
                    <option value="">-- Kies categorie --</option>
                    <option value="top">Bovenstuk</option>
                    <option value="bottom">Onderstuk</option>
                    <option value="shoes">Schoenen</option>
                    <option value="dress">Jurk</option>
                </select>

                <label for="colour">Kleur</label>
                <div class="colour-dropdown" id="colourDropdown">
                    <div class="colour-dropdown-toggle" id="selectedColour">-- Kies kleur --</div>
                    <div class="colour-dropdown-menu">
                        <?php
                        $colours = [
                            'zwart' => '#000000',
                            'wit' => '#ffffff',
                            'rood' => '#c62828',
                            'blauw' => '#1e88e5',
                            'groen' => '#43a047',
                            'geel' => '#fdd835',
                            'grijs' => '#9e9e9e',
                            'beige' => '#f5f5dc',
                            'bruin' => '#8B4513'
                        ];

                        foreach ($colours as $name => $hex): ?>
                            <label>
                                <input type="radio" name="colour" value="<?= $name ?>" required>
                                <span class="colour-box" style="background-color: <?= $hex ?>"></span>
                                <?= ucfirst($name) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <label for="occasion">Gelegenheid</label>
                <select name="occasion" id="occasion" required>
                    <option value="">-- Kies gelegenheid --</option>
                    <option value="casual">Casual</option>
                    <option value="formeel">Formeel</option>
                    <option value="feest">Feest</option>
                    <option value="werk">Werk</option>
                </select>

                <button type="submit" class="btn">Kledingstuk uploaden</button>
            </div>
        </form>
    </main>
</div>

<script>
    const options = document.querySelectorAll('.colour-dropdown input[type="radio"]');
    const dropdown = document.getElementById('colourDropdown');
    const toggle = document.getElementById('selectedColour');

    toggle.addEventListener('click', () => {
        dropdown.classList.toggle('open');
    });

    options.forEach(radio => {
        radio.addEventListener('change', () => {
            toggle.textContent = radio.parentElement.textContent.trim();
            dropdown.classList.remove('open');
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
</script>
</body>
</html>
