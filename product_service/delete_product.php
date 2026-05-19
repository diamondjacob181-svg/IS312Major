<?php
session_start();
include '../config/db.php';

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product ID");
}

$id = intval($_GET['id']);

// Prepared DELETE statement
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    // Redirect back to product page
    header("Location: ../admin/product.php?msg=deleted");
    exit();
} else {
    echo "Error deleting product: " . $conn->error;
}
?>