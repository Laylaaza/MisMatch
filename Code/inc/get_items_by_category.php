<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

require_once 'functions.php';

$conn = db();
$userId = $_SESSION['user_id'];
$category = $_GET['category'] ?? '';
$occasion = $_GET['occasion'] ?? '';

if (!$category || !$occasion) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing category or occasion']);
    exit;
}

$items = getItemsByCategoryAndOccasion($conn, $userId, $category, $occasion);

header('Content-Type: application/json');
echo json_encode($items);
