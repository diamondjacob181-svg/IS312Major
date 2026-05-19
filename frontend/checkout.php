<?php
session_start();
include '../config/db.php';

if ($conn->connect_error) {
    die("<div style='color:red; font-family:sans-serif; padding:20px;'><strong>Database Connection Error:</strong> " . $conn->connect_error . "</div>");
}

$catalog_sql = "SELECT id, product_name, price, stock_quantity FROM products WHERE stock_quantity > 0 ORDER BY product_name ASC";
$catalog_result = $conn->query($catalog_sql);

$cart_mode = isset($_SESSION['cart']) && !empty($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - Bilas New Guinea</title>
<link rel="stylesheet" href="assets/styles.css">
</head>

<body>

<header style="background: url('https://d3k81ch9hvuctc.cloudfront.net/company/RmR3VV/images/677ac13d-2098-4c8a-934d-8e47229e3e84.png') no-repeat center center; background-size: cover; color: white; padding: 40px 20px; text-align: center;">
    <h1>Bilas New Guinea</h1>
    <p>Secure Checkout Page</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="customer_register.php">Register</a>
    <a href="reviews.php">Reviews</a>
    <a href="cart.php" class="active">Cart</a>
</nav>

<div class="container">
<div class="form-card">

<h2>Order Checkout</h2>

<form action="../order_service/create_order.php" method="POST">

<!-- ✅ ONLY CHANGE: customer_name → customer_id -->
<div class="form-group">
    <label>Customer ID</label>
    <input type="number" name="customer_id" required>
</div>

<!-- CART MODE -->
<?php if ($cart_mode): ?>
<?php 
$grand_total = 0; 
foreach ($_SESSION['cart'] as $item): 

$product_id = $item['id'];

$stmt = $conn->prepare("SELECT product_name, price, stock_quantity FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();

if (!$product) continue;

$qty = $item['quantity'] ?? 1;

if ($qty > $product['stock_quantity']) {
    $qty = $product['stock_quantity'];
}

/* PRICE × QTY */
$total = $product['price'] * $qty;
$grand_total += $total;
?>

<div class="cart-item" style="border:1px solid #ddd; padding:12px; margin-bottom:10px; border-radius:6px;">

    <input type="hidden" name="product_id[]" value="<?php echo $product_id; ?>">
    <input type="hidden" class="price" value="<?php echo $product['price']; ?>">

    <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>

    <p>Price: K<?php echo number_format($product['price'], 2); ?></p>

    <label>Quantity</label>
    <input type="number"
           class="quantity"
           name="quantity[]"
           value="<?php echo $qty; ?>"
           min="1"
           max="<?php echo $product['stock_quantity']; ?>">

    <p><strong>Subtotal:</strong> K<span class="subtotal"><?php echo number_format($total, 2); ?></span></p>

</div>

<?php endforeach; ?>
<?php endif; ?>

<!-- GRAND TOTAL FIELD -->
<div class="form-group" style="margin-top:20px;">
    <label><strong>Grand Total (K)</strong></label>
    <input type="number" id="grand_total" name="grand_total" value="<?php echo number_format($grand_total, 2); ?>" readonly>
</div>

<button type="submit" class="register-btn" style="margin-top:20px;">
    Place Secure Order
</button>

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

<script>
// Recalculate subtotals and grand total dynamically
function recalcTotals() {
    let grandTotal = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
        const price = parseFloat(item.querySelector('.price').value);
        const qty = parseInt(item.querySelector('.quantity').value) || 1;
        const subtotal = price * qty;
        item.querySelector('.subtotal').textContent = subtotal.toFixed(2);
        grandTotal += subtotal;
    });
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}

// Attach event listeners
document.querySelectorAll('.quantity').forEach(input => {
    input.addEventListener('input', recalcTotals);
});
</script>

</body>
</html>