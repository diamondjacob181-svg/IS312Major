<?php
// admin/orders.php
session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all orders
$orders_query = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bilas Admin | Manage Orders</title>
    <link rel="stylesheet" href="../frontend/assets/backendstyle.css">

    <style>
        .orders-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; border-radius: 8px; overflow: hidden; }
        .orders-table th, .orders-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #db2e1e; font-size: 14px; }
        .orders-table th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; }
        .text-muted { color: #cd2121; font-size: 12px; }
        .status-select { padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 13px; }
        .update-btn { background: #8B0000; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; margin-left: 5px; }
        .update-btn:hover { background: #a30000; }
        .items-breakdown-box { margin-top: 5px; padding: 8px; background: #f8fafc; border-radius: 4px; border-left: 3px solid #8B0000; }
        .items-list-text { margin: 3px 0; font-size: 13px; color: #334155; }
    </style>
</head>

<body>

<div class="sidebar">
    <h2 class="admin-logo">Bilas Admin</h2>
    <ul class="nav-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="products.php">Manage Products</a></li>
        <li><a href="orders.php" class="active">Manage Orders</a></li>
        <li><a href="customers.php">Customers</a></li>
        <li><a href="manage_reviews.php">Manage Reviews</a></li>
        <li><a href="../frontend/index.php">View Website</a></li>
    </ul>
</div>

<div class="main-content">
    <header class="admin-header">
        <h3>Manage System Orders</h3>
    </header>

    <section class="card-section">
        <h3>Active System Purchase Orders</h3>

        <?php if ($orders_query && $orders_query->num_rows > 0): ?>

            <table class="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer ID</th>
                        <th>Purchased Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $orders_query->fetch_assoc()): ?>
                        <?php
                            $order_id = $row['id'];
                            $status_lower = strtolower($row['order_status']);
                            $order_total = $row['total_amount'];
                        ?>

                        <tr>
                            <td><strong>#<?php echo $order_id; ?></strong></td>

                            <td>
                                <span class="text-muted">
                                    Customer #<?php echo htmlspecialchars($row['customer_id']); ?>
                                </span>
                            </td>

                            <td>
                                <div class="items-breakdown-box">
                                    <?php
                                    $item_stmt = $conn->prepare("
                                        SELECT oi.quantity, oi.subtotal, p.product_name
                                        FROM order_items oi
                                        JOIN products p ON oi.product_id = p.id
                                        WHERE oi.order_id = ?
                                    ");

                                    $item_stmt->bind_param("i", $order_id);
                                    $item_stmt->execute();

                                    $items_result = $item_stmt->get_result();

                                    if ($items_result && $items_result->num_rows > 0):
                                        while ($item = $items_result->fetch_assoc()):
                                    ?>
                                        <p class="items-list-text">
                                            📦 <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                            (x<?php echo $item['quantity']; ?>)
                                            - K<?php echo number_format($item['subtotal'], 2); ?>
                                        </p>
                                    <?php
                                        endwhile;
                                    else:
                                    ?>
                                        <span style="color:#94a3b8; font-size:12px;">No item details found</span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td><strong>K<?php echo number_format($order_total, 2); ?></strong></td>

                            <td>
                                <span style="font-weight:bold; text-transform:uppercase; font-size:12px;
                                    color: <?php
                                        echo ($status_lower == 'completed') ? '#16a34a' :
                                            (($status_lower == 'pending') ? '#f59e0b' : '#2563eb');
                                    ?>;">
                                    <?php echo htmlspecialchars($row['order_status']); ?>
                                </span>
                            </td>

                            <td class="text-muted">
                                <?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?>
                            </td>

                            <td>
                                <!-- UPDATE STATUS (NOW HANDLED BY SERVICE FILE) -->
                                <form method="POST"
                                      action="../order_service/update_order.php"
                                      style="display:flex; align-items:center;">

                                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

                                    <select name="order_status" class="status-select">
                                        <option value="Pending" <?php if($status_lower=='pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Processing" <?php if($status_lower=='processing') echo 'selected'; ?>>Processing</option>
                                        <option value="Completed" <?php if($status_lower=='completed') echo 'selected'; ?>>Completed</option>
                                        <option value="Cancelled" <?php if($status_lower=='cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>

                                    <button type="submit" class="update-btn">
                                        Update
                                    </button>
                                </form>

                                <!-- DELETE ORDER -->
                                <form method="POST"
                                      action="../order_service/delete_order.php"
                                      onsubmit="return confirm('Delete Order #<?php echo $order_id; ?>?');"
                                      style="margin-top:8px;">

                                    <input type="hidden" name="delete_order_id" value="<?php echo $order_id; ?>">

                                    <button type="submit" name="delete_order" class="update-btn" style="background:#dc2626;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p style="color:#e60909; font-style:italic; margin-top:20px;">
                No transaction records found in database.
            </p>
        <?php endif; ?>

    </section>
</div>

</body>
</html>