<?php
session_start();
include '../config/db.php';

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only allow POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form inputs
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $address   = mysqli_real_escape_string($conn, $_POST['address']);

    // Validate required fields
    if (empty($full_name) || empty($email)) {
        header("Location: ../frontend/customer_register.php?error=empty_fields");
        exit();
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM customers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../frontend/customer_register.php?error=email_exists");
        exit();
    }

    // Insert customer
    $stmt = $conn->prepare("INSERT INTO customers (full_name, email, phone, address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $full_name, $email, $phone, $address);

    if ($stmt->execute()) {

        // Get inserted ID
        $customer_id = $stmt->insert_id;

        // Redirect with success + ID
        header("Location: ../frontend/customer_register.php?status=success&id=" . $customer_id);
        exit();

    } else {
        header("Location: ../frontend/customer_register.php?error=failed");
        exit();
    }
}
?>
