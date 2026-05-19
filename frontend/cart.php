<?php
// Start active session tracking and load configuration engine
session_start();
include '../config/db.php';

// Verify structural database connection stability
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*
|--------------------------------------------------------------------------
| MULTI-PRODUCT SESSION CART STORAGE SYSTEM
|--------------------------------------------------------------------------
*/

// Initialize cart session array if not already existing
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/*
|--------------------------------------------------------------------------
| CLEAR ENTIRE CART
|--------------------------------------------------------------------------
*/
if (isset($_GET['clear_cart'])) {

    unset($_SESSION['cart']);

    $_SESSION['cart'] = [];

    header("Location: cart.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| REMOVE SINGLE PRODUCT FROM CART
|--------------------------------------------------------------------------
*/
if (isset($_GET['remove_id']) && is_numeric($_GET['remove_id'])) {

    $remove_id = intval($_GET['remove_id']);

    foreach ($_SESSION['cart'] as $key => $item) {

        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$key]);
        }
    }

    // Re-index array
    $_SESSION['cart'] = array_values($_SESSION['cart']);

    header("Location: cart.php");
    exit();
}

// Check if a product ID is passed from shop.php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $product_id = intval($_GET['id']);

    // Query your products table
    $product_stmt = $conn->prepare("SELECT id, product_name, price FROM products WHERE id = ? LIMIT 1");

    if ($product_stmt) {

        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();

        $product_result = $product_stmt->get_result();

        if ($product_result->num_rows > 0) {

            $product_row = $product_result->fetch_assoc();

            // Prevent duplicate inserts
            $already_exists = false;

            foreach ($_SESSION['cart'] as $cart_item) {
                if ($cart_item['id'] == $product_row['id']) {
                    $already_exists = true;
                    break;
                }
            }

            // Add product into session cart
            if (!$already_exists) {

                $_SESSION['cart'][] = [
                    'id' => $product_row['id'],
                    'product_name' => $product_row['product_name'],
                    'price' => $product_row['price']
                ];
            }
        }

        $product_stmt->close();
    }
}

// Capture all session cart products
$cart_products = $_SESSION['cart'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Bilas New Guinea</title>
    <link rel="stylesheet" href="assets\styles.css">
</head>
<body>

<header style="background: url('https://d3k81ch9hvuctc.cloudfront.net/company/RmR3VV/images/677ac13d-2098-4c8a-934d-8e47229e3e84.png') no-repeat center center; background-size: cover; color: white; padding: 40px 20px; text-align: center; position: relative;">
    <h1>Bilas New Guinea</h1>
    <p>Your Cultural Fashion Selection</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="customer_register.php">Register</a>
    <a href="cart.php" class="active">Cart</a>
</nav>

<div class="container">
    <div class="cart-card">

        <div class="cart-header">
            <span class="cart-icon">🛒</span>
            <h2>Your Shopping Cart</h2>
        </div>

        <div class="cart-content">

            <?php if (!empty($cart_products)): ?>

                <p style="font-size: 16px; margin-bottom: 15px;">
                    Products currently stored in your cart:
                </p>

                <?php
                $grand_total = 0;

                foreach ($cart_products as $item):

                    $grand_total += $item['price'];
                ?>

                    <div style="background: #f8fafc; padding: 15px; border-radius: 6px; border-left: 4px solid #8B0000; margin-top: 10px; position: relative;">

                        <strong style="font-size: 16px; color: #1e293b;">
                            <?php echo htmlspecialchars($item['product_name']); ?>
                        </strong>

                        <p style="margin: 5px 0 10px 0; font-size: 15px; color: #8B0000; font-weight: bold;">
                            Price: K<?php echo number_format($item['price'], 2); ?>
                        </p>

                        <!-- REMOVE SINGLE PRODUCT BUTTON -->
                        <a href="cart.php?remove_id=<?php echo $item['id']; ?>"
                           onclick="return confirm('Remove this product from cart?')"
                           style="display:inline-block;
                                  padding:6px 12px;
                                  background:#dc2626;
                                  color:white;
                                  text-decoration:none;
                                  border-radius:4px;
                                  font-size:13px;
                                  font-weight:bold;">
                            Remove
                        </a>

                    </div>

                <?php endforeach; ?>

                <!-- GRAND TOTAL -->
                <div style="margin-top:20px; padding:15px; background:#fff7ed; border-radius:6px; border:1px solid #fdba74;">

                    <h3 style="margin:0; color:#9a3412;">
                        Cart Total: K<?php echo number_format($grand_total, 2); ?>
                    </h3>

                </div>

            <?php else: ?>

                <p>Your cart is empty. Please head over to our store categories to make a selection.</p>

            <?php endif; ?>

        </div>

        <div class="cart-actions">

            <!-- CONTINUE SHOPPING -->
            <a href="shop.php" class="continue-btn">
                Continue Shopping
            </a>

            <!-- CHECKOUT -->
            <a href="checkout.php" class="checkout-btn">
                Proceed To Checkout
            </a>

            <!-- CLEAR ENTIRE CART -->
            <?php if (!empty($cart_products)): ?>

                <a href="cart.php?clear_cart=1"
                   onclick="return confirm('Clear all products from cart?')"
                   style="background:#991b1b;
                          color:white;
                          padding:10px 18px;
                          border-radius:5px;
                          text-decoration:none;
                          font-weight:bold;
                          margin-left:10px;">
                    Clear Cart
                </a>

            <?php endif; ?>

        </div>

    </div>
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