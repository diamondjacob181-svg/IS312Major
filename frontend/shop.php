<?php
// Include database connection configuration parameters
include '../config/db.php';

// Verify structural connection stability
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query all columns from the products table matching your schema exactly
$sql = "SELECT id, product_name, description, category, price, stock_quantity, image_url, created_at FROM products ORDER BY id DESC";
$result = $conn->query($sql);

// Fallback protection handler if query breaks or table doesn't exist
if (!$result) {
    die("Database Query Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Bilas New Guinea</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<header style="background: url('https://d3k81ch9hvuctc.cloudfront.net/company/RmR3VV/images/677ac13d-2098-4c8a-934d-8e47229e3e84.png') no-repeat center center; background-size: cover; color: white; padding: 40px 20px; text-align: center; position: relative;">
    <h1>Shop PNG Fashion</h1>
    <p>Authentic Cultural Wear & Accessories</p>
</header>

<!-- Added Navigation -->
<nav>
    <a href="index.php">Home</a>
    <a href="shop.php" class="active">Shop</a>
    <a href="customer_register.php">Register</a>
    <a href="cart.php">Cart</a>
</nav>

<div class="container">
    <h2 class="section-title">Our Collection</h2>
    
 <div class="product-grid">
    <?php while($row = $result->fetch_assoc()) { ?>
    <div class="product-card">
        <!-- 1. IMAGE DISPLAY LOGIC -->
        <div class="product-image">
            <?php if(!empty($row['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: 100%; height: 250px; object-fit: cover;">
            <?php else: ?>
                <div class="no-image" style="height: 250px; background: #eee; display: flex; align-items: center; justify-content: center;">No Image</div>
            <?php endif; ?>
        </div>

        <div class="product-info">
            <span class="category-tag"><?php echo htmlspecialchars($row['category']); ?></span>
            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
            <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
            <p class="price">K<?php echo number_format($row['price'], 2); ?></p>
            
          <a href="product_details.php?id=<?php echo $row['id']; ?>" class="view-btn" style="display: inline-block; width: 100%; text-align: center; padding: 12px 0; background-color: #8B0000; color: #ffffff; text-decoration: none; font-family: sans-serif; font-weight: bold; font-size: 14px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); box-sizing: border-box; transition: background-color 0.2s;">
    View Details
</a>

        </div>
    </div>
    <?php } ?>
</div>
<footer class="site-footer">
    <div class="footer-container">
        
        <!-- Column 1 -->
        <div class="footer-column">
            <h3>ABOUT</h3>
            <ul>
                <li><a href="#">Meaning of Bilum & Bilas</a></li>
                <li><a href="#">Our Story</a></li>
                <li><a href="#">Our Founder</a></li>
                <li><a href="#">Meet the Makers</a></li>
                <li><a href="#">Materials & Process</a></li>
                <li><a href="#">Journal & Press</a></li>
            </ul>
        </div>

        <!-- Column 2 -->
        <div class="footer-column">
            <h3>CUSTOMER SERVICE</h3>
            <ul>
                <li><a href="#">Studio Location & Contact</a></li>
                <li><a href="#">Stockists</a></li>
                <li><a href="#">Shipping</a></li>
                <li><a href="#">Returns & Exchange</a></li>
                <li><a href="#">Jewellery Care</a></li>
                <li><a href="#">Bilum Care</a></li>
                <li><a href="#">Terms & Conditions</a></li>
            </ul>
        </div>

        <!-- Column 3 -->
        <div class="footer-column">
            <h3>WHOLESALE</h3>
            <ul>
                <li><a href="#">Enquire & Register</a></li>
            </ul>
        </div>

        <!-- Column 4 -->
        <div class="footer-column">
            <h3>FOLLOW US</h3>
            <ul>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Facebook</a></li>
            </ul>
        </div>

    </div>
    <div class="footer-bottom">
        <p>© 2026 Bilas New Guinea. All rights reserved.</p>
    </div>
</footer>

</body>
</html>



