<!-- student list with use of ajax -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
  $_SESSION['message'] = "You must logged in first";
  header("Location: login.php");
  exit();
}

if (isset($_SESSION['message'])) {
  echo "<script>alert('" . $_SESSION['message'] . "');</script>";
  unset($_SESSION['message']);  

}
$dbname="login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";
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
$conn->close();
// getting the total pages to display in it
$number_of_pages = ceil($total_records/$results);

//determine which page number visitor is currently on  
if (!isset ($_GET['page']) ) {  
  $page = 1;  
} else {  
  $page = $_GET['page'];  
}
$_SESSION['page'] = $page;
$_SESSION['result'] = $results;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student List</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
     
      function getData() {
        $.ajax({
          url:"./search.php",
          type: "POST",
          data : { action : "fetchData" },
          success: function (response) {
            $("#displayText").html(response); // Replace the table body with the filtered data
          }
        })
      }
      getData();
      $("#search").keyup(function (event) {
        event.preventDefault();
        var searchValue = $(this).val();
        $.ajax({
          url: "./search.php",
          type: "POST",
          data: { action: "searchbox", search: searchValue },
          success: function (response) {
            $("#displayText").html(response); // Replace the table body with the filtered data
          },
          error: function () {
            console.error("Error fetching search results");
          },
        });
      });
      $("#sort").click(function (event) {
        event.preventDefault();
        let currentSort = $(this).attr("data-sort"); // Get current sort order
        let newSort = currentSort === "ASC" ? "DESC" : "ASC"; // Toggle sort order
        $(this).attr("data-sort", newSort); 
        $.ajax({
          url: "./search.php",
          type: "POST",
          data: { action: "sorting", sort : newSort},
          success: function (response) {
            $("#displayText").html(response);
            event.preventDefault();
          },
          error: function () {
            console.error("Error fetching search results");
          },
        });
      });
        // prevents a resubmission of form
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
  });
 
  </script>
</head>
<body class="bg-[#fef8f8] relative">
  <!-- including header -->
  <?php include "../Components/header.php" ?>
  <div class="container mx-auto my-4">

      <!-- heading -->
      <h1 class="text-5xl font-medium text-gray-600 text-center">Student's <span class="text-red-500 underline">List</span></h1>

      <!-- Table Layout -->
      <div class="relative overflow-x-auto px-10 my-10">
        <form name="Searching" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div class="flex justify-end mb-2">
            <input type="text" class="p-2 w-[25%] bg-blue-100 text-gray-600 rounded placeholder-gray-600 outline-none" placeholder="Search" id="search" name="search">
          </div>
        </form>
      <table class="w-full text-sm text-left rtl:text-right overflow-y-hidden shadow-md text-gray-500" id="table">
        <thead class="text-gray-700  uppercase bg-blue-100">
          <tr>
            <th scope="col" class="px-6 py-3">Image</th>
            <form name="Sorting" method="post" class="m-0 " action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <th>
                <button name="sort" id="sort" data-sort="ASC" value="<?php echo $sort; ?>" class="text-base">Student Name</button>
              </th>
            </form>
            <th scope="col" class="px-6 py-3">Age</th>
            <th scope="col" class="px-6 py-3">Gender</th>
            <th scope="col" class="px-6 py-3">Phone Number</th>
            <th scope="col" class="px-6 py-3">Qualification</th>
            <th scope="col" class="px-6 py-3">Residence</th>
            <th scope="col" class="px-6 py-3">Subject</th>  
          </tr>
        </thead>
        <tbody  id="displayText">
         <!-- Getting data through search.php -->
        </tbody>
      </table>
    </div>
    <!-- Table Ends -->

    <!-- Pagination -->
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
                <a href = "list.php?page=' . $page_num . '" class="flex items-center justify-center px-3  w-full h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-red-400 hover:text-white">' . $page_num . ' </a>
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
