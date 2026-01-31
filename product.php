<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "bangle db";

$conn = mysqli_connect($servername, $username, $password, $database);

if(!$conn){
    die("Database Connection Failed: " . mysqli_error($conn));
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // IMAGE UPLOAD
    $image_name = $_FILES['image']['name'];
    $tmp_name   = $_FILES['image']['tmp_name'];

    if(!is_dir("uploads")){
        mkdir("uploads", 0777, true);
    }

    $upload_path = "uploads/" . $image_name;

    if(move_uploaded_file($tmp_name, $upload_path)){

        $sql = "INSERT INTO productuploaded (`name`, `price`, `description`, `image`)
                VALUES ('$name', '$price', '$description', '$image_name')";

        if(mysqli_query($conn, $sql)){
            echo "<script>alert('Product Added Successfully');</script>";
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }

    } else {
        echo "<script>alert('Image Upload Failed');</script>";
    }
}

// Fetch data
$result = mysqli_query($conn, "SELECT * FROM productuploaded");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Management - Sapphire Jewels</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">

<div class="flex min-h-screen">

   
    <aside class="w-64 bg-gray-800 text-white flex flex-col p-6 space-y-6">
        <h2 class="text-3xl font-bold">Sapphire Admin</h2>
        <nav class="space-y-3 mt-10">
            <a href="adminpanel old.php" class="block py-2 px-3 rounded hover:bg-gray-700 transition">Dashboard</a>
            <a href="product.php" class="block py-2 px-3 rounded bg-gray-700 font-semibold transition">Products</a>
        </nav>
    </aside>

    <main class="flex-1 p-10 space-y-12">

      
        <section class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Add New Product</h2>

            <form action="product.php" method="POST" enctype="multipart/form-data" class="space-y-5">

                <div>
                    <label class="block font-medium mb-1">Product Name</label>
                    <input type="text" name="name" required class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Price</label>
                    <input type="number" name="price" required class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full p-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1">Upload Image</label>
                    <input type="file" name="image" required class="w-full p-2 border rounded-lg">
                </div>

                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-lg font-semibold transition">Save Product</button>

            </form>
        </section>

       
        <section class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg">
            <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">All Products</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse table-auto text-left">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="p-3 font-medium text-gray-700 dark:text-gray-200">ID</th>
                            <th class="p-3 font-medium text-gray-700 dark:text-gray-200">Image</th>
                            <th class="p-3 font-medium text-gray-700 dark:text-gray-200">Name</th>
                            <th class="p-3 font-medium text-gray-700 dark:text-gray-200">Price</th>
                            <th class="p-3 font-medium text-gray-700 dark:text-gray-200">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <td class="p-3"><?php echo $row['Id']; ?></td>
                            <td class="p-3">
                                <img src="uploads/<?php echo $row['image']; ?>" class="w-20 h-20 object-cover rounded shadow">
                            </td>
                            <td class="p-3"><?php echo $row['name']; ?></td>
                            <td class="p-3 text-green-700 font-bold">$<?php echo $row['price']; ?></td>
                            <td class="p-3"><?php echo $row['description']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</div>

</body>
</html>
