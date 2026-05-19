<?php
session_start();
// If already logged in, skip to dashboard
if(isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bilas Admin | Login</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; background: #f8f5f2; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center; width: 350px; border-top: 5px solid #8B0000; }
        .login-icon { width: 80px; margin-bottom: 20px; }
        .login-card h2 { color: #333; margin-bottom: 25px; font-size: 1.5rem; }
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
        .login-btn { width: 100%; background: #8B0000; color: white; border: none; padding: 12px; border-radius: 5px; cursor: pointer; font-size: 1.1rem; transition: 0.3s; }
        .login-btn:hover { background: #333; }
        .error { color: #8B0000; margin-bottom: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Your User Icon -->
        <img src="https://media.istockphoto.com/id/1196083861/vector/simple-man-head-icon-set.jpg?s=612x612&w=0&k=20&c=a8fwdX6UKUVCOedN_p0pPszu8B4f6sjarDmUGHngvdM=" alt="Admin Icon" class="login-icon">
        
        <h2>Admin Login</h2>

        <?php if(isset($_GET['error'])) echo '<p class="error">Invalid Username or Password</p>'; ?>

        <form action="login_process.php" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="login-btn">Sign In</button>
        </form>
    </div>

</body>
</html>
