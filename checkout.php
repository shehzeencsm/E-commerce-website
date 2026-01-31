<?php
$servrname = "localhost";
$username = "root"; 
$password = "";
$database = "bangle db";

$conn = mysqli_connect($servrname, $username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// $product = $_POST['product_name'];
// $price = $_POST['total_price'];
// $name = $_POST['name'];
// $phone = $_POST['phone'];
// $address = $_POST['address'];
// $sql = "INSERT INTO `checkout` (`id`, `product_name`, `total_price`, `customer_name`, `phone`, `address`, `orderdate`) 
// VALUES ('01', '', '', '', '', '', '');



// $sql = "INSERT INTO orders (product_name, total_price, customer_name, phone, address)
//         VALUES ('$product', '$price', '$name', '$phone', '$address')";

// if(mysqli_query($conn, $sql)){
//     echo "success";
// } else {
//     echo "error: " . mysqli_error($conn);
// }
// ?>
