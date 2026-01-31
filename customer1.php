<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "bangle db";

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Database Connection Failed: " . mysqli_error($conn));
}

// Correct GET variable
$Id = $_GET['Id'];

Correct product fetch
$result = mysqli_query($conn, "SELECT * FROM productuploaded WHERE Id = $id");
$product = mysqli_fetch_assoc($result);

// Create cart
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Correct usage
$_SESSION['cart'][$Id] = [
    "id" => $product['Id'],
    "name" => $product['name'],
    "price" => $product['price'],
    "image" => $product['image'],
    "qty" => 1
];

header("Location: cart.php");
exit;
?> 









<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<nav class="bg-white p-4 shadow flex justify-between">
    <h1 class="text-2xl font-bold">🛒 Your Cart</h1>
    <a href="customerproducts.php" class="text-blue-600 font-semibold">Continue Shopping</a>
</nav>

<div class="max-w-5xl mx-auto p-8 bg-white mt-10 rounded-xl shadow">

    <h2 class="text-3xl font-bold mb-6">Cart Items</h2>

    <?php if(empty($cart)){ ?>
        <p class="text-gray-600 text-lg">Your cart is empty.</p>
    <?php } else { ?>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Image</th>
                <th class="p-3 text-left">Name</th>
                <th class="p-3">Price</th>
                <th class="p-3">Qty</th>
                <th class="p-3">Total</th>
            </tr>
        </thead>

        <tbody>
            <?php 
            $grandTotal = 0;
            foreach($cart as $item){
                $total = $item['price'] * $item['qty'];
                $grandTotal += $total;
            ?>
            <tr class="border-b">
                <td class="p-3"><img src="uploads/<?php echo $item['image']; ?>" class="w-16 rounded"></td>
                <td class="p-3"><?php echo $item['name']; ?></td>
                <td class="p-3">Rs <?php echo $item['price']; ?></td>
                <td class="p-3 text-center">1</td>
                <td class="p-3 font-bold">Rs <?php echo $total; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="text-right mt-6">
        <h3 class="text-xl font-bold">Grand Total: Rs <?php echo $grandTotal; ?></h3>

        <a href="checkout.php"
            class="mt-4 inline-block bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800">
            Proceed to Checkout
        </a>
    </div>

    <?php } ?>

</div>

</body>
</html>





<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if(empty($cart)){
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-4xl mx-auto bg-white p-10 mt-10 shadow rounded-xl">

    <h2 class="text-3xl font-bold mb-6">Checkout</h2>

    <form action="customer1.php" method="POST" class="space-y-5">

        <input type="text" name="name" placeholder="Full Name" required class="w-full p-3 border rounded">
        <input type="text" name="phone" placeholder="Phone Number" required class="w-full p-3 border rounded">
        <textarea name="address" placeholder="Full Address" required class="w-full p-3 border rounded"></textarea>

        <button type="submit" class="w-full bg-black text-white py-3 rounded-lg font-semibold">
            Place Order
        </button>
    </form>

</div>

</body>
</html>




<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "bangle db";

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Connection Failed: " . mysqli_error($conn));
}

$cart = $_SESSION['cart'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

$total = 0;
foreach($cart as $item){
    $total += $item['price'] * $item['qty'];
}

$sql = "INSERT INTO orders (`customer_name`, `phone`, `address`, `total_amount`, `order_date`)
        VALUES ('$name', '$phone', '$address', '$total', NOW())";

mysqli_query($conn, $sql);
$order_id = mysqli_insert_id($conn);

// Insert each item
foreach($cart as $item){
    $pn = $item['name'];
    $pp = $item['price'];
    $qty = $item['qty'];

    mysqli_query($conn,
        "INSERT INTO order_items (order_id, product_name, price, qty)
         VALUES ($order_id, '$pn', $pp, $qty)"
    );
}

// clear cart
unset($_SESSION['cart']);

header("Location: success.php");
exit;
?>





<!DOCTYPE html>
<html>
<head>
    <title>Order Placed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-10 shadow rounded-xl text-center max-w-lg">

    <h1 class="text-4xl font-bold text-green-600">✔ Order Placed</h1>
    <p class="text-gray-700 text-lg mt-3">
        Thank you! Your order has been placed successfully.
    </p>

    <a href="customerproducts.php"
       class="mt-6 inline-block bg-black text-white px-6 py-3 rounded-lg">
       Continue Shopping
    </a>

</div>

</body>
</html>

