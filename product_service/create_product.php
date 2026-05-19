<?php
include '../config/db.php';

$product_name = $_POST['product_name'];
$description = $_POST['description'];
$category = $_POST['category'];
$price = $_POST['price'];
$stock_quantity = $_POST['stock_quantity'];
$image_url = $_POST['image_url'];

$sql = "INSERT INTO products(product_name,description,category,price,stock_quantity,image_url)
VALUES('$product_name','$description','$category','$price','$stock_quantity','$image_url')";

$conn->query($sql);

header("Location: ../frontend/shop.php");
?>