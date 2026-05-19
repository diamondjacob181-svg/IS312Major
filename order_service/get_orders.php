<?php
include '../config/db.php';

$result = $conn->query("SELECT * FROM orders");

while($row = $result->fetch_assoc()){
    echo $row['customer_name']." ordered ".$row['product_name']."<br>";
}
?>