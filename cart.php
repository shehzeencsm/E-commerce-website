<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

//  quantity bharhe gi is me
if(isset($_POST['update_qty'])){
    $id = $_POST['product_id'];
    $qty = max(1, intval($_POST['qty'])); // minimum 1
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['qty'] = $qty;
    }
    header("Location: cart.php");
    exit;
}

// remove item
if(isset($_GET['remove_id'])){
    $id = $_GET['remove_id'];
    if(isset($_SESSION['cart'][$id])){
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart - Sapphire Jewels</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">


<nav class="bg-white shadow sticky top-0 z-50 p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-yellow-700">🛒 Your Cart</h1>
    <a href="customerproduct.php" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg font-semibold transition">
        Continue Shopping
    </a>
</nav>


<header class="text-center my-10">
    <h2 class="text-4xl font-bold text-gray-800">Shopping Cart</h2>
    <p class="text-gray-600 mt-2">Review your selected items before checkout</p>
</header>

<div class="max-w-6xl mx-auto p-6">
    <?php if(empty($cart)){ ?>
        <div class="text-center py-20 bg-white rounded-xl shadow-lg">
            <p class="text-gray-600 text-lg">Your cart is empty.</p>
            <a href="customerproduct.php" class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                Shop Now
            </a>
        </div>
    <?php } else { ?>
        <div class="grid gap-6">
            <?php 
            $grandTotal = 0;
            foreach($cart as $item){
                $total = $item['price'] * $item['qty'];
                $grandTotal += $total;
            ?>
        
            <div class="flex flex-col md:flex-row bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition gap-4 items-center md:items-start">
                <div class="md:w-1/4 flex justify-center">
                    <img src="uploads/<?= $item['image'] ?>" class="w-32 h-32 object-cover rounded-lg">
                </div>
                <div class="md:w-2/4 flex flex-col justify-between mt-2 md:mt-0">
                    <h3 class="text-xl font-semibold text-gray-800"><?= $item['name'] ?></h3>
                    <p class="text-gray-600 mt-2">Price: Rs <?= $item['price'] ?></p>

                    <form method="POST" class="mt-3 flex items-center gap-2">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="w-20 p-1 border rounded text-center">
                        <button type="submit" name="update_qty" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded transition">
                            Update
                        </button>
                        <a href="?remove_id=<?= $item['id'] ?>" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded transition">
                            Remove
                        </a>
                    </form>
                </div>
                <div class="md:w-1/4 flex justify-end mt-2 md:mt-0">
                    <span class="text-lg font-bold text-gray-800">Rs <?= $total ?></span>
                </div>
            </div>
            <?php } ?>

            <!-- GRAND TOTAL -->
            <div class="flex flex-col md:flex-row justify-between items-center mt-6 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800">Grand Total: Rs <?= $grandTotal ?></h3>
                <a href="checkout1.php" class="mt-4 md:mt-0 bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    <?php } ?>
</div>

<!-- FOOTER -->
<footer class="bg-gray-100 p-6 text-center text-gray-600 border-t mt-12">
    &copy; <?= date("Y") ?> Sapphire Jewels. All rights reserved.
</footer>

</body>
</html>
