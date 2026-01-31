<?php
$servername="localhost";
$username="root";
$password="";
$database="bangle db";

$conn = mysqli_connect($servername, $username, $password, $database);
if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// -------------------- INSERT DATA --------------------
if($_SERVER['REQUEST_METHOD'] =='POST' && isset($_POST['product_name'])){
    $product_name = $_POST['product_name'];
    $total_price = $_POST['total_price'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql ="INSERT INTO checkout (`product_name`, `total_price`, `name`, `phone`,`email`, `address`)
           VALUES ('$product_name', '$total_price', '$name', '$phone','$email','$address')";
    $res = mysqli_query($conn, $sql);

    if($res){
        echo "<div class='bg-green-500 text-white p-3 rounded mb-4'>Submitted successfully</div>";
    } else {
        echo "<div class='bg-red-500 text-white p-3 rounded mb-4'>Error: ".mysqli_error($conn)."</div>";
    }
}

// -------------------- DELETE ORDER --------------------
// -------------------- DELETE ORDER --------------------
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Run delete query
    $delete_sql = "DELETE FROM checkout WHERE id = $id";
    if (mysqli_query($conn, $delete_sql)) {
        // Redirect back with a success message
        header("Location: adminpanel old.php?deleted=1");
        exit();
    } else {
        echo "<div class='bg-red-500 text-white p-3 rounded mb-4'>
                Error deleting record: " . mysqli_error($conn) . "
              </div>";
    }
}


// -------------------- CONFIRM ORDER --------------------
// -------------------- CONFIRM ORDER --------------------
if (isset($_GET['confirm_id'])) {
    $id = intval($_GET['confirm_id']); // sanitize input

    // Update status to 'confirmed'
    $confirm_sql = "UPDATE checkout SET status = 'confirmed' WHERE id = $id";
    if (mysqli_query($conn, $confirm_sql)) {
        // Redirect back with a success message
        header("Location: adminpanel old.php?confirmed=1");
        exit();
    } else {
        echo "<div class='bg-red-500 text-white p-3 rounded mb-4'>
                Error confirming order: " . mysqli_error($conn) . "
              </div>";
    }
}

// if (isset($_GET['confirm_id'])) {

//     $id = $_GET['confirm_id'];

//     // Fetch order details
//     $fetch = mysqli_query($conn, "SELECT * FROM checkout WHERE id=$id");
//     $data  = mysqli_fetch_assoc($fetch);

//     $email = $data['email'];
//     $name = $data['name'];
//     $product = $data['product_name'];

//     // Email subject and message
//     $subject = "Your Order Has Been Confirmed!";
//     $message = "
//     <html>
//     <body>
//         <h2>Dear $name,</h2>
//         <p>Your order for <strong>$product</strong> has been <strong>confirmed</strong>!</p>
//         <p>We will process it shortly.</p>
//         <br>
//         <p>Thank you for shopping with Sapphire Jewels.</p>
//     </body>
//     </html>
//     ";

//     // Email headers
//     $headers = "MIME-Version: 1.0" . "\r\n";
//     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//     $headers .= "From: sapphirejewels@example.com" . "\r\n"; // aapka server email

//     // Send email
//     if(mail($email, $subject, $message, $headers)){
//         // Update status in database only if mail sent
//         mysqli_query($conn, "UPDATE checkout SET status='confirmed' WHERE id=$id");
//         header("Location: adminpanel.php?confirmed=1");
//         exit();
//     } else {
//         echo "<div class='bg-red-500 text-white p-3 rounded mb-4'>
//             Error sending confirmation email to $email. Check your server mail configuration.
//         </div>";
//     }
// }






// -------------------- SETTINGS TABLE --------------------
$create_settings_table = "
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    website_name VARCHAR(255) DEFAULT '',
    contact_email VARCHAR(255) DEFAULT '',
    phone_number VARCHAR(50) DEFAULT '',
    footer_text VARCHAR(255) DEFAULT '',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
mysqli_query($conn, $create_settings_table);

// Insert default row if empty
$insert_default_row = "
INSERT INTO settings (website_name, contact_email, phone_number, footer_text)
SELECT '', '', '', '' 
WHERE NOT EXISTS (SELECT * FROM settings)";
mysqli_query($conn, $insert_default_row);

// -------------------- SAVE krne ka code --------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['website_name'])){
    $website_name  = mysqli_real_escape_string($conn, $_POST['website_name']);
    $contact_email = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $phone_number  = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $footer_text   = mysqli_real_escape_string($conn, $_POST['footer_text']);

    $update_sql = "UPDATE settings SET 
                    website_name='$website_name', 
                    contact_email='$contact_email', 
                    phone_number='$phone_number', 
                    footer_text='$footer_text' 
                   WHERE id=1";

    if (mysqli_query($conn, $update_sql)) {
        $settings_saved_msg = "Settings saved successfully!";
    } else {
        $settings_saved_msg = "Error saving settings: " . mysqli_error($conn);
    }
}

// -------------------- FETCH krne k liye --------------------
$res_settings = mysqli_query($conn, "SELECT * FROM settings WHERE id=1");
$settings_row = mysqli_fetch_assoc($res_settings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
tailwind.config = { darkMode: 'class' }
</script>
</head>
<body class="bg-gray-100 text-black dark:bg-gray-900 dark:text-white min-h-screen flex transition-all" id="body">




<aside id="sidebar" class="w-64 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 
text-white min-h-screen p-6 shadow-xl border-r border-gray-700 transition-all duration-300">

  <!-- Logo -->
  <div class="flex items-center gap-3 mb-8">
    <div class="w-12 h-12 bg-white text-gray-900 rounded-xl flex items-center justify-center text-xl font-bold shadow-lg">
      SBJ
    </div>
    <h1 class="text-2xl font-semibold tracking-wide">Admin Panel</h1>
  </div>

  <!-- Navigation -->
  <nav class="space-y-3">
    <a href="#" onclick="openDashboard()" class="flex items-center gap-3 p-3 rounded-lg 
       hover:bg-gray-700 transition">
      <span>📊</span> Dashboard
    </a>

    <a href="#" onclick="openOrders()" class="flex items-center gap-3 p-3 rounded-lg 
       hover:bg-gray-700 transition">
      <span>🧾</span> Orders
    </a>

    <a href="product.php" class="flex items-center gap-3 p-3 rounded-lg 
       hover:bg-gray-700 transition">
      <span>📦</span> Products
    </a>

    <a href="customerproduct.php" class="flex items-center gap-3 p-3 rounded-lg 
       hover:bg-gray-700 transition">
      <span>👥</span> Customers
    </a>

    <a href="#" onclick="openSettings()" class="flex items-center gap-3 p-3 rounded-lg 
       hover:bg-gray-700 transition">
      <span>⚙️</span> Settings
    </a>
  </nav>

</aside>


<!-- MAIN CONTENT -->
<main class="flex-1 p-4 md:p-8">

  <!-- TOP BAR -->
<div class="flex justify-between items-center mb-8">
  <h2 class="text-3xl font-bold">Dashboard</h2>
  <div class="flex items-center gap-4">
    <button onclick="toggleTheme()" class="px-4 py-2 bg-gray-700 dark:bg-gray-300 text-white dark:text-black rounded">Dark/Light</button>
    <button class="px-4 py-2 bg-red-600 text-white rounded">Logout</button>
  </div>
</div>





  <!-- ALERTS -->


<?php if(isset($_GET['deleted'])): ?>
<div id="deleteAlert" class="bg-green-500 text-white p-3 rounded mb-4">
  Order deleted successfully!
</div>
<?php endif; ?>

<?php if(isset($settings_saved_msg)): ?>
<div class="settingsSavedAlert bg-green-500 text-white p-3 rounded mb-4">
  <?= $settings_saved_msg ?>
</div>
<?php endif; ?>

<?php if(isset($_GET['confirmed'])): ?>
<div id="confirmAlert" class="bg-green-500 text-white p-3 rounded mb-4">
  Order confirmed successfully!
</div>
<?php endif; ?>


  <!-- STAT CARDS -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow text-black dark:text-white">
      <h3 class="text-xl font-semibold mb-2">Total Orders</h3>
      <p class="text-3xl font-bold"><?= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM checkout")) ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow text-black dark:text-white">
      <h3 class="text-xl font-semibold mb-2">Total Customers</h3>
      <p class="text-3xl font-bold"><?= mysqli_num_rows(mysqli_query($conn,"SELECT * FROM checkout")) ?></p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow text-black dark:text-white">
      <h3 class="text-xl font-semibold mb-2">Total Revenue</h3>
      <p class="text-3xl font-bold"></p>
    </div>
  </div>

  <!-- ORDERS TABLE -->
  <div id="ordersSection" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow overflow-x-auto hidden">
    <h3 class="text-2xl font-semibold mb-4">Recent Orders</h3>
    <table class="min-w-full border-collapse text-sm text-black dark:text-white">
      <thead>
        <tr class="bg-gray-100 dark:bg-gray-700 text-left">
          <th class="p-3">Order ID</th>
          <th class="p-3">Product Name</th>
          <th class="p-3">Total Price</th>
          <th class="p-3">Name</th>
          <th class="p-3">Phone No</th>
          <th class="p-3">Email</th>
          <th class="p-3">Address</th>
          <th class="p-3">Action</th>
          <th class="p-3">status</th>
          
         
        </tr>
      </thead>
      <tbody>
      
     
<?php
$id = 1;
$sql = "SELECT * FROM `checkout`";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)){

    // Check status
    if(isset($row['status']) && $row['status'] == 'confirmed'){
        $status_html = '<span class="bg-green-500 text-white px-1 py-1 rounded flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg> Confirmed</span>';
        $confirm_button = ''; // Remove confirm button if already confirmed
    } else {
        $status_html = '<span class="bg-gray-300 text-black px-1 py-1 rounded">Pending</span>';
        $confirm_button = '<a href="?confirm_id='.$row['id'].'" class="px-1 py-1 bg-green-600 text-white rounded hover:bg-green-700" onclick="return confirm(\'Mark this order as confirmed?\')">Confirm</a>';
    }

    echo '<tr class="bg-gray-200 dark:bg-gray-700 text-black dark:text-white">
        <td class="p-3 py-4">'.$id.'</td>
        <td class="p-3 py-4">'.$row['product_name'].'</td>
        <td class="p-3 py-4">'.$row['total_price'].'</td>
        <td class="p-3 py-4">'.$row['name'].'</td>
        <td class="p-3 py-4">'.$row['phone'].'</td>
        <td class="p-3 py-4">'.$row['email'].'</td>
        <td class="p-3 py-4">'.$row['address'].'</td>
        <td class="p-3 py-4 flex gap-2">
            <a href="?delete_id='.$row['id'].'" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm(\'Are you sure you want to delete this order?\')">Delete</a>
            '.$confirm_button.'
        </td>
        <td class="p-3 py-4">'.$status_html.'</td>
    </tr>';
    $id++;
}
?>
</tbody>

      </tbody>
    </table>
  </div>

  <!-- SETTINGS SECTION -->
  <div id="settingsSection" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow overflow-x-auto hidden mt-6 text-black dark:text-white">
    <h3 class="text-2xl font-semibold mb-4">Website Settings</h3>
    <form action="adminpanel old.php" method="POST" class="space-y-4">
      <div>
        <label class="block mb-1 font-medium">Website Name</label>
        <input type="text" name="website_name" class="w-full p-2 border rounded text-black dark:text-white dark:bg-gray-700" value="<?= htmlspecialchars($settings_row['website_name']) ?>" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Contact Email</label>
        <input type="email" name="contact_email" class="w-full p-2 border rounded text-black dark:text-white dark:bg-gray-700" value="<?= htmlspecialchars($settings_row['contact_email']) ?>" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Phone Number</label>
        <input type="text" name="phone_number" class="w-full p-2 border rounded text-black dark:text-white dark:bg-gray-700" value="<?= htmlspecialchars($settings_row['phone_number']) ?>" />
      </div>
      <div>
        <label class="block mb-1 font-medium">Footer Text</label>
        <input type="text" name="footer_text" class="w-full p-2 border rounded text-black dark:text-white dark:bg-gray-700" value="<?= htmlspecialchars($settings_row['footer_text']) ?>" />
      </div>
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Settings</button>
    </form>
  </div>

</main>



<script>
// SIDEBAR TOGGLE
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-ml-64'); }

// DARK / LIGHT THEME
function toggleTheme() {
  document.body.classList.toggle("dark");
  if(document.body.classList.contains("dark")) localStorage.setItem("theme","dark");
  else localStorage.setItem("theme","light");
}
window.addEventListener("DOMContentLoaded", ()=>{
  if(localStorage.getItem("theme")==="dark") document.body.classList.add("dark");
});

const deleteAlert = document.getElementById("deleteAlert");
 if(deleteAlert){ setTimeout(() => { deleteAlert.style.display = "none"; }, 2000);} 
 const confirmAlert = document.getElementById("confirmAlert"); 
if(confirmAlert){ setTimeout(() => { confirmAlert.style.display = "none"; }, 2000);}





// OPEN ORDERS / SETTINGS
function openOrders(){ document.getElementById("ordersSection").classList.remove("hidden"); document.getElementById("settingsSection").classList.add("hidden"); }
function openSettings(){ document.getElementById("settingsSection").classList.remove("hidden"); document.getElementById("ordersSection").classList.add("hidden"); }



// Auto hide alerts after 2 seconds (2000ms)
window.addEventListener("DOMContentLoaded", () => {
    const alerts = document.querySelectorAll("#deleteAlert, #confirmAlert, .settingsSavedAlert");
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => { alert.style.display = "none"; }, 500);
        }, 2000);
    });
});


</script>








</body>
</html>
