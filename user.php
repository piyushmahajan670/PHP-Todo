<?php 
session_start();
if (!isset($_SESSION['username'])) {
  $_SESSION['message'] = "You must logged in first";
  header("Location: login.php");
  exit();
}

if (isset($_SESSION['message'])) {
  echo "
    <script>
      alert('" . $_SESSION['message'] . "');
    </script>";
  unset($_SESSION['message']);
}
$dbname = "login";
include "dbConnect.php";

$sql = "SELECT `ID`, `First Name`, `Name`, `Last Name`, `Phone Number`, `admin` FROM `users` WHERE `Name` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User</title>
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-[#fef8f8]">

<?php 
include "header.php"; 
if (count($users) > 0) {
  foreach ($users as $row) {
?>
  <div class="container mx-auto w-full py-15">
    <div class="flex flex-col justify-center items-center">
      <div class="bg-blue-100 rounded-t-lg py-4 w-[50%] ps-4">
        <h2 class="text-gray-600 text-lg font-semibold">
          Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
        </h2>
      </div>
      <div class="user-information bg-white w-[50%] px-10 py-8">
        <form action="updateDelete.php" method="post">
          <input type="hidden" value="<?php echo htmlspecialchars($row['ID']); ?>" name="id">
          <div class="flex flex-row justify-between gap-10 items-center">
            <div class="w-1/2">
              <h3 class="mb-2 text-gray-600 font-medium text-base">Username</h3>
              <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>"
                class="bg-blue-100 rounded-md w-full outline-0 p-2">
            </div>
            <div class="w-1/2">
              <h3 class="mb-2 text-gray-600 font-medium text-base">Phone Number</h3>
              <input type="number" name="number" value="<?php echo htmlspecialchars($row['Phone Number']); ?>"
                class="bg-blue-100 rounded-md w-full outline-0 p-2">
            </div>
          </div>
          <div class="flex flex-row py-10 items-center gap-10">
            <div class="w-1/2">
              <h3 class="mb-2 text-gray-600 font-medium text-base">First Name</h3>
              <input type="text" name="fname" value="<?php echo htmlspecialchars($row['First Name']); ?>"
                class="bg-blue-100 rounded-md w-full outline-0 p-2">
            </div>
            <div class="w-1/2">
              <h3 class="mb-2 text-gray-600 font-medium text-base">Last Name</h3>
              <input type="text" name="lname" value="<?php echo htmlspecialchars($row['Last Name']); ?>"
                class="bg-blue-100 rounded-md w-full outline-0 p-2">
            </div>
          </div>
          <div class="w-full flex justify-end gap-3 items-center">
            <button type="submit" name="update" class="bg-red-400 text-white h-10 w-18 rounded-md cursor-pointer">Update</button>
            <button type="submit" name="logout" class="bg-red-400 text-white h-10 w-18 rounded-md cursor-pointer">Logout</button>
            <button class="border-blue-400 border text-black hover:bg-blue-400 hover:text-white transition-all duration-300 h-10 w-18 rounded-md cursor-pointer" type="submit" name="delete">
              Delete
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
  }
} else {
  echo '
    <div class="col-span-2 bg-white shadow-md rounded-md h-full">
      <div class="user-information px-10 py-8">
        <h2 class="ps-4 text-gray-600 text-lg font-semibold">There are no registered users</h2>
        <p class="ps-4 mt-4 font-medium text-base">Please Register....</p>
      </div>
    </div>
  ';
}
$conn->close();
?>

</body>
</html>
