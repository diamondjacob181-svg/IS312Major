<?php
// Start the session listener
session_start();

// Unset all active admin session variables
$_SESSION = array();

// Destroy the session completely on the server
session_destroy();

// Redirect back to the login page immediately
header("Location: ../frontend/index.php");
exit();
?>
