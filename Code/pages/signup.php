<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanmelden - Mis-Match</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styling/main.css">
</head>
<body>
    <div class="form-wrapper">
        <h1 class="title">Mis-Match</h1>
        <form action="../inc/process_signup.php" method="POST" class="form-card">

            <h2>Aanmelden</h2>

            <label for="name">Naam</label>
            <input type="text" name="name" id="name" required>

            <label for="email">E-mailadres</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Wachtwoord</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Herhaal wachtwoord</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" class="btn">Account aanmaken</button>
        </form>
    </div>
</body>
</html>
