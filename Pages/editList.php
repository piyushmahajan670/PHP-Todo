<?php

session_start();
if (!isset($_SESSION['username'])) {
  $_SESSION['message'] = "You must logged in first";
  header("Location: login.php");
  exit();
}
$dbname = "login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";
$id = $_GET['id'];
$sql = "SELECT * FROM `student list` WHERE`Id` = ? " ;
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
$checkbox = array('Maths','Chemistry','Physics','English','IT');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.getElementById('file').addEventListener('change', function (event) {
          const file = event.target.files[0];
          console.log(file);
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
          }
        })
      });
  </script>
</head>
<body class="bg-[#fef8f8] relative">
  <?php 
    include "../Components/header.php"; 
  ?>
  <div class="container mx-auto my-4">
    <h1 class="text-5xl font-medium text-gray-600 my-14 text-center">Edit Student's <span class="text-red-500 underline">List</span></h1>
    <?php
      if (count($list) > 0) {
        foreach ($list as $li) {
        
    ?>
    <form action="./student/UpdateDeleteList.php" method="post" enctype="multipart/form-data" >
      <div class="w-[50%] mx-auto grid grid-cols-2 items-center gap-4" >
        <div class="col-span-2 flex flex-col gap-4 justify-center items-center">
        <label class="text-lg text-gray-600 font-medium ">Student Image</label>
          <input type="hidden"  value="<?php echo htmlspecialchars($li['Id']);?>" name="id">
          <img id="preview" src="./student/image/<?php echo htmlspecialchars($li['Image']); ?>" class="w-52 object-contain   h-52   rounded-md mb-2"/>
          <input type="file" name="uploadfile" id="file" class="bg-blue-100 rounded-md w-full outline-0 p-2"  />
        </div>
        <div>
         <label class="text-lg text-gray-600 font-medium">Name</label>
          <input type="text" value="<?php echo htmlspecialchars($li['Name']);?>" name="name"  class="bg-blue-100 rounded-md w-full outline-0 p-2"/>
        </div>
        <div>
         <label class="text-lg text-gray-600 font-medium">Age</label>
          <input type="text" value="<?php echo htmlspecialchars($li['Age']);?>" name="age" class="bg-blue-100 rounded-md w-full outline-0 p-2"/>
        </div>
        <div>
         <label class="text-lg text-gray-600 font-medium">Gender</label>
          <input type="text" value="<?php echo htmlspecialchars($li['Gender']);?>" name="gender" class="bg-blue-100 rounded-md w-full outline-0 p-2"/>
        </div>
        <div>
         <label class="text-lg text-gray-600 font-medium">Phone Number</label>
          <input type="text" value="<?php echo htmlspecialchars($li['Phone Number']);?>" name="number" class="bg-blue-100 rounded-md w-full outline-0 p-2"/>
        </div>
        <div>
         <label class="text-lg text-gray-600 font-medium">Qualification</label>
          <input type="text" value="<?php echo htmlspecialchars($li['Qualification']);?>" name="qualification" class="bg-blue-100 rounded-md w-full outline-0 p-2"/>
        </div>
        <div>
         <label class="text-lg text-gray-600 font-medium">Residence</label>
          <input type="text" value="<?php echo htmlspecialchars($li['Residence']);?>" name="residence" class="bg-blue-100 rounded-md w-full outline-0 p-2"/>
        </div>
        <div class="col-span-2 flex flex-col items-center gap-2 pt-3 justify-center">
         <label class="text-lg text-gray-600 font-medium">Subject</label>
          <div class="flex flex-wrap  gap-8">
            <?php
              $Selected = explode(',',$li['Subject']);
              $selectedCheckbox = array_map('trim',$Selected);
              foreach ($selectedCheckbox as $chk) {
                echo "
                <div class='flex items-center gap-1'>
                  <input type='checkbox' id='Chemistry' checked name='subject[]' class='h-4 w-4' value='$chk'>
                  <label for='$chk'>$chk</label>
                </div>
                ";
              }
              $diff = array_diff($checkbox,$selectedCheckbox);
              foreach ($diff as $chk) {
                echo "
                <div class='flex items-center gap-1'>
                  <input type='checkbox' id='Chemistry' name='subject[]' class='h-4 w-4' value='$chk'>
                  <label for='$chk'>$chk</label>
                </div>
                ";
              }
            ?>
          </div>
        </div>
        <div class="mx-auto col-span-2">
          <button class="bg-red-400 rounded text-white p-2 cursor-pointer mx-1" name="update">Save</button>
          <button class="bg-red-400 rounded text-white p-2 cursor-pointer mx-1"   name="delete">Delete</button>
          <button class="bg-red-400 rounded text-white p-2 cursor-pointer mx-1" name="cancel">Cancel</button>
        </div>
      </div>
    </form>
    <?php
        }
      }
    ?>
  </div>
</body>
</html>