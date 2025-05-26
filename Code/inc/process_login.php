<?php
require_once 'functions.php';

$conn = db();

$email = trim($_POST['email']);
$password = $_POST['password'];

$result = loginUser($conn, $email, $password);

if ($result === true) {
    header("Location: ../pages/dashboard.php");
    exit;
} else {
    die($result);
}
?>
