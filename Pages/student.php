<?php
session_start();
if (!isset($_SESSION['username'])) {
  $_SESSION['message'] = "You must logged in first";
  header("Location: login.php");
  exit();
}
// Message for added successfully
if (isset($_SESSION['message'])) {
  echo "<script>alert('" . $_SESSION['message'] . "');</script>";
  unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student List</title>
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#fef8f8]">
  <?php include '../Components/header.php';?>
  <div class="container py-14 mx-auto px-14">
    <h1 class="text-5xl font-bold text-center cursor-pointer text-gray-600"> Student's <span class="text-red-500 underline">Detail</span></h1>
    <div class="student-record mt-10 w-[50%] shadow-md hover:shadow-lg rounded-lg mx-auto bg-white">
      <div class="bg-blue-100 rounded-t-lg py-4">
        <h2 class="ps-4 text-gray-600 text-xl font-semibold"> Enter the Student's Details </h2>
      </div>
      <div class="rounded-md bg-white p-4">
        <form action="./student/Insert.php" method="post" class="px-3" enctype="multipart/form-data">
          <div class="grid grid-cols-2 gap-3 w-full">
            <div class="flex flex-col gap-2 w-full">
              <label class="text-lg text-gray-600 font-medium">Student Name</label>
              <input type="text" name="name" class="w-full outline-0 bg-blue-100 focus:bg-blue-200 p-2" required>
            </div>
            <div class="flex flex-col gap-2 w-full">
              <label class="text-lg text-gray-600 font-medium">Age</label>
              <input type="text" name="age" class="w-full outline-0 bg-blue-100 focus:bg-blue-200 p-2" required>
            </div>
            <div class="flex flex-col gap-2 pt-3 w-full">
              <label class="text-lg text-gray-600 font-medium">Gender</label>
              <select name="gender" class="w-full outline-0 bg-blue-100 p-2 focus:bg-blue-200">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="flex flex-col gap-2 pt-3 w-full">
              <label class="text-lg text-gray-600 font-medium">Qualification</label>
              <input type="text" name="qualification" class="w-full outline-0 bg-blue-100 focus:bg-blue-200 p-2" required>
            </div>
            <div class="flex flex-col gap-2 pt-3 justify-center">
              <label class="text-lg text-gray-600 font-medium">Phone Number</label>
              <input type="number" class="w-full outline-0 bg-blue-100                focus:bg-blue-200 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none p-2" name="number" required>
            </div>
            <div class="flex flex-col gap-2 pt-3  w-full">
              <label class="block text-lg font-medium text-gray-600 ">Upload file</label>
              <input type="file" name="uploadfile" class=" w-full h-full  h-10 p-2 bg-blue-100 cursor-pointer">
            </div> 
            <div class="col-span-2 flex flex-col gap-2 pt-3 justify-center">
              <label class="text-lg text-gray-600 font-medium">Residence</label>
              <textarea name="residence" class="bg-blue-100 resize-none focus:bg-blue-200 h-30 p-2 outline-0 w-full" id=""></textarea>
            </div>
          </div>
          <button type="submit" class="mt-4 hover:opacity-85 cursor-pointer bg-blue-400 w-18 h-10 text-base font-semibold text-white">Submit</button>
        </form>
      </div>  
    </div>
    <div class="student-entries-link text-center my-4">
      <a href="list.php" class="text-lg font-bold text-red-400 hover:underline">Click To Get All The List Of Students</a>
    </div>
  </div>
</body>
</html>