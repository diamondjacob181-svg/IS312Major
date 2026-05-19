<?php
// Start active session tracking to check customer validation states
session_start();

// Step up two levels out of frontend/review_service/ into project root, then into config/
include '../../config/db.php';

// Verify structural database connection stability
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. AUTHENTICATION GATEWAY PROTECTION RUNTIME
if (!isset($_SESSION['customer_id'])) {
    // If not logged in, redirect back out to frontend/add_review.php
    header("Location: ../add_review.php?error=auth_required");
    exit();
}

// 2. HANDLE FORM DATA INSERTION INTERCEPTION
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    
    // Capture and clean data
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $product_id    = mysqli_real_escape_string($conn, $_POST['product_id']);
    $rating        = mysqli_real_escape_string($conn, $_POST['rating']);
    $review_text   = mysqli_real_escape_string($conn, $_POST['review_text']);

    // Check for empty values
    if (!empty($customer_name) && !empty($product_id) && !empty($rating) && !empty($review_text)) {
        
        // Prepared statements mapping to your reviews table schema layout
        $stmt = $conn->prepare("INSERT INTO reviews (customer_name, product_id, rating, review_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $customer_name, $product_id, $rating, $review_text);

        if ($stmt->execute()) {
            // Success: redirect out to live page frontend/reviews.php
            header("Location: ../reviews.php?status=success");
            exit();
        } else {
            header("Location: ../add_review.php?error=db_failure");
            exit();
        }
        $stmt->close();
    } else {
        header("Location: ../add_review.php?error=empty_fields");
        exit();
    }
} else {
    header("Location: ../reviews.php");
    exit();
}
?>

