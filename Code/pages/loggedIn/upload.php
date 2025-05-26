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
        $_POST['material']
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
    <title>Upload kledingstuk - Mis-Match</title>
    <link rel="stylesheet" href="../../styling/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="../../inc/function.js" defer></script>
</head>
<body>
    <div class="dashboard-container">
        <?php renderSidebar(); ?>

        <main class="dashboard-content">
            <h1>Upload kledingstuk</h1>
            <p>Upload hier een foto en stel de kenmerken van je kledingstuk in.</p>

            <?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="form-card">
                <label for="image">Kledingfoto</label>
                <input type="file" name="image" id="image" accept="image/*" required>
                <img id="preview" style="max-width: 200px; margin-top: 10px; display: none;">

                <label for="category">Categorie</label>
                <select name="category" id="category" required>
                    <option value="">-- Kies categorie --</option>
                    <option value="top">Top</option>
                    <option value="bottom">Bottom</option>
                    <option value="shoes">Shoes</option>
                    <option value="dress">Dress</option>
                </select>

                <label for="colour">Kleur</label>
                <input type="text" name="colour" id="colour" placeholder="Bijv. zwart, rood">

                <label for="material">Materiaal</label>
                <input type="text" name="material" id="material" placeholder="Bijv. katoen, denim">

                <label for="occasion">Gelegenheid</label>
                <select name="occasion" id="occasion" required>
                    <option value="">-- Kies gelegenheid --</option>
                    <option value="casual">Casual</option>
                    <option value="formeel">Formeel</option>
                    <option value="feest">Feest</option>
                    <option value="werk">Werk</option>
                </select>

                <button type="submit" class="btn">Upload kledingstuk</button>
            </form>
        </main>
    </div>
</body>
</html>
