<?php
session_start();

include 'includes/db.php';
include 'includes/functions.php';

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$confirm_password = $_SESSION['confirm_password'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input
    if (empty($username)) {
        $_SESSION['errors'] = ['Username cannot be empty!'];
        $_SESSION['password'] = $password;
        $_SESSION['confirm_password'] = $confirm_password;
        header("Location: register.php");
        exit();
    }
    if (empty($password)) {
        $_SESSION['errors'] = ['Password cannot be empty!'];
        $_SESSION['username'] = $username;
        $_SESSION['confirm_password'] = $confirm_password;
        header("Location: register.php");
        exit();
    }
    if ($password !== $confirm_password) {
        $_SESSION['errors'] = ['Both passwords do not match!'];
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("Location: register.php");
        exit();
    }

    // Register the user
    if (registerUser($username, $password)) {
        $_SESSION['registration_success'] = true;
        $_SESSION['username'] = $username;
        header("Location: login.php?username=" . urlencode($username));
        exit();
    } else {
        header("Location: register.php");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>
