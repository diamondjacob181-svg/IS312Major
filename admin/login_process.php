<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // In a real project, passwords should be hashed (password_hash/password_verify)
    // Here we check a simple plain text password for your local testing
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        header("Location: dashboard.php");
    } else {
        header("Location: login.php?error=1");
    }
    exit();
}
?>
