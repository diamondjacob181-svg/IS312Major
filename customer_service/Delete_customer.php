<?php
session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_customer'])) {

    $customer_id = intval($_POST['delete_customer_id']);

    // STEP 1: Get all orders for this customer
    $order_stmt = $conn->prepare("SELECT id FROM orders WHERE customer_id = ?");
    $order_stmt->bind_param("i", $customer_id);
    $order_stmt->execute();
    $orders_result = $order_stmt->get_result();

    // STEP 2: Delete order items first
    while ($order = $orders_result->fetch_assoc()) {
        $order_id = $order['id'];

        $item_stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $item_stmt->bind_param("i", $order_id);
        $item_stmt->execute();
    }

    // STEP 3: Delete orders
    $order_delete_stmt = $conn->prepare("DELETE FROM orders WHERE customer_id = ?");
    $order_delete_stmt->bind_param("i", $customer_id);
    $order_delete_stmt->execute();

    // STEP 4: Delete customer LAST (this is where your error was happening before)
    $customer_stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $customer_stmt->bind_param("i", $customer_id);

    if ($customer_stmt->execute()) {

        echo "<script>
                alert('Customer and all related orders deleted successfully!');
                window.location.href='../admin/customers.php';
              </script>";
        exit();

    } else {
        echo "Error deleting customer: " . $conn->error;
    }
}
?>