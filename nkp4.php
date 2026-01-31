<?php
$alertMsg = "";
$alertClass = "";

// DB Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bangle db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $sql = "INSERT INTO `checkout` (`product_name`, `total_price`, `name`, `phone`,`email`, `address`)
            VALUES ('$product_name', '$total_price', '$name', '$phone','$email','$address')";

    if (mysqli_query($conn, $sql)) {
        $alertMsg = "Your order has been submitted successfully!";
        $alertClass = "bg-green-500";
    } else {
        $alertMsg = "Error: " . mysqli_error($conn);
        $alertClass = "bg-red-500";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">

<!-- ✅ PROFESSIONAL ALERT -->
<?php if(!empty($alertMsg)) : ?>
<div id="orderAlert"
     class="fixed top-4 right-4 <?= $alertClass ?> text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-4 z-50 animate-fade">
    <span><?= $alertMsg ?></span>
    <button onclick="document.getElementById('orderAlert').remove()"
            class="font-bold text-xl leading-none hover:opacity-70">&times;</button>
</div>

<script>
// Auto-hide after 2 seconds
setTimeout(() => {
    const alert = document.getElementById('orderAlert');
    if(alert) alert.remove();
}, 2000);
</script>
<?php endif; ?>



  <!-- MAIN PRODUCT SECTION -->
  <div class="max-w-7xl mx-auto p-9 grid grid-cols-1 md:grid-cols-2 gap-11">

    <!-- LEFT IMAGES -->
    <div>
      <img id="mainImage" src="nk10.jpg" class="w-full rounded-xl shadow-lg h-96 object-cover">

      <div class="flex gap-3 mt-4">
        <img onclick="changeImage(this.src)" src="nk11.jpg" class="w-20 h-20 rounded-md cursor-pointer border">
        <img onclick="changeImage(this.src)" src="nk12.jpg" class="w-20 h-20 rounded-md cursor-pointer border">
        <img onclick="changeImage(this.src)" src="nk9.jpg" class="w-20 h-20 rounded-md cursor-pointer border">
        <img onclick="changeImage(this.src)" src="nk7.jpg" class="w-20 h-20 rounded-md cursor-pointer border">
      </div>
    </div>

    <!-- RIGHT PRODUCT DETAILS -->
    <div>
      <h2 class="text-3xl font-bold">Aurora Shine Necklace</h2>
      <p class="text-gray-600 mt-2 text-2xl font-bold">Doublet Stone | Premium</p>

      <p class="text-2xl font-bold text-[#6e510b] mt-3"> Rs. 111,199</p>

      <!-- SIZE -->
      <h3 class="mt-4 font-semibold">Select Size:</h3>
      <div class="flex gap-3 mt-2">
        <button class="size-btn border px-4 py-2 rounded-md hover:bg-gray-200">2.2</button>
        <button class="size-btn border px-4 py-2 rounded-md hover:bg-gray-200">2.4</button>
        <button class="size-btn border px-4 py-2 rounded-md hover:bg-gray-200">2.6</button>
        <button class="size-btn border px-4 py-2 rounded-md hover:bg-gray-200">2.8</button>
      </div>

      <!-- QUANTITY -->
      <div class="mt-6">
        <h3 class="font-semibold mb-2">Quantity:</h3>
        <div class="flex items-center gap-3">
          <button onclick="updateQty(-1)" class="px-4 py-1 bg-gray-300 rounded">-</button>
          <span id="qty" class="text-lg font-semibold">1</span>
          <button onclick="updateQty(1)" class="px-4 py-1 bg-gray-300 rounded">+</button>
        </div>
      </div>

      <!-- BUTTONS -->
      <div class="flex gap-4 mt-6">
        <button onclick="openCartPanel()" class="px-6 py-3 bg-[#6e510b] text-white rounded-lg shadow hover:bg-[#584007]">
          Add to Cart
        </button>

        <button onclick="toggleWishlist()" id="wishlistBtn"
          class="px-6 py-3 bg-gray-200 rounded-lg shadow">♡ Wishlist</button>
      </div>

      <!-- DESCRIPTION -->
      <div class="mt-6">
        <h3 class="text-lg font-bold">Description</h3>
        <p class="text-gray-700 mt-2">
          High quality sapphire Rings – perfect for bridal and party wear.
          Available in multiple designs,colors or sizes with long-lasting shine.
        </p>
      </div>
    </div>
  </div>

  
<!-- CART PANEL -->
<div id="cartPanel"
     class="fixed top-0 right-[-400px] w-80 bg-white h-full shadow-2xl p-6 transition-all duration-500">

    <h2 class="text-2xl font-bold mb-4">Added to Cart</h2>

    <img id="cartImage" src="nk10.jpg" class="w-full h-40 object-cover rounded-lg">

    <h3 id="cartName" class="text-xl font-semibold mt-4">Aurora Shine Necklace</h3>
    <p class="text-[#6e510b] font-bold">Doublet Stone | Premium</p>

    <p class="mt-3 text-gray-600"><b>Quantity:</b> <span id="cartQty">1</span></p>

    <p class="text-gray-700 mt-2"><b>Total:</b> Rs. <span id="cartTotal"> 111,199</span></p>

    <p class="mt-4 text-gray-600 font-semibold">More Images:</p>
    <div class="flex gap-3 mt-2">
        <img src="nk10.jpg" class="w-20 h-20 rounded-md border">
        <img src="nk12.jpg" class="w-20 h-20 rounded-md border">
        <img src="nk7.jpg" class="w-20 h-20 rounded-md border">
    </div>

    <button onclick="openCheckoutForm()" class="mt-6 w-full px-6 py-2 bg-[#6e510b] text-white rounded-md">
        🛒 Checkout
    </button>

    <button onclick="closeCartPanel()" class="mt-2 w-full px-6 py-2 bg-black text-white rounded-md">
        Close
    </button>
</div>


<!-- CHECKOUT FORM -->
<div id="checkoutForm" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-2xl font-bold mb-4">Place Your Order</h2>
        <form action="nkp4.php" id="orderForm" method="POST">

            <label class="block mt-2">Product Name:</label>
            <input type="text" name="product_name" id="formProductName" class="w-full border p-2 rounded" readonly>

            <label class="block mt-2">Total Price:</label>
            <input name="total_price" type="text" id="formTotalPrice" class="w-full border p-2 rounded" readonly>

            <label class="block mt-2">Name:</label>
            <input name="name" type="text" id="formName" class="w-full border p-2 rounded" required>

            <label class="block mt-2">Phone:</label>
            <input name="phone" type="text" id="formPhone" class="w-full border p-2 rounded" required>

             <label class="block mt-2">Email:</label>
            <input name="email" type="text" id="formemail" class="w-full border p-2 rounded" required>


            <label class="block mt-2">Address:</label>
            <textarea name="address" id="formAddress" class="w-full border p-2 rounded" required></textarea>

            <button type="submit" class="mt-4 w-full bg-[#6e510b] text-white py-2 rounded">Place Order</button>
        </form>
        <button onclick="closeCheckoutForm()" class="mt-2 w-full bg-gray-500 text-white py-2 rounded">Cancel</button>
    </div>
</div>




 <!-- JS -->
  <script>
const PRODUCT_PRICE =  111199;

function changeImage(src) {
    document.getElementById("mainImage").src = src;
    document.getElementById("cartImage").src = src;
}

function updateQty(num) {
    let qty = document.getElementById("qty");
    let newQty = Number(qty.innerText) + num;

    if (newQty >= 1) {
        qty.innerText = newQty;
        document.getElementById("cartQty").innerText = newQty;
        document.getElementById("cartTotal").innerText = newQty * PRODUCT_PRICE;
    }
}

function toggleWishlist() {
    const btn = document.getElementById("wishlistBtn");
    if (btn.innerText.includes("♡")) {
        btn.innerText = "❤️ Wishlisted";
        btn.classList.add("bg-red-300");
    } else {
        btn.innerText = "♡ Wishlist";
        btn.classList.remove("bg-red-300");
    }
}

function openCartPanel() {
    document.getElementById('cartPanel').style.right = '0';
}

function closeCartPanel() {
    document.getElementById('cartPanel').style.right = '-400px';
}

function openCheckoutForm() {
    document.getElementById('formProductName').value = document.getElementById('cartName').innerText;
    document.getElementById('formTotalPrice').value = document.getElementById('cartTotal').innerText;

    document.getElementById('checkoutForm').classList.remove('hidden');
    document.getElementById('checkoutForm').classList.add('flex');
}

function closeCheckoutForm() {
    document.getElementById('checkoutForm').classList.add('hidden');
    document.getElementById('checkoutForm').classList.remove('flex');
}
</script>

</body>

</html>
