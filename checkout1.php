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

    <form action="placeorder.php" method="POST" class="space-y-5">
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
