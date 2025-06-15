<?php
function db() {
    $db = new mysqli('localhost', 'root', '', 'mismatch'); // make sure DB name is correct

    if ($db->connect_errno) {
        echo "Connection failed: " . $db->connect_error;
        exit();
    }

    return $db;
}

// Gebruiker registreren
function registerUser($conn, $name, $email, $password) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        return "Dit e-mailadres is al geregistreerd.";
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Registratie mislukt. Probeer opnieuw.";
    }
}

function loginUser($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Password klopt
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            return true;
        } else {
            return "Wachtwoord is onjuist.";
        }
    } else {
        return "Gebruiker niet gevonden.";
    }
}

function renderSidebarDashboard() {
    echo '
    <aside class="sidebar" id="sidebar">
        <button id="toggleSidebar">☰</button>
        <h2>Mis-Match</h2>
        <nav>
            <a href="loggedIn/upload.php"><i class="fas fa-upload"></i> Upload foto\'s</a>
            <a href="loggedIn/closet.php"><i class="fas fa-tshirt"></i> Bekijk kledingkast</a>
            <a href="loggedIn/generate.php"><i class="fas fa-magic"></i> Genereer een outfit</a>
            <a href="loggedIn/savedOutfit.php"><i class="fas fa-bookmark"></i> Opgeslagen outfits</a>
            <a href="loggedIn/settings.php"><i class="fas fa-cog"></i> Instellingen</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Uitloggen</a>
        </nav>
    </aside>';
}


function renderSidebar() {
    echo '
    <aside class="sidebar" id="sidebar">
        <button id="toggleSidebar">☰</button>
        <h2>Mis-Match</h2>
        <nav>
            <a href="upload.php"><i class="fas fa-upload"></i> Upload foto\'s</a>
            <a href="closet.php"><i class="fas fa-tshirt"></i> Bekijk kledingkast</a>
            <a href="generate.php"><i class="fas fa-magic"></i> Genereer een outfit</a>
            <a href="savedOutfit.php"><i class="fas fa-bookmark"></i> Opgeslagen outfits</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Uitloggen</a>
        </nav>
    </aside>';
}

function logoutUser() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

function handleClothingUpload($conn, $userId, $image, $category, $colour, $occasion, $material) {
    if (!isset($image) || $image['error'] !== 0) {
        return "Afbeelding is niet correct geüpload.";
    }

    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = uniqid() . '_' . basename($image['name']);
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($image['tmp_name'], $targetPath)) {
        return "Afbeelding kon niet worden opgeslagen.";
    }

    $stmt = $conn->prepare("INSERT INTO clothing_items (user_id, image_path, category, colour, occasion, material) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userId, $targetPath, $category, $colour, $occasion, $material);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Er ging iets mis met opslaan.";
    }
}

function deleteClothingItem($conn, $userId, $itemId) {
    $stmt = $conn->prepare("DELETE FROM clothing_items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $itemId, $userId);
    return $stmt->execute();
}

function getItemsByCategoryAndOccasion($conn, $userId, $category, $occasion) {
    $stmt = $conn->prepare("SELECT * FROM clothing_items WHERE user_id = ? AND category = ? AND occasion = ?");
    $stmt->bind_param("iss", $userId, $category, $occasion);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getItemsByCategory($conn, $userId, $category) {
    $stmt = $conn->prepare("SELECT id, image_path FROM clothing_items WHERE user_id = ? AND category = ?");
    $stmt->bind_param("is", $userId, $category);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}






?>
