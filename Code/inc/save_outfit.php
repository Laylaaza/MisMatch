<?php
session_start();
require_once '../inc/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['outfit_data'])) {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

$data = json_decode($_POST['outfit_data'], true);

if (!$data) {
    http_response_code(400);
    echo "Invalid data";
    exit;
}

$conn = db();
$userId = $_SESSION['user_id'];
$occasion = $data['occasion'] ?? '';

$top = $data['top']['image_path'] ?? null;
$bottom = $data['bottom']['image_path'] ?? null;
$dress = $data['dress']['image_path'] ?? null;
$shoes = $data['shoes']['image_path'] ?? null;

$stmt = $conn->prepare("
    INSERT INTO saved_outfits (user_id, occasion, top_path, bottom_path, dress_path, shoes_path)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isssss", $userId, $occasion, $top, $bottom, $dress, $shoes);

if ($stmt->execute()) {
    header("Location: ../pages/loggedIn/savedOutfit.php");
    exit;
} else {
    echo "Fout bij opslaan.";
}
