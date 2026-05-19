<?php
// Start active session tracking and load configuration engine
session_start();
include '../config/db.php';

// Verify structural database connection stability
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize parameter input to protect string parsing operations
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch target catalog record row metrics (including image field)
$result = $conn->query("SELECT * FROM products WHERE id='$id'");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['product_name'] ?? 'Product Details'); ?> - Bilas New Guinea</title>
    <!-- Stepping into your storefront stylesheet rules -->
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Specific adjustments to elevate item display readability */
        .details-wrapper { padding: 40px 20px; max-width: 900px; margin: 0 auto; }
        .details-card { background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; gap: 30px; flex-wrap: wrap; }
        .details-image-side { flex: 1; min-width: 300px; max-width: 400px; }
        .details-image-side img { width: 100%; height: auto; border-radius: 6px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .details-info-side { flex: 1; min-width: 300px; }
        .details-title { font-family: sans-serif; color: #1e293b; font-size: 28px; margin-top: 0; margin-bottom: 15px; }
        .details-desc { font-family: sans-serif; font-size: 15px; line-height: 1.6; color: #475569; margin-bottom: 25px; }
        .metric-group { display: flex; gap: 40px; margin-bottom: 30px; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; padding: 15px 0; }
        .metric-label { font-family: sans-serif; font-size: 14px; color: #64748b; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px; }
        .metric-value { font-family: sans-serif; font-size: 20px; font-weight: bold; color: #8B0000; margin: 0; }
        .cart-action-btn { display: inline-block; background-color: #8B0000; color: white; border: none; padding: 14px 35px; font-size: 16px; font-family: sans-serif; font-weight: bold; border-radius: 4px; cursor: pointer; text-decoration: none; transition: background-color 0.2s; text-align: center; }
        .cart-action-btn:hover { background-color: #a30000; }
    </style>
</head>
<body>

<!-- UPDATED: BACKGROUND IMAGE HEADER WITH REMOVED LINKS AND EXTRA TEXT -->
<header style="background: url('https://d3k81ch9hvuctc.cloudfront.net/company/RmR3VV/images/677ac13d-2098-4c8a-934d-8e47229e3e84.png') no-repeat center center; background-size: cover; color: white; padding: 40px 20px; text-align: center; position: relative;">
    <h1 style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8); margin: 0;">Bilas New Guinea</h1>
    <p style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8); margin: 5px 0 0 0; font-size: 18px;">PNG Fashion & Cultural Store</p>
    <p style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8); margin: 8px 0 0 0; font-size: 14px; font-style: italic; opacity: 0.95;">Explore authentic hand-woven bilums and traditional garments from our local makers.</p>
</header> 

<!-- MAIN WEBSITE NAVIGATION LINK STRIP -->
<nav>
    <a href="index.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="customer_register.php">Register</a>
    <a href="reviews.php">Reviews</a>
</nav>

<!-- CORE WORKSPACE MAIN CONTENT CONTAINER -->
<div class="details-wrapper">
    <?php if ($row): ?>
        <div class="details-card">
            
            <!-- DYNAMIC PRODUCT IMAGE LOADER -->
            <div class="details-image-side">
                <?php if (!empty($row['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                <?php elseif (!empty($row['image'])): ?>
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                <?php else: ?>
                    <img src="placeholder.com" alt="No Image Available">
                <?php endif; ?>
            </div>

            <!-- PRODUCT INFORMATION SPLIT PANEL -->
            <div class="details-info-side">
                <h2 class="details-title"><?php echo htmlspecialchars($row['product_name']); ?></h2>
                <p class="details-desc"><?php echo htmlspecialchars($row['description']); ?></p>

                <div class="metric-group">
                    <div>
                        <p class="metric-label">Price</p>
                        <p class="metric-value">K<?php echo number_format($row['price'], 2); ?></p>
                    </div>
                    <div>
                        <p class="metric-label">Availability</p>
                        <p class="metric-value" style="color: <?php echo ($row['stock_quantity'] > 0) ? '#16a34a' : '#ef4444'; ?>;">
                            <?php echo ($row['stock_quantity'] > 0) ? htmlspecialchars($row['stock_quantity']) . ' In Stock' : 'Out of Stock'; ?>
                        </p>
                    </div>
                </div>

                <!-- Standard E-commerce Cart Redirection Link Action Button -->
                <a href="cart.php?id=<?php echo $row['id']; ?>" class="cart-action-btn">
                    🛒 Add To Cart
                </a>
            </div>

        </div>
    <?php else: ?>
        <div class="details-card" style="text-align: center; justify-content: center;">
            <p style="color: #ef4444; font-weight: bold; font-family: sans-serif;">⚠️ Error: The requested product profile could not be found.</p>
            <a href="shop.php" class="cart-action-btn" style="margin-top: 15px;">Return to Shop</a>
        </div>
    <?php endif; ?>
</div>

<!-- UNIFIED STOREFRONT SITEMAP FOOTER SECTION FROM INDEX.PHP -->
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
