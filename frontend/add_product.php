<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

<div class="card">

<h2>Add Product</h2>

<form action="../product-service/create_product.php" method="POST">

<input type="text" name="product_name" placeholder="Product Name">

<textarea name="description" placeholder="Description"></textarea>

<input type="text" name="category" placeholder="Category">

<input type="number" name="price" placeholder="Price">

<input type="number" name="stock_quantity" placeholder="Stock Quantity">

<input type="text" name="image_url" placeholder="Image URL">

<button type="submit">Add Product</button>

</form>

</div>

</div>

</body>
</html>