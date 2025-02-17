<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <title>Sign Up Now</title>
</head>

<body class="bg-[#fef8f8]">
  <?php include "header.php" ?>
  <div class="container mx-auto w-1/2  pt-20 ">
    <div class="bg-blue-100 rounded-t-lg py-4">
      <h2 class="ps-4 text-gray-600 text-xl font-semibold">Sign Up</h2>
    </div>
    <div class="rounded-md bg-white py-8 mb-4">
      <form action="register.php" method="post" class="flex items-center flex-col">
        <div class="flex gap-4 w-[75%] mb-3">
          <input type="text" placeholder="Enter your username" name="username"
            class="p-2 border-blue-200 border-[2px] rounded-md w-full  outline-0 text-black">
          <input type="text" placeholder="Enter your First Name" name="fname"
            class="p-2 border-blue-200 border-[2px] rounded-md w-full outline-0 mx-auto text-black">
        </div>
        <div class="flex gap-4 w-[75%] my-2">
          <input type="text" placeholder="Enter your Last Name" name="lname"
            class="p-2 border-blue-200 border-[2px] rounded-md w-full outline-0 mx-auto text-black">
          <input type="number" placeholder="Enter your Number" name="number"
            class="p-2 border-blue-200 border-[2px] rounded-md  w-full outline-0 mx-auto text-black">
        </div>
        <input type="password" placeholder="Enter your password" name="password"
          class="p-2 border-blue-200 border-[2px] rounded-md w-[75%] outline-0 mx-auto my-3 text-black">
        <input type="submit" name="submit" value="Register"
          class="w-20 h-10 bg-red-400 hover:opacity-85 cursor-pointer text-white text-base font-semibold rounded-md ">
        <?php
        // adding the db file to connect
        $dbname = "login";
        include "C:/Users/dell/Desktop/xampp/htdocs/test/testing/dbConnect.php";

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
          $fname = $_POST['fname'] ?? '';
          $lname = $_POST['lname'] ?? '';
          $username = $_POST['username'] ?? '';
          $password = $_POST['password'] ?? '';
          $num = $_POST['number'] ?? '';
          if
          (
            !empty($fname) &&
            !empty($lname) &&
            !empty($username) &&
            !empty($password) &&
            !empty($num)
          ) {
            // sql query 
            $sql = "INSERT INTO `users` (`ID`, `Name`, `Password`, `First Name`, `Last Name`, `Phone Number`) VALUES (NULL, '$username', '$password', '$fname', '$lname', '$num');";

            if ($conn->query($sql)) {
              echo '<p class="message success">User created successfully!</p>';
            } else {
              echo '<p class="message error">Error: ' . $conn->error . '</p>';
            }
            $conn->close();
          } else {
            echo '<p class="message error">All fields are required!</p>';
          }
        }
        ?>
      </form>
    </div>
  </div>
</body>

</html>