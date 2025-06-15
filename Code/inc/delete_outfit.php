<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['outfit_id'])) {
    http_response_code(400);
    echo "Ongeldige aanvraag.";
    exit;
}

$conn = db();
$userId = $_SESSION['user_id'];
$outfitId = intval($_POST['outfit_id']);

$stmt = $conn->prepare("DELETE FROM saved_outfits WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $outfitId, $userId);
$stmt->execute();

header("Location: ../pages/loggedIn/savedOutfit.php");
exit;
?>
