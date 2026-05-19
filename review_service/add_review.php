<?php
// 1. INJECT DATABASE ENGINE
include '../config/db.php';

// Verify the server connection works flawlessly
if ($conn->connect_error) {
    die("<div style='color:red; font-family:Arial; padding:20px;'><strong>Database Connection Error:</strong> " . $conn->connect_error . "</div>");
}

// 2. FETCH ACTIVE PRODUCTS FOR THE DROPDOWN
$products_result = $conn->query("SELECT id, product_name FROM products ORDER BY product_name ASC");

// 3. HANDLE FORM SUBMISSION LOGIC
$status_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    if (!empty($customer_name) && !empty($product_id) && !empty($rating) && !empty($review_text)) {
        
        // FIXED: Removed the non-existent 'name' column and mapped strictly to 'full_name'
        $check_customer = $conn->prepare("SELECT id FROM customers WHERE full_name = ? LIMIT 1");
        $check_customer->bind_param("s", $customer_name);
        $check_customer->execute();
        $check_result = $check_customer->get_result();
        
        if ($check_result->num_rows > 0) {
            // Customer exists -> Proceed to save the review securely
            $stmt = $conn->prepare("INSERT INTO reviews (customer_name, product_id, rating, review_text) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siis", $customer_name, $product_id, $rating, $review_text);

            if ($stmt->execute()) {
                echo "<script>alert('Thank you! Your review has been published.'); window.location.href='customer_reviews.php';</script>";
                exit();
            } else {
                $status_message = "<div class='status-alert status-error'>⚠️ Database Error: Unable to save review.</div>";
            }
            $stmt->close();
        } else {
            // NOTIFICATION: Trigger alert if name is not found in the customer registry
            $status_message = "<div class='status-alert status-error' style='background: #fee2e2; color: #dc2626; padding: 12px; border: 1px solid #fca5a5; border-radius: 4px; font-family: Arial, sans-serif; font-weight: bold; margin-bottom: 15px;'>❌ Access Denied: You cannot create a review. Only verified customers can make reviews.</div>";
        }
        $check_customer->close();
        
    } else {
        $status_message = "<div class='status-alert status-error'>⚠️ Please populate all required form inputs.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write a Review - Bilas New Guinea</title>
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Injected alert box styling to match customer_register.php natively */
        .status-alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            font-weight: bold;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<header>
    <h1>Share Your Experience</h1>
    <p>Help our cultural community grow</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="customer_register.php">Register</a>
    <a href="reviews.php" class="active">Reviews</a>
    <a href="cart.php">Cart</a>
</nav>

<div class="container">
    <div class="form-card">
        <h2>Write a Review</h2>

        <!-- Render dynamic login/registration validation feedback blocks cleanly -->
        <?php echo $status_banner; ?>
        
        <!-- Action target routes out seamlessly straight into create_review.php handling script -->
        <form method="POST" action="create_review.php">
            
            <div class="form-group">
                <label>Your Name</label>
                <!-- Pre-fills name if customer is registered and log tracking variables are active -->
                <input type="text" name="customer_name" placeholder="Enter your name" 
                       value="<?php echo isset($_SESSION['customer_name']) ? htmlspecialchars($_SESSION['customer_name']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Select PNG Item Reviewed</label>
                <select name="product_id" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem; background: #fff;" required>
                    <option value="">-- Choose an Item --</option>
                    <?php if ($products_result && $products_result->num_rows > 0): ?>
                        <?php while($product = $products_result->fetch_assoc()): ?>
                            <option value="<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Star Rating Score</label>
                <select name="rating" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 1rem; background: #fff;" required>
                    <option value="5">★★★★★ (5 - Excellent)</option>
                    <option value="4">★★★★☆ (4 - Very Good)</option>
                    <option value="3">★★★☆☆ (3 - Average)</option>
                    <option value="2">★★☆☆☆ (2 - Poor)</option>
                    <option value="1">★☆☆☆☆ (1 - Very Bad)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Your Review Comments</label>
                <textarea name="review_text" placeholder="Tell us about the product quality, stitch integrity, or design pattern..." required></textarea>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 10px;">
                <button type="submit" name="submit_review" class="register-btn" style="flex: 2; margin-top: 0;">Submit Review</button>
                <a href="../frontend/reviews.php" class="continue-btn" style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; text-decoration: none;">Cancel</a>
            </div>
            
        </form>
    </div>
</div>

<footer class="site-footer">
    <div class="footer-container">
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
        <div class="footer-column">
            <h3>WHOLESALE</h3>
            <ul>
                <li><a href="#">Enquire & Register</a></li>
            </ul>
        </div>
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
