<?php
session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $customer_id = intval($_POST['customer_id']);
    $product_ids = $_POST['product_id'] ?? [];
    $quantities  = $_POST['quantity'] ?? [];
    $grand_total = floatval($_POST['grand_total']);

    // ✅ VALIDATE ARRAYS
    if (!is_array($product_ids) || !is_array($quantities)) {
        die("Invalid cart submission.");
    }

    // ✅ PREVENT EMPTY CART
    if (count($product_ids) == 0) {
        die("Your cart is empty.");
    }

    // ✅ VALIDATE CUSTOMER EXISTS
    $check = $conn->prepare("SELECT id FROM customers WHERE id = ?");
    $check->bind_param("i", $customer_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows == 0) {
        die("<script>
                alert('Invalid Customer ID. Please register before ordering.');
                window.location.href='../frontend/cart.php';
             </script>");
    }

    // ✅ START TRANSACTION
    $conn->begin_transaction();

    try {

        // ✅ INSERT ORDER
        $order_stmt = $conn->prepare("
            INSERT INTO orders (customer_id, total_amount, order_status, created_at)
            VALUES (?, ?, 'Pending', NOW())
        ");

        $order_stmt->bind_param("id", $customer_id, $grand_total);
        $order_stmt->execute();

        $order_id = $order_stmt->insert_id;

        // ✅ INSERT ORDER ITEMS
        for ($i = 0; $i < count($product_ids); $i++) {

            $product_id = intval($product_ids[$i]);
            $qty = intval($quantities[$i]);

            // Skip invalid quantity
            if ($qty <= 0) {
                continue;
            }

            // Get product info
            $product_stmt = $conn->prepare("
                SELECT product_name, price, stock_quantity
                FROM products
                WHERE id = ?
            ");

            $product_stmt->bind_param("i", $product_id);
            $product_stmt->execute();

            $product_res = $product_stmt->get_result();
            $product = $product_res->fetch_assoc();

            if (!$product) {
                continue;
            }

            // ✅ CHECK STOCK
            if ($qty > $product['stock_quantity']) {
                throw new Exception(
                    "Insufficient stock for " .
                    $product['product_name']
                );
            }

            // ✅ CALCULATE SUBTOTAL
            $subtotal = $product['price'] * $qty;

            // ✅ INSERT ORDER ITEM
            $item_stmt = $conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, subtotal)
                VALUES (?, ?, ?, ?)
            ");

            $item_stmt->bind_param(
                "iiid",
                $order_id,
                $product_id,
                $qty,
                $subtotal
            );

            $item_stmt->execute();

            // ✅ UPDATE STOCK
            $stock_stmt = $conn->prepare("
                UPDATE products
                SET stock_quantity = stock_quantity - ?
                WHERE id = ?
            ");

            $stock_stmt->bind_param("ii", $qty, $product_id);
            $stock_stmt->execute();
        }

        // ✅ COMMIT TRANSACTION
        $conn->commit();

        // ✅ CLEAR CART
        unset($_SESSION['cart']);

        echo "<script>
                alert('Order placed successfully!');
                window.location.href='../frontend/index.php';
              </script>";

    } catch (Exception $e) {

        // ✅ ROLLBACK ON ERROR
        $conn->rollback();

        echo "<script>
                alert('Order failed: " . $e->getMessage() . "');
                window.location.href='../frontend/cart.php';
              </script>";
    }
}
?>