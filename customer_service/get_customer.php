<?php
include '../config/db.php';

$result = $conn->query("SELECT * FROM customers");

while($row = $result->fetch_assoc()){
    echo $row['full_name']."<br>";
}
?>