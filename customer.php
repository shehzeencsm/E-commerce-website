

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
    
    // If already in cart, increase qty
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
    header("Location: customerproducts.php");
    exit;
}

// Fetch Products
$result = mysqli_query($conn,"SELECT * FROM productuploaded");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Our Products</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- HEADER -->
<nav class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-yellow-700">Sapphire Jewels</h1>
    <a href="cart.php" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Cart (<?= count($_SESSION['cart']) ?>)</a>
</nav>

<!-- PRODUCTS GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <div class="bg-white p-4 rounded-xl shadow hover:shadow-xl transition">
        <img src="uploads/<?= $row['image'] ?>" class="w-full h-48 object-cover rounded-lg">
        <h2 class="text-xl font-semibold mt-3"><?= $row['name'] ?></h2>
        <p class="text-yellow-700 font-bold text-lg">Rs <?= $row['price'] ?></p>
        <p class="text-gray-600 mt-1"><?= $row['description'] ?></p>
        
        <form method="POST" class="mt-3">
            <input type="hidden" name="product_id" value="<?= $row['Id'] ?>">
            <input type="hidden" name="name" value="<?= $row['name'] ?>">
            <input type="hidden" name="price" value="<?= $row['price'] ?>">
            <input type="hidden" name="image" value="<?= $row['image'] ?>">
            <button type="submit" name="add_to_cart"
                class="w-full bg-yellow-600 text-white py-2 rounded-lg hover:bg-yellow-700 mt-2">
                Add to Cart
            </button>
        </form>
    </div>
<?php } ?>
</div>

</body>
</html>




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
    <h1 class="text-2xl font-bold text-yellow-700">🛒 Your Cart</h1>
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
                <td class="p-3"><img src="uploads/<?= $item['image'] ?>" class="w-16 rounded"></td>
                <td class="p-3"><?= $item['name'] ?></td>
                <td class="p-3">Rs <?= $item['price'] ?></td>
                <td class="p-3 text-center"><?= $item['qty'] ?></td>
                <td class="p-3 font-bold">Rs <?= $total ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="text-right mt-6">
        <h3 class="text-xl font-bold">Grand Total: Rs <?= $grandTotal ?></h3>
        <a href="checkout.php" class="mt-4 inline-block bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800">
            Proceed to Checkout
        </a>
    </div>
    <?php } ?>
</div>

</body>
</html>







<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>ShineCraft Jewels - Products</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
/* Smooth Fade Animation */
.fade-in { animation: fadeIn 0.4s ease-in-out; }
@keyframes fadeIn {
0% {opacity: 0; transform: scale(0.95);}
100% {opacity: 1; transform: scale(1);}
}
</style>
</head>

<body class="bg-gray-100">

<!-- HEADER -->
<header class="bg-black text-white py-5 shadow-lg">
    <h1 class="text-center text-4xl font-extrabold tracking-wide text-yellow-500">
        Sapphire Bangles & jewels
    </h1>
    <p class="text-center text-gray-300 text-lg">
        Premium Handmade Jewellery Collection
    </p>
</header>

<!-- TITLE -->
<div class="text-center mt-10">
    <h2 class="text-3xl font-bold text-gray-800">Our Latest Products</h2>
    <div class="w-20 h-1 bg-yellow-500 mx-auto mt-2 rounded"></div>
</div>

<!-- PRODUCTS GRID -->
<?php $result = mysqli_query($conn,"SELECT * FROM productuploaded"); ?>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 p-10">

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<div class="bg-white p-5 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer fade-in"
     onclick="showDetail('<?php echo addslashes($row['name']); ?>',
                         '<?php echo $row['price']; ?>',
                         '<?php echo addslashes($row['description']); ?>',
                         '<?php echo $row['image']; ?>')">

    <img src="uploads/<?php echo $row['image']; ?>"
         class="w-full h-60 object-cover rounded-xl shadow-sm">

    <h2 class="text-xl font-semibold mt-3 text-gray-800">
        <?php echo $row['name']; ?>
    </h2>

    <p class="text-yellow-600 font-bold text-lg mt-1">
        Rs <?php echo $row['price']; ?>
    </p>

</div>
<?php } ?>

</div>

<!-- PRODUCT DETAIL MODAL -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4">
<div class="bg-white p-7 rounded-2xl w-full max-w-md relative fade-in shadow-2xl">

    <!-- CLOSE BUTTON -->
    <button onclick="closeDetail()" class="absolute top-3 right-3 text-gray-600 text-2xl hover:text-black">
        ✕
    </button>

    <!-- IMAGE -->
    <img id="dImage" class="w-full h-64 object-cover rounded-xl shadow-md">

    <!-- TITLE -->
    <h2 id="dName" class="text-3xl font-bold mt-4 text-gray-800"></h2>

    <!-- PRICE -->
    <p id="dPrice" class="text-yellow-600 font-bold text-xl mt-2"></p>

    <!-- DESCRIPTION -->
    <p id="dDesc" class="text-gray-600 mt-3 leading-relaxed"></p>

    <!-- BUTTONS -->
    <button onclick="addToCart()"
            class="w-full mt-5 bg-yellow-600 text-black font-semibold py-3 rounded-xl hover:bg-yellow-500">
        🛒 Add to Cart
    </button>

    <a id="whatsappBtn" target="_blank"
       class="block mt-3 text-center bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 font-semibold">
       📩 Order via WhatsApp
    </a>

</div>
</div>

<script>
/* SHOW PRODUCT DETAILS */
function showDetail(name, price, desc, img){
    document.getElementById('dName').innerText = name;
    document.getElementById('dPrice').innerText = "Rs " + price;
    document.getElementById('dDesc').innerText = desc;
    document.getElementById('dImage').src = "uploads/" + img;

    // WhatsApp Link
    document.getElementById('whatsappBtn').href =
        "https://api.whatsapp.com/send?phone=03121336235&text=" +
        encodeURIComponent(
            "✨ New Order Request ✨\n\n" +
            "Product: " + name + "\n" +
            "Price: Rs " + price + "\n" +
            "Description: " + desc
        );

    // SHOW MODAL
    document.getElementById('detailModal').classList.remove('hidden');
}

/* CLOSE MODAL */
function closeDetail(){
    document.getElementById('detailModal').classList.add('hidden');
}

/* ADD TO CART */
function addToCart(){
    alert("✔ Product added to cart successfully!");
}
</script>

</body>
</html>
