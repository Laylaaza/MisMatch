<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../inc/functions.php';
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Mis-Match</title>
    <link rel="stylesheet" href="../styling/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>
<body>
    <div class="dashboard-container">
        <?php renderSidebarDashboard(); ?>

        <main class="dashboard-content">
            <h1>Welkom terug, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <p>Kies een optie in het menu om aan de slag te gaan.</p>
        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
