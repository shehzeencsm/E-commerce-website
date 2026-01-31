<?php

 $servername="localhost";
 $username="root";
 $password="";
 $database="crud";



 $conn= mysqli_connect($servername, $username, $password, $database);

 if(!$conn){
    die("your connection is not build due to error---" . mysqli_error($conn));

 }

//  if($_SERVER['REQUEST_METHOD']=='POST'){
//  $name= $_POST['Name'];
//  $description= $_POST['Description'];

// $sql="INSERT INTO user (name, Description)
//  VALUES ('$name','$description')";

// $res= mysqli_query($conn, $sql);

// if($res){
//   echo"<div class='alert alert-success' role='alert'>
//   Your form is submitted
// </div>";
// }
// else{
//   echo"you're form is not submmited ";
// }
 
 
//  }
 
 
// ?>


// <!DOCTYPE html>
// <html lang="en">
// <head>
//   <meta charset="UTF-8" />
//   <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
//   <title>Crud</title>
//   <script src="https://cdn.tailwindcss.com"></script>
//  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
//  <link href="//cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css" rel="stylesheet">
//  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
 
//  <body class="items-center justify-center bg-blue-100">
//    <!-- Button trigger modal -->
// <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
//   Launch demo modal
// </button>

// <!-- Modal -->
// <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
//   <div class="modal-dialog">
//     <div class="modal-content">
//       <div class="modal-header">
//         <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
//         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
//       </div>
//       <div class="modal-body">
//         ...
//       </div>
//       <div class="modal-footer">
//         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
//         <button type="button" class="btn btn-primary">Save changes</button>
//       </div>
//     </div>
//   </div>
// </div>
  
//   <form action="registration one.php" method="POST" class="bg-white/40 w-[500px] h-[300px] mt-28 rounded-2xl">
//    <h1 class="pt-5 text-center"> <span class=" text-[20px] pr-4">Name:</span><input type="text" name="Name" id="" class="border-[1px] h-[35px] border-b-gray-400 w-[300px] rounded "></h1> <br>
//    <h1 class="text-center "> <span class="text-[20px] pr-4">Description:</span><input type="text" name="Description" id="" class="border-[1px] h-[35px] border-b-gray-400 w-[300px] rounded "></h1> <br>
  
   
//    <div class="flex justify-center">
//      <button class="text-black border-2 border-black w-[80px] rounded-2xl " >Submit</button>
//    </div>
   
   
//   </form>

  

//   <table class="table" id="myTable">
//   <thead>
//     <tr>
//       <th scope="col">Sno</th>
//       <th scope="col">Name</th>
//       <th scope="col">description</th>
//       <th scope="col">Actions</th>
//     </tr>
//   </thead>
//   <tbody> 
    
// <?php

// $sql="SELECT * FROM user";
// $result= mysqli_query($conn, $sql);
// while($row= mysqli_fetch_assoc($result)){
//   // echo $row ['sno']. " this is a user ". $row ['name']. "" . $row ['Description'];
//   echo
//   "<tr>
//       <th scope='row'>".$row['Sno']."</th>
//       <td>".$row['Name']."</td>
//       <td>".$row['Description']."</td>
//       ";
// }
// ?>
//   </tbody>
// </table>


//  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 
//  <script str="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>


 
//  <script>
//   let table = new DataTable('#myTable');
//  </script>
 
// </body>
// </html>
 ?>