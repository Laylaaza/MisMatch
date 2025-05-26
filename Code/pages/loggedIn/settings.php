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
    <title>Instellingen - Mis-Match</title>
    <link rel="stylesheet" href="../../styling/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php renderSidebar(); ?>

        <main class="dashboard-content">
            <h1>Instellingen</h1>
            <p>Pas je voorkeuren of accountgegevens aan.</p>

            <!-- Instellingenformulier komt hier -->
        </main>
    </div>
</body>
</html>
