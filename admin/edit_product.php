<?php
// 1. DATABASE CONNECTION & SESSION
session_start();
include '../config/db.php';

// 2. HANDLE THE UPDATE (When user clicks "Update Product")
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $cat = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock_quantity']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $img = mysqli_real_escape_string($conn, $_POST['image_url']);

    $update_query = "UPDATE products SET 
                     product_name='$name', 
                     price='$price', 
                     category='$cat', 
                     stock_quantity='$stock', 
                     description='$desc', 
                     image_url='$img' 
                     WHERE id='$id'";

    if ($conn->query($update_query) === TRUE) {
   echo "<script>alert('Record Updated Successfully!'); window.location.href='products.php';</script>";
exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// 3. FETCH THE EXISTING DATA (To show in the form)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id='$id'");
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Error: Product record not found.");
    }
} else {
    die("Error: Invalid or missing product ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Bilas Admin</title>
    <link rel="stylesheet" href="../frontend/assets/backendstyle.css">
</head>
<body>

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
    </div>
     
    <div class="main-content">
        <header class="admin-header">
            <h3>Inventory Modifications</h3>
            <div class="user-profile">Admin Panel</div>
        </header>

        <div class="form-and-table-flex" style="justify-content: center;">
            <div class="admin-form-card" style="width: 100%; max-width: 600px;">
                <h3>Modify Entry Information (ID: #<?php echo $row['id']; ?>)</h3>
                
                <!-- Action is empty "" so it submits to this same file -->
                <form action="" method="POST" class="form-grid">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <div>
                        <label>Product Name</label>
                        <input type="text" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>" required>
                    </div>

                    <div>
                        <label>Category</label>
                        <select name="category">
                            <option value="Bilums" <?php if($row['category'] == 'Bilums') echo 'selected'; ?>>Bilums</option>
                            <option value="PNG Dresses" <?php if($row['category'] == 'PNG Dresses') echo 'selected'; ?>>PNG Dresses</option>
                            <option value="Cultural Attires" <?php if($row['category'] == 'Cultural Attires') echo 'selected'; ?>>Cultural Attires</option>
                            <option value="PNG Shirts" <?php if($row['category'] == 'PNG Shirts') echo 'selected'; ?>>PNG Shirts</option>
                        </select>
                    </div>

                    <div>
                        <label>Unit Retail Price (K)</label>
                        <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required>
                    </div>

                    <div>
                        <label>Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="<?php echo $row['stock_quantity']; ?>" required>
                    </div>

                    <div class="form-group-full">
                        <label>Image URL</label>
                        <input type="text" name="image_url" value="<?php echo htmlspecialchars($row['image_url']); ?>">
                    </div>

                    <div class="form-group-full">
                        <label>Description</label>
                        <textarea name="description" rows="4" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                    </div>

                    <div class="form-group-full" style="display: flex; gap: 10px; margin-top: 15px;">
                        <button type="submit" class="save-btn" style="flex: 1; margin: 0;">Save Changes</button>
                        <a href="products.php" class="edit-link" style="text-align: center; padding: 12px; line-height: 20px; background: #6c757d; color: white !important; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px;">Cancel</a>
                    </div>
                </form>
            </div>
        </div> 
</body>
</html>


