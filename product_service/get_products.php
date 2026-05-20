<?php
include '../config/db.php';

$result = $conn->query("SELECT * FROM products");

while($row = $result->fetch_assoc()){
    echo $row['product_name']."<br>";
}
?>