<?php
// Start session and include database connection
session_start();
include '../config/db.php';

// Verify database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- 1. HANDLE ADDING NEW PRODUCT ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name  = $_POST['product_name'];
    $desc  = $_POST['description'];
    $cat   = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity']; 
    $img   = $_POST['image_url'];

    // Prepared statement matching your SQL table structure
    $stmt = $conn->prepare("INSERT INTO products (product_name, description, category, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdii", $name, $desc, $cat, $price, $stock, $img);

    if ($stmt->execute()) {
        echo "<script>alert('Product Added Successfully!'); window.location.href='product.php';</script>";
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}

// --- 2. FETCH PRODUCTS FOR THE TABLE ---
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | Bilas Admin</title>
    <link rel="stylesheet" href="../frontend/assets/backendstyle.css">
</head>
<body>

    <!-- PERSISTENT SIDEBAR (Matching Dashboard Style) -->
    <div class="sidebar">
        <h2 class="admin-logo">Bilas Admin</h2>
        
        <ul class="nav-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="product.php" class="active">Manage Products</a></li>
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

    <!-- MAIN WORKSPACE -->
    <div class="main-content">
        <header class="admin-header">
            <h3>Inventory Management</h3>
            <div class="user-profile">Admin Panel</div>
        </header>

        <section class="form-and-table-flex">
            
            <!-- 1. ADD PRODUCT FORM CARD -->
            <div class="stat-card" style="text-align: left; margin-bottom: 30px;">
                <h3 style="color: #8B0000; margin-bottom: 20px;">Add New PNG Fashion Item</h3>
                <form method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display:block; margin-bottom:5px;">Product Name</label>
                        <input type="text" name="product_name" style="width:100%; padding:10px;" required>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:5px;">Category Group</label>
                        <select name="category" style="width:100%; padding:10px;">
                            <option value="Bilums">Bilums</option>
                            <option value="PNG Dresses">PNG Dresses</option>
                            <option value="Cultural Attires">Cultural Attires</option>
                            <option value="PNG Shirts">PNG Shirts</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:5px;">Unit Price (K)</label>
                        <input type="number" step="0.01" name="price" style="width:100%; padding:10px;" required>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom:5px;">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="0" style="width:100%; padding:10px;" required>
                    </div>
                    <div style="grid-column: span 2;">
                        <label style="display:block; margin-bottom:5px;">Image URL</label>
                        <input type="text" name="image_url" placeholder="://example.com" style="width:100%; padding:10px;">
                    </div>
                    <div style="grid-column: span 2;">
                        <label style="display:block; margin-bottom:5px;">Specifications & Description</label>
                        <textarea name="description" rows="3" style="width:100%; padding:10px;" required></textarea>
                    </div>
                    <div style="grid-column: span 2;">
                        <button type="submit" name="add_product" class="btn">Save Product to Shop</button>
                    </div>
                </form>
            </div>

            <!-- 2. INVENTORY DATA TABLE -->
            <div class="stat-card" style="text-align: left; overflow-x: auto;">
                <h3 style="color: #8B0000; margin-bottom: 20px;">Current Inventory</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f5f2;">
                        <tr style="border-bottom: 2px solid #ddd;">
                            <th style="padding: 12px;">Image</th>
                            <th style="padding: 12px;">Product Name</th>
                            <th style="padding: 12px;">Category</th>
                            <th style="padding: 12px;">Price</th>
                            <th style="padding: 12px;">Stock</th>
                            <th style="padding: 12px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;">
                                    <?php if(!empty($row['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Img" style="width:50px; border-radius:4px;">
                                    <?php else: ?>
                                        <span style="font-size:0.8rem; color:#999;">No Img</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 10px;"><strong><?php echo htmlspecialchars($row['product_name']); ?></strong></td>
                                <td style="padding: 10px;"><?php echo htmlspecialchars($row['category']); ?></td>
                                <td style="padding: 10px;">K<?php echo number_format($row['price'], 2); ?></td>
                                <td style="padding: 10px;"><?php echo $row['stock_quantity']; ?></td>
                                <td style="padding: 10px;">
                                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" style="color:#8B0000; text-decoration:none;">Edit</a>
                                    |
                                    <a href="../product-service/delete_product.php?id=<?php echo $row['id']; ?>" 
                                       style="color:#666;" 
                                       onclick="return confirm('Delete this item?')">
                                       Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="padding:20px; text-align:center;">No products found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

</body>
</html>