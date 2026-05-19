<?php
// admin/manage_reviews.php
session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Fetch all dynamic user comments from database rows
$reviews_query = $conn->query("SELECT * FROM reviews ORDER BY id DESC");
if (!$reviews_query) {
    die("Database Query Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bilas Admin | Manage Reviews</title>
    <link rel="stylesheet" href="../frontend/assets/backendstyle.css">

    <style>
        .reviews-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .reviews-table th, .reviews-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #db2e1e; font-size: 14px; }
        .reviews-table th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        .reviews-table tr:hover { background-color: #f8fafc; }
        .stars-display { color: #f59e0b; font-size: 14px; white-space: nowrap; }
        .text-muted { color: #cd2121; font-size: 12px; }
        .table-delete-btn { background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 6px 12px; border-radius: 4px; font-weight: 600; font-size: 12px; cursor: pointer; transition: all 0.2s ease; }
        .table-delete-btn:hover { background: #ef4444; color: white; border-color: #dc2626; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
    </style> 
    
    
</head>
<body>

<div class="sidebar">
    <h2 class="admin-logo">Bilas Admin</h2>
    <ul class="nav-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="products.php">Manage Products</a></li>
        <li><a href="orders.php">Manage Orders</a></li>
        <li><a href="customers.php">Customers</a></li>
        <li><a href="manage_reviews.php" class="active">Manage Reviews</a></li>
        <li><a href="../frontend/index.php">View Website</a></li>
    </ul>
      <div class="logout-vertical-group">
        <a href="logout.php" class="logout-vertical-link" title="Secure Exit">
            <img src="https://media.istockphoto.com/id/1292679147/vector/logout-icon.webp?s=1024x1024&w=is&k=20&c=uPyUMpyRKa7-f3SWoHXlC6u-O2omBDhp1sZHU6CJZ0w=" alt="Logout Icon" class="logout-img">
            <span class="logout-text-label">Logout</span>
        </a>
    </div>
</div>

</div>

<div class="main-content">
    <header class="admin-header">
        <h3>Manage Product Reviews</h3>
        <div class="user-profile">Admin Panel</div>
    </header>

    <section class="card-section">
        <h3>Customer Feedback Stream</h3>

        <?php if ($reviews_query->num_rows > 0): ?>
            <table class="reviews-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Product ID</th>
                        <th>Rating</th>
                        <th>Review Message</th>
                        <th>Date Submitted</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reviews_query->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><span class="text-muted">Product #<?php echo $row['product_id']; ?></span></td>
                            <td>
                                <div class="stars-display">
                                    <?php 
                                    $rating = intval($row['rating']);
                                    echo str_repeat('★', $rating) . str_repeat('☆', max(0, 5 - $rating)); 
                                    ?>
                                </div>
                            </td>
                            <td style="max-width: 300px; line-height: 1.4;"><?php echo htmlspecialchars($row['review_text']); ?></td>
                            <td><span class="text-muted"><?php echo isset($row['created_at']) ? date('Y-m-d H:i', strtotime($row['created_at'])) : 'N/A'; ?></span></td>
                            <td style="text-align: center;">
                                <!-- Linked to external review service -->
                                <form method="POST" action="../review_service/delete_reviews.php" style="margin: 0; display: inline-block;">
                                    <input type="hidden" name="delete_review_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="table-delete-btn" onclick="return confirm('Are you sure you want to permanently delete this review?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #e60909; font-style: italic; margin-top: 20px;">No customer reviews found in database.</p>
        <?php endif; ?>
    </section>
</div>

</body>
</html>







