<?php
require_once 'functions.php';

$conn = db();

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    die("Wachtwoorden komen niet overeen.");
}

$result = registerUser($conn, $name, $email, $password);

if ($result === true) {
    header("Location: ../pages/login.php?success=1");
    exit;
} else {
    die($result);
}
?>
