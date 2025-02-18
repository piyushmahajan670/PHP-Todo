<?php
session_start();
if (!isset($_SESSION['username'])) {
  $_SESSION['message'] = "You must logged in first";
  header("Location: login.php");
  exit();
}
$dbname="login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";


// no.of entry to display
$results = 10;

// counting the rows of the data in database
$sql = "SELECT COUNT(*) as total FROM `student list`";
$stmt_count = $conn->prepare($sql);

if ($stmt_count === false) {
  die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row = $result_count->fetch_assoc();
$total_records = $row['total'];

$stmt_count->close();

// getting the total pages to display in it
$number_of_pages = ceil($total_records/$results);

//determine which page number visitor is currently on  
if (!isset ($_GET['page']) ) {  
  $page = 1;  
} else {  
  $page = $_GET['page'];  
}

// formula of pagination 
$page_first_result = ($page - 1) * $results;

// limiting the query to show specific entries
$sql = "SELECT `Id`,`Name`,`Gender`,`Phone Number`,`Qualification`,`Residence`,`Age` FROM `student list`ORDER BY `Id` DESC LIMIT " . $page_first_result . ',' . $results ;

$stmt = $conn->prepare($sql);

if($stmt === false) {
  die('Prepare failed'. htmlspecialchars($conn->$error));
};

$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
$stmt->close();
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
  <?php include "../Components/header.php" ?>
  <div class="container mx-auto my-4">
    <h1 class="text-5xl font-medium text-gray-600 text-center">Student's <span class="text-red-500 underline">List</span></h1>
    <div class="relative overflow-x-auto px-10 my-10">
      <table class="w-full text-sm text-left rtl:text-right shadow-md text-gray-500">
        <thead class="text-xs text-gray-700  uppercase bg-blue-100">
          <tr>
            <th scope="col" class="px-6 py-3">Student name</th>
            <th scope="col" class="px-6 py-3">Age</th>
            <th scope="col" class="px-6 py-3">Gender</th>
            <th scope="col" class="px-6 py-3">Phone Number</th>
            <th scope="col" class="px-6 py-3">Qualification</th>
            <th scope="col" class="px-6 py-3">Residence</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach($list as $li) {
          ?>
          <tr class="bg-white border-b border-gray-200">
            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo htmlspecialchars($li['Name']);?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($li['Age']);?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($li['Gender']);?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($li['Phone Number']);?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($li['Qualification']);?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($li['Residence']);?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <nav aria-label="Page navigation example " class="flex justify-center items-center mx-auto">
      <ul class="inline-flex -space-x-px text-base gap-2 h-10">
        <li>
          <a href="<?php echo ($page > 1) ? 'list.php?page=' . ($page - 1) : ''; ?>" 
          class="flex items-center justify-center px-3 w-full h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-red-400 hover:text-white <?php echo ($page == 1) ? 'pointer-events-none' : ''; ?>">
            Previous
          </a>
        </li>
        <?php 
        for($page_num = 1; $page_num<= $number_of_pages; $page_num++) {  
          echo '
          <li>
            <a href = "list.php?page=' . $page_num . '" class="flex items-center justify-center px-3 w-full h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-red-400 hover:text-white">' . $page_num . ' </a>
          </li>
          ';  
        };
        ?>
        <li>
          <a href="<?php echo  ($page < $number_of_pages) ? 'list.php?page=' . ($page + 1)  : '';?>" 
          class="flex items-center justify-center px-3 w-full h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-red-400 hover:text-white 
            <?php echo ($page == $number_of_pages) ? 'pointer-events-none' : ''; ?>">
            Next
          </a>
        </li>       
      </ul>
    </nav>
  </div>
</body>
</html>