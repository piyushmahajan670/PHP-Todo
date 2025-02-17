<?php
session_start();
if(isset($_SESSION['username'])) {
  header("Location: user.php");
} else if (isset($_SESSION['message'])) {
  echo "
    <script>
      alert('" . $_SESSION['message'] . "');
    </script>";
  unset($_SESSION['message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  <title>Login</title>
</head>
<body class="relative bg-[#fef8f8]">
  <?php include "../Components/header.php" ?>
  <div class="container mx-auto w-1/2  pt-20 ">
    <div class="bg-blue-100 rounded-t-lg py-4">
      <h2 class="ps-4 text-gray-600 text-xl font-semibold">Sign In</h2>
    </div>
    <div class="rounded-md bg-white py-8">
      <form action="login.php" method="post" class="flex flex-col justify-center items-center ">
        <input type="text" placeholder="Enter your username" name="username"
          class="p-2 border-blue-200 border-[2px] rounded-md w-[75%] outline-0 mx-auto text-black"><br>
        <input type="password" placeholder="Enter your password" name="password"
          class="p-2 border-blue-200 border-[2px] rounded-md w-[75%] outline-0 mx-auto text-black"><br>
        <button
          class="w-20 h-10 bg-red-400 hover:opacity-85 cursor-pointer text-white text-base font-semibold rounded-md ">Login
        </button>
        <?php
        // adding the dbname 
        $dbname = 'login';
        // Include the database connection file
        include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Validate form inputs
          if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $_SESSION['username'] = "$username";
            $_SESSION['password'] = "$password";
            // writing sql query 
            $sql = "SELECT * FROM `users` WHERE `Name` = '$username' AND `Password` = '$password'";

            $result = $conn->query($sql);

            // checking if the record found in the database
            if ($result->num_rows > 0) {
             $_SESSION['message'] = "Log In Successfully!";
              
            } else {
              echo '<p class="text-red-500">Invalid Credentials</p>';
            }
          }
          header("Location: user.php");
          exit();
        }
        $conn->close();
        ?>
      </form>
    </div>
  </div>
</body>

</html>