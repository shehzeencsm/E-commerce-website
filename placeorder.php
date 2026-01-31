<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "bangle db";

// Database connection
$conn = mysqli_connect($servername, $username, $password, $database);
if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

// Check cart
if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Calculate total
$total = 0;
foreach($cart as $item){
    $total += $item['price'] * $item['qty'];
}

// Insert order
$sql = "INSERT INTO orders (customer_name, phone, address, total_amount, order_date)
        VALUES ('$name', '$phone', '$address', '$total', NOW())";

if(mysqli_query($conn, $sql)){
    $order_id = mysqli_insert_id($conn);

    // Insert order items
    foreach($cart as $item){
        $pn = $item['name'];
        $pp = $item['price'];
        $qty = $item['qty'];

        mysqli_query($conn,
            "INSERT INTO order_items (order_id, product_name, price, qty)
             VALUES ($order_id, '$pn', $pp, $qty)"
        );
    }

    // Clear cart
    unset($_SESSION['cart']);

    // Redirect to success page
    header("Location: success.php");
    exit;

} else {
    echo "Error: " . mysqli_error($conn);
}
?>
