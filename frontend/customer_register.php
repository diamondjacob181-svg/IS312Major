<?php
include '../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Bilas New Guinea</title>
    <link rel="stylesheet" href="assets\styles.css">
    <style>
        .status-alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            font-weight: bold;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<header style="background: url('https://d3k81ch9hvuctc.cloudfront.net/company/RmR3VV/images/677ac13d-2098-4c8a-934d-8e47229e3e84.png') no-repeat center center; background-size: cover; color: white; padding: 40px 20px; text-align: center; position: relative;">
    <h1>Bilas New Guinea</h1>
    <p>Join our cultural fashion community</p>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="customer_register.php" class="active">Register</a>
    <a href="cart.php">Cart</a>
</nav>

<div class="container">
    <div class="form-card">
        <h2>Customer Registration</h2>

        <!-- SUCCESS MESSAGE WITH CUSTOMER ID -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>

            <?php
            // Get last inserted customer (latest registered user)
            $latest = $conn->query("SELECT id FROM customers ORDER BY id DESC LIMIT 1");
            $customer = $latest ? $latest->fetch_assoc() : null;
            $customer_id = $customer['id'] ?? 'N/A';
            ?>

            <div class="status-alert status-success">
                🎉 Registered successfully!<br><br>
                <strong>Your Customer ID is: <?php echo $customer_id; ?></strong><br><br>
                📌 Please save this ID — you will use it when placing orders.
            </div>

        <?php endif; ?>

        <!-- ERROR MESSAGE -->
        <?php if (isset($_GET['error'])): ?>
            <div class="status-alert status-error">
                <?php 
                    if ($_GET['error'] == 'email_exists') {
                        echo "⚠️ This email address is already registered.";
                    } elseif ($_GET['error'] == 'empty_fields') {
                        echo "⚠️ Please fill out all required fields.";
                    } else {
                        echo "⚠️ Registration failed. Please try again.";
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <form action="../customer_service/create_customer.php" method="POST">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="e.g. +675...">
            </div>

            <div class="form-group">
                <label>Residential Address</label>
                <textarea name="address" placeholder="Enter your delivery address"></textarea>
            </div>

            <button type="submit" class="register-btn">Create Account</button>
            
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
