<?php
// review_service/delete_review.php

session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// --- HANDLE REVIEW DELETION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_review_id'])) {

    $review_id = mysqli_real_escape_string($conn, $_POST['delete_review_id']);

    $delete_sql = "DELETE FROM reviews WHERE id = '$review_id'";

    if ($conn->query($delete_sql) === TRUE) {

        echo "<script>
                alert('Review Deleted Successfully!');
                window.location.href='../admin/manage_reviews.php';
              </script>";
        exit();

    } else {

        die("Database Deletion Failure Error: " . $conn->error);

    }
} else {

    header("Location: ../admin/manage_reviews.php");
    exit();

}
?>
