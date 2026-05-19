<?php
// Include database connection (one folder up in config/)
include '../config/db.php';

// Fetch basic stats for the dashboard
$product_count = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$order_count = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$customer_count = $conn->query("SELECT COUNT(*) as total FROM customers")->fetch_assoc()['total'];
$review_count = $conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bilas Admin | Dashboard</title>
    <link rel="stylesheet" href="../frontend/assets/backendstyle.css">

    
</head>
<body>

<div class="sidebar">
    <!-- Top Branding Group -->
    <h2 class="admin-logo">Bilas Admin</h2>
    
    <!-- Main Section Links Group -->
    <ul class="nav-menu">
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="products.php">Manage Products</a></li>
        <li><a href="orders.php">Manage Orders</a></li>
        <li><a href="customers.php">Customers</a></li>
        <li><a href="manage_reviews.php">Manage Reviews</a></li>
        <li><a href="../frontend/index.php">View Website</a></li>
    </ul>
    
   <div class="logout-vertical-group">
        <a href="logout.php" class="logout-vertical-link" title="Secure Exit">
            <img src="https://media.istockphoto.com/id/1292679147/vector/logout-icon.webp?s=1024x1024&w=is&k=20&c=uPyUMpyRKa7-f3SWoHXlC6u-O2omBDhp1sZHU6CJZ0w=" alt="Logout Icon" class="logout-img">
            <span class="logout-text-label">Logout</span>
        </a>
    </div>
</div>

   
    <div class="main-content">
        <header class="admin-header">
            <h3>Overview Dashboard</h3>
            <div class="user-profile">Admin Panel</div>
        </header>

        <section class="stats-grid">
            <div class="stat-card">
                <h4>Total Products</h4>
                <p><?php echo $product_count; ?></p>
            </div>
            <div class="stat-card">
                <h4>Total Orders</h4>
                <p><?php echo $order_count; ?></p>
            </div>
            <div class="stat-card">
                <h4>Registered Customers</h4>
                <p><?php echo $customer_count; ?></p>
            </div>
              <div class="stat-card">
            <h4>Total Active Reviews</h4>
            <p><?php echo $review_count; ?></p>
        </div>
        </section>

        <section class="card-section">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
                <a href="products.php" class="btn">Add New Product</a>
                <a href="orders.php" class="btn">View All Orders</a>
            </div>
        </section>
    </div>

</body>
</html>
