<?php
// Start active secure admin session tracking
session_start();
include '../config/db.php';

// Verify structural database connection stability
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- NEW FEATURE: INLINE CUSTOMER DELETION PROCESSOR ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_customer'])) {

    $customer_id = mysqli_real_escape_string($conn, $_POST['delete_customer_id']);

    // First delete related orders
    $delete_orders = "DELETE FROM orders WHERE customer_id = '$customer_id'";
    $conn->query($delete_orders);

    // Then delete customer
    $delete_customer = "DELETE FROM customers WHERE id = '$customer_id'";

    if ($conn->query($delete_customer) === TRUE) {

        echo "<script>
                alert('Customer Deleted Successfully!');
                window.location.href='customers.php';
              </script>";
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

// --- FETCH ALL REGISTERED USERS MATCHING YOUR SCHEMA ---
$sql = "SELECT id, full_name, email, phone, address, created_at FROM customers ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profiles | Bilas Admin</title>
    <!-- Stepping up and out of admin to target your master stylesheet layout rules -->
    <link rel="stylesheet" href="../frontend/assets/backendstyle.css">
    <style>
        /* Clean data table layout structures matching your reviews styling profile */
        .reviews-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .reviews-table th, .reviews-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #db2e1e; font-size: 14px; }
        .reviews-table th { background-color: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        .reviews-table tr:hover { background-color: #f8fafc; }
        .text-muted { color: #cd2121; font-size: 12px; }
        
        /* Newly edited sleek table delete action button matching your reviews page exactly */
        .table-delete-btn { background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; padding: 6px 12px; border-radius: 4px; font-weight: 600; font-size: 12px; cursor: pointer; transition: all 0.2s ease; }
        .table-delete-btn:hover { background: #ef4444; color: white; border-color: #dc2626; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
    </style>
</head>
<body>

    <!-- PERSISTENT DARK SIDEBAR -->
    <div class="sidebar">
        <h2 class="admin-logo">Bilas Admin</h2>
        <ul class="nav-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="products.php">Manage Products</a></li>
            <li><a href="orders.php">Manage Orders</a></li>
            <li><a href="customers.php" class="active">Customers</a></li>
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

    <!-- MAIN DASHBOARD WORKSPACE MAIN CONTAINER -->
    <div class="main-content">
        <header class="admin-header">
            <h3>User Profile Directory</h3>
            <div class="user-profile">Admin Panel</div>
        </header>

        <section class="card-section">
            <h3>Customer Feedback Stream</h3>

            <?php if ($result && $result->num_rows > 0): ?>
                <table class="reviews-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Email Address</th>
                            <th>Phone Contact</th>
                            <th>Delivery/Residential Address</th>
                            <th>Date Registered</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><span class="text-muted"><?php echo htmlspecialchars($row['email']); ?></span></td>
                            <td><?php echo !empty($row['phone']) ? htmlspecialchars($row['phone']) : 'N/A'; ?></td>
                            <td style="max-width: 300px; line-height: 1.4;"><?php echo !empty($row['address']) ? htmlspecialchars($row['address']) : 'No Address Provided'; ?></td>
                            <td><span class="text-muted"><?php echo isset($row['created_at']) ? date("Y-m-d H:i", strtotime($row['created_at'])) : 'N/A'; ?></span></td>
                            <td style="text-align: center;">

<form method="POST" action="../customer_service/delete_customer.php" onsubmit="return confirm('Are you completely sure you want to permanently delete customer: <?php echo addslashes($row['full_name']); ?>? This action cannot be undone.');" style="margin: 0; display: inline-block;">
    <input type="hidden" name="delete_customer_id" value="<?php echo $row['id']; ?>">
    <button type="submit" name="delete_customer" class="table-delete-btn">Delete</button>
</form>

                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #e60909; font-style: italic; margin-top: 20px;">No client accounts have been recorded in the customer registry database yet.</p>
            <?php endif; ?>
        </section>
    </div>

</body>
</html>
