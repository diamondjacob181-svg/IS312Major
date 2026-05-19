<?php
// 1. DATABASE CONFIGURATION LINK
// Ensure this path matches your directory setup
include '../config/db.php';

// Verify the server connection works flawlessly
if ($conn->connect_error) {
    die("<div style='color:red; font-family:Arial; padding:20px;'><strong>Database Connection Error:</strong> " . $conn->connect_error . "</div>");
}

// 2. FETCH REVIEWS AND CORRESPONDING PRODUCT NAMES
// Uses SQL INNER JOIN to link review data with product names
$sql = "SELECT r.customer_name, r.review_text, r.rating, p.product_name 
        FROM reviews r 
        INNER JOIN products p ON r.product_id = p.id 
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews - Bilas New Guinea</title>
    <!-- Master layout style sheet linking -->
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<header style="background: url('https://d3k81ch9hvuctc.cloudfront.net/company/RmR3VV/images/677ac13d-2098-4c8a-934d-8e47229e3e84.png') no-repeat center center; background-size: cover; color: white; padding: 40px 20px; text-align: center; position: relative;">
    <h1>Bilas New Guinea</h1>
    <p>What our customers say about us</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="customer_register.php">Register</a>
    <a href="reviews.php" class="active">Reviews</a>
    <a href="cart.php">Cart</a>
</nav>

<div class="container">
    <h2 class="section-title">Customer Feedback</h2>

    <div class="reviews-grid">
        <?php
        // 3. COMPILE AND LOOP THROUGH THE RETRIEVED SCHEMA DATA
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="review-card">
                    <div class="quote-icon">“</div>
                    <h3><?php echo htmlspecialchars($row['customer_name']); ?></h3>
                    
                    <!-- Displays which specific PNG item was reviewed -->
                    <span class="category-tag" style="margin-bottom: 5px; display: inline-block;">
                        Product: <?php echo htmlspecialchars($row['product_name']); ?>
                    </span>
                    
                    <!-- Simple Star Generator based on rating number -->
                    <div class="rating-stars" style="color: #ffd700; margin-bottom: 15px;">
                        <?php echo str_repeat('★', $row['rating']) . str_repeat('☆', 5 - $row['rating']); ?>
                    </div>
                    
                    <p class="review-text">
                        <?php echo htmlspecialchars($row['review_text']); ?>
                    </p>
                </div>
                <?php
            }
        } else {
            // Friendly interface markup when database contains 0 entries
            echo '<div class="card" style="grid-column: 1 / -1; text-align:center; padding: 50px;">
                    <p>No reviews yet. Be the first to share your experience!</p>
                  </div>';
        }
        ?>
    </div>

    <!-- Centered Write a Review CTA Wrapper -->
    <div class="review-actions-wrapper" style="text-align: center; margin-top: 50px; margin-bottom: 50px;">
    <a href="add_review.php" class="shop-btn" style="display: inline-block; width: auto; padding: 14px 35px; background-color: #8B0000; color: #ffffff; text-decoration: none; font-family: sans-serif; font-weight: bold; font-size: 15px; border-radius: 25px; letter-spacing: 0.5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: all 0.2s ease;">
        Write a Review
    </a>
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



