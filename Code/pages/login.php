<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Mis-Match</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styling/main.css">
</head>
<body>
    <div class="form-wrapper">
        <h1 class="title">Mis-Match</h1>
        <form action="../inc/process_login.php" method="POST" class="form-card">

            <h2>Inloggen</h2>

            <label for="email">E-mailadres</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Wachtwoord</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" class="btn">Inloggen</button>
        </form>
    </div>
</body>
</html>
