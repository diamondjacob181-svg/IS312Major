<?php
session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ==========================================
// UPDATE ORDER STATUS
// ==========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $order_id = intval($_POST['order_id']);
    $new_status = trim($_POST['order_status']);

    // Allowed statuses
    $allowed_status = [
        'Pending',
        'Processing',
        'Completed',
        'Cancelled'
    ];

    // Validate status
    if (!in_array($new_status, $allowed_status)) {

        echo "<script>
                alert('Invalid order status.');
                window.location.href='../admin/orders.php';
              </script>";

        exit();
    }

    // Update order
    $update_stmt = $conn->prepare("
        UPDATE orders
        SET order_status = ?
        WHERE id = ?
    ");

    $update_stmt->bind_param(
        "si",
        $new_status,
        $order_id
    );

    if ($update_stmt->execute()) {

        echo "<script>
                alert('Order Status Updated Successfully!');
                window.location.href='../admin/orders.php';
              </script>";

    } else {

        echo "<script>
                alert('Failed to update order.');
                window.location.href='../admin/orders.php';
              </script>";
    }
}
?>