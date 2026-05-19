<?php
session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {

    $order_id = mysqli_real_escape_string($conn, $_POST['delete_order_id']);

    // Delete related order items first
    $delete_items = "DELETE FROM order_items WHERE order_id = '$order_id'";
    $conn->query($delete_items);

    // Delete the main order
    $delete_order = "DELETE FROM orders WHERE id = '$order_id'";

    if ($conn->query($delete_order) === TRUE) {

        echo "<script>
                alert('Order Deleted Successfully!');
                window.location.href='../admin/orders.php';
              </script>";

        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}
?>