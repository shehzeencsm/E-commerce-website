<?php
session_start();
$conn = mysqli_connect("localhost","root","","bangle db");
if(!$conn){ die("Database Connection Failed: ".mysqli_connect_error()); }

// Initialize cart session
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Handle Add to Cart
if(isset($_POST['add_to_cart'])){
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    
    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['qty'] += 1;
    } else {
        $_SESSION['cart'][$id] = [
            "id"=>$id,
            "name"=>$name,
            "price"=>$price,
            "image"=>$image,
            "qty"=>1
        ];
    }
    header("Location: customerproduct.php");
    exit;
}

// Fetch Products
$result = mysqli_query($conn,"SELECT * FROM productuploaded");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Our Products - Sapphire Jewels</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<nav class="bg-white shadow-lg p-4 flex justify-between items-center sticky top-0 z-50">
    <h1 class="text-3xl font-bold text-yellow-700">Sapphire Jewels</h1>
    <div class="flex items-center gap-4">
        <a href="adminpanel old.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition">Dashboard</a>
        <a href="cart.php" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition">
            Cart (<?= count($_SESSION['cart']) ?>)
        </a>
    </div>
</nav>


<header class="text-center my-8">
    <h2 class="text-4xl font-bold text-gray-800">Our Exclusive Collection</h2>
    <p class="text-gray-600 mt-2">Browse our finest bangles, rings & necklaces</p>
</header>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-6 pb-12">
<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div class="bg-white p-4 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
        <img src="uploads/<?= $row['image'] ?>" class="w-full h-52 object-cover rounded-lg">
        <h2 class="text-xl font-semibold mt-4 text-gray-800"><?= $row['name'] ?></h2>
        <p class="text-yellow-700 font-bold text-lg mt-1">Rs <?= $row['price'] ?></p>
        <p class="text-gray-500 mt-2 text-sm"><?= $row['description'] ?></p>
        
        <form method="POST" class="mt-4">
            <input type="hidden" name="product_id" value="<?= $row['Id'] ?>">
            <input type="hidden" name="name" value="<?= $row['name'] ?>">
            <input type="hidden" name="price" value="<?= $row['price'] ?>">
            <input type="hidden" name="image" value="<?= $row['image'] ?>">
            <button type="submit" name="add_to_cart"
                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg font-semibold transition">
                Add to Cart
            </button>
        </form>
    </div>
<?php } ?>
</div>


<footer class="bg-gray-100 p-6 text-center text-gray-600 border-t">
    &copy; <?= date("Y") ?> Sapphire Jewels. All rights reserved.
</footer>

</body>
</html>
