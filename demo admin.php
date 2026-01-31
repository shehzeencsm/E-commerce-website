~~~{"variant":"standard","title":"Fixed Admin Panel","id":"98644"}
<?php
$servername="localhost";
$username="root";
$password="";
$database="bangle db";

$conn = mysqli_connect($servername, $username, $password, $database);
if(!$conn){ die("Connection failed: ".mysqli_connect_error()); }

// ---------------- CREATE SETTINGS TABLE ----------------
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

// INSERT DEFAULT ROW IF EMPTY
$insert_default_row = "
INSERT INTO settings (website_name, contact_email, phone_number, footer_text)
SELECT '', '', '', ''
WHERE NOT EXISTS (SELECT * FROM settings)
";
mysqli_query($conn, $insert_default_row);

// ---------------- INSERT ORDER ----------------
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['product_name'])){
    $product_name = mysqli_real_escape_string($conn,$_POST['product_name']);
    $total_price  = mysqli_real_escape_string($conn,$_POST['total_price']);
    $name         = mysqli_real_escape_string($conn,$_POST['name']);
    $phone        = mysqli_real_escape_string($conn,$_POST['phone']);
    $address      = mysqli_real_escape_string($conn,$_POST['address']);
    $sql = "INSERT INTO checkout (product_name,total_price,name,phone,address)
            VALUES ('$product_name','$total_price','$name','$phone','$address')";
    mysqli_query($conn,$sql);
}

// ---------------- DELETE ORDER ----------------
if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    mysqli_query($conn,"DELETE FROM checkout WHERE id=$id");
    header("Location: adminpanel.php?deleted=1");
    exit();
}

// ---------------- CONFIRM ORDER ----------------
if(isset($_GET['confirm_id'])){
    $id = intval($_GET['confirm_id']);
    mysqli_query($conn,"UPDATE checkout SET status='confirmed' WHERE id=$id");
    header("Location: adminpanel.php?confirmed=1");
    exit();
}

// ---------------- SAVE SETTINGS ----------------
$settings_saved_msg='';
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['website_name'])){
    $website_name  = mysqli_real_escape_string($conn,$_POST['website_name']);
    $contact_email = mysqli_real_escape_string($conn,$_POST['contact_email']);
    $phone_number  = mysqli_real_escape_string($conn,$_POST['phone_number']);
    $footer_text   = mysqli_real_escape_string($conn,$_POST['footer_text']);
    $update_sql = "UPDATE settings SET website_name='$website_name',
                    contact_email='$contact_email',
                    phone_number='$phone_number',
                    footer_text='$footer_text' WHERE id=1";
    if(mysqli_query($conn,$update_sql)){
        $settings_saved_msg="Settings saved successfully!";
    }else{
        $settings_saved_msg="Error saving settings: ".mysqli_error($conn);
    }
}

// ---------------- FETCH SETTINGS ----------------
$res_settings = mysqli_query($conn,"SELECT * FROM settings WHERE id=1");
$settings_row = mysqli_fetch_assoc($res_settings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-black dark:text-white flex min-h-screen">

<!-- SIDEBAR -->
<aside id="sidebar" class="w-64 bg-[#1f2937] dark:bg-gray-800 text-white p-6 transition-all duration-300">
<h1 class="text-2xl font-bold mb-6">Sapphire Admin</h1>
<nav class="space-y-4">
    <a href="#" onclick="showDashboard()" class="block py-2 px-3 rounded hover:bg-gray-700">Dashboard</a>
    <a href="#" onclick="openOrders()" class="block py-2 px-3 rounded hover:bg-gray-700">Orders</a>
    <a href="#" onclick="openSettings()" class="block py-2 px-3 rounded hover:bg-gray-700">Settings</a>
</nav>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-1 p-6">

<!-- ALERTS -->
<?php if(isset($_GET['deleted'])): ?>
<div id="deleteAlert" class="bg-green-500 text-white p-3 rounded mb-4">Order deleted successfully!</div>
<?php endif; ?>
<?php if(isset($_GET['confirmed'])): ?>
<div id="confirmAlert" class="bg-blue-500 text-white p-3 rounded mb-4">Order confirmed successfully!</div>
<?php endif; ?>
<?php if($settings_saved_msg!=''): ?>
<div class="bg-green-500 text-white p-3 rounded mb-4"><?= $settings_saved_msg ?></div>
<?php endif; ?>

<!-- DASHBOARD STATS -->
<div id="dashboardSection" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<h3 class="text-xl font-semibold mb-2">Total Orders</h3>
<p class="text-3xl font-bold"><?php $res=mysqli_query($conn,"SELECT COUNT(*) as total FROM checkout"); $row=mysqli_fetch_assoc($res); echo $row['total']; ?></p>
</div>
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<h3 class="text-xl font-semibold mb-2">Total Customers</h3>
<p class="text-3xl font-bold"><?php $res=mysqli_query($conn,"SELECT COUNT(DISTINCT name) as total_customers FROM checkout"); $row=mysqli_fetch_assoc($res); echo $row['total_customers']; ?></p>
</div>
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<h3 class="text-xl font-semibold mb-2">Total Revenue</h3>
<p class="text-3xl font-bold"><?php $res=mysqli_query($conn,"SELECT SUM(total_price) as revenue FROM checkout"); $row=mysqli_fetch_assoc($res); echo $row['revenue']?$row['revenue']:0; ?></p>
</div>
</div>

<!-- ORDERS SECTION -->
<div id="ordersSection" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow overflow-x-auto hidden">
<h3 class="text-2xl font-semibold mb-4">Recent Orders</h3>
<table class="min-w-full border-collapse text-sm">
<thead>
<tr class="bg-gray-100 dark:bg-gray-700 text-left">
<th class="p-3">ID</th><th class="p-3">Product</th><th class="p-3">Price</th>
<th class="p-3">Name</th><th class="p-3">Phone</th><th class="p-3">Address</th>
<th class="p-3">Actions</th><th class="p-3">Status</th>
</tr>
</thead>
<tbody>
<?php
$id=1;
$result=mysqli_query($conn,"SELECT * FROM checkout ORDER BY id DESC");
while($row=mysqli_fetch_assoc($result)){
echo '<tr class="bg-gray-200">
<td class="p-3">'.$id.'</td>
<td class="p-3">'.$row['product_name'].'</td>
<td class="p-3">'.$row['total_price'].'</td>
<td class="p-3">'.$row['name'].'</td>
<td class="p-3">'.$row['phone'].'</td>
<td class="p-3">'.$row['address'].'</td>
<td class="p-3 flex gap-2">
<a href="?confirm_id='.$row['id'].'" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700" onclick="return confirm(\'Confirm order?\')">Confirm</a>
<a href="?delete_id='.$row['id'].'" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm(\'Delete order?\')">Delete</a>
</td>
<td class="p-3">'.$row['status'].'</td>
</tr>';
$id++;
}
?>
</tbody>
</table>
</div>

<!-- SETTINGS SECTION -->
<div id="settingsSection" class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow hidden mt-6">
<h3 class="text-2xl font-semibold mb-4">Website Settings</h3>
<form method="POST" class="space-y-4">
<div><label>Website Name</label><input type="text" name="website_name" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings_row['website_name']) ?>"></div>
<div><label>Contact Email</label><input type="email" name="contact_email" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings_row['contact_email']) ?>"></div>
<div><label>Phone Number</label><input type="text" name="phone_number" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings_row['phone_number']) ?>"></div>
<div><label>Footer Text</label><input type="text" name="footer_text" class="w-full p-2 border rounded" value="<?= htmlspecialchars($settings_row['footer_text']) ?>"></div>
<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Settings</button>
</form>
</div>

</main>

<script>
// TOGGLE SECTIONS
function openOrders(){ document.getElementById('ordersSection').classList.remove('hidden'); document.getElementById('settingsSection').classList.add('hidden'); document.getElementById('dashboardSection').classList.add('hidden'); }
function openSettings(){ document.getElementById('settingsSection').classList.remove('hidden'); document.getElementById('ordersSection').classList.add('hidden'); document.getElementById('dashboardSection').classList.add('hidden'); }
function showDashboard(){ document.getElementById('dashboardSection').classList.remove('hidden'); document.getElementById('ordersSection').classList.add('hidden'); document.getElementById('settingsSection').classList.add('hidden'); }
const deleteAlert = document.getElementById("deleteAlert"); if(deleteAlert){ setTimeout(() => { deleteAlert.style.display = "none"; }, 2000);} const confirmAlert = document.getElementById("confirmAlert"); 
if(confirmAlert){ setTimeout(() => { confirmAlert.style.display = "none"; }, 2000);}
const deleteAlert=document.getElementById("deleteAlert"); if(deleteAlert){setTimeout(()=>{deleteAlert.style.display="none";},2000);}
const confirmAlert=document.getElementById("confirmAlert"); if(confirmAlert){setTimeout(()=>{confirmAlert.style.display="none";},2000);}
</script>

</body>
</html>
~~~


