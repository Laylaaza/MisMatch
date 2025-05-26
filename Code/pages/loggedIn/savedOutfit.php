<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../../inc/functions.php';
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

            <!-- Later komen hier outfitkaarten met bewerk/verwijder opties -->
        </main>
    </div>
</body>
</html>
