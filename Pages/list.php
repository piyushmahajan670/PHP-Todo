<?php
$dbname="login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";
session_start();

$results = 10;
$search = isset($_GET['search'] ) ? $_GET['search'] : '';

$sql = "SELECT COUNT(*) as total FROM `student list` WHERE `Name` LIKE ?";
$stmt_count = $conn->prepare($sql);

$searchname= "%".$search . "%";
$stmt_count->bind_param('s',$searchname);
if($stmt_count === false) {
  die('Preparing Error'. $conn->error); 
};
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row = $result_count->fetch_assoc();
$total_records = $row['total'];
$stmt_count->close();

$noOfPages = ceil($total_records/$results);
//determine which page number visitor is currently on  
if (!isset ($_GET['page']) ) {  
  $page = 1;  
} else {  
  $page = $_GET['page'];  
}

$page_first_result = ($page - 1) * $results;

//SORTING
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC';
// SEARCHING 
$search = isset($_GET['search'] ) ? $_GET['search'] : '';
$sql = "SELECT * FROM `student list` WHERE `Name` LIKE ? ORDER BY `Name` $sort LIMIT $page_first_result, $results";
$stmt = $conn->prepare($sql);
$searchname= "%".$search . "%";
$stmt->bind_param('s',$searchname);
if($stmt === false) {
  die('Preparing Error'.$conn->error);
};
$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_all(MYSQLI_ASSOC);
$searchQuery = isset($_GET['search']) ? "&search=".$_GET['search'] : '';
$sortQuery = isset($_GET['sort']) ? "&sort=".$_GET['sort'] : '';
$conn->close();
$number = 1;
$currentNumber = ($page  - 1 ) * $results + $number;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP List</title>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
    })
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#fef8f8] relative">
  <?php include '../Components/header.php';?>
  <div class="container mx-auto my-4">

    <!-- heading -->
    <h1 class="text-5xl font-medium text-gray-600 text-center">Student's <a href="list.php" class="text-red-500 underline">List</a></h1>

    <!-- Table Layout -->
    <div class="relative overflow-x-auto px-10 my-12">
      <form name="Searching" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="flex justify-end mb-2 relative">
        <input type="hidden" name="page" value="<?php echo $page?>">
          <input type="text" class="p-2 h-10 w-[45%] bg-blue-100 text-gray-600 rounded placeholder-gray-600 outline-none" placeholder="Search" id="search" name="search">
          <button class="absolute h-10 w-10 right-[1px] p-2 w-20 text-white rounded bg-red-400">Search</button>
          <?php
          if(!empty($sortQuery)) {
            echo "
            <input type='hidden' name='sort' value='$sort'/>
            ";
          }
          ?>    
        </div>
      </form>
      <table class="w-full text-sm text-left rtl:text-right overflow-y-hidden shadow-md text-gray-500 mt-8" id="table">
        <thead class="text-gray-700  uppercase bg-blue-100">
          <tr>
            <th scope="col" class="px-6 py-3">S. No.</th>
            <th scope="col" class="px-6 py-3">Image</th>
            <?php 
            $sort == 'ASC' ? $sort = 'DESC' : $sort = 'ASC';
            ?>
            <form name="Sorting" method="get" class="m-0 " action="list.php">
              <input type="hidden" name="page" value="<?php echo $page?>">
              <th>
              <?php
              if(!empty($searchQuery)) {
                echo "
                <input type='hidden' name='search' value='$search'/>
                ";
              }
              ?>
              <button name="sort" id="sort" value="<?php echo $sort; ?>" class="text-base">Student Name</button>
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
        <tbody>
          <?php
          if(count($list) > 0) {
          foreach($list as $li) {
              echo 
            '
            <tr class="bg-white border-b border-gray-200 relative group">
              <td class="px-6 py-4">'. $currentNumber++.'</td>
              <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap "><img src="./student/image/'.$li['Image'].'" class="w-20 h-20 object-contain rounded-full"/></td>
              <td class="px-6 py-4">'.$li['Name'].'</td>
              <td class="px-6 py-4">'.$li['Age'].'</td>
              <td class="px-6 py-4">'.$li['Gender'].'</td>
              <td class="px-6 py-4">'.$li['Phone Number'].'</td>
              <td class="px-6 py-4">'.$li['Qualification'].'</td>
              <td class="px-6 py-4">'.$li['Residence'].'</td>
              <td class="px-6 py-4">'.$li['Subject'].'</td>
              <td>
                <a href="./editList.php?id='.$li['Id'].'" class="absolute top-2 bg-red-400 rounded text-white px-2 py-[3px] h-7 w-10 cursor-pointer hidden  right-10 group-hover:inline-block top-0" id="edit">Edit</a>
              </td>
            </tr>
            '; 
          }
          }else {
            echo "
            <tr class='bg-white border-b border-gray-200 relative group'>
            <td colspan='9' class='text-center text-base font-medium py-6'>No Student Found</td>
            </tr>
            ";
          }
          ?>
        </tbody>
      </table> 
    </div>
     <!-- Table Ends -->

    <!-- Pagination -->
    <nav aria-label="Page navigation example " class="flex justify-center items-center mx-auto">
      <ul class="inline-flex -space-x-px text-base gap-2 h-10">
        <?php 
        // Previous button only to show when page is on number 2
        if($page > 1) {
          echo '
            <li>
          <a href="list.php?page='.($page - 1) .$searchQuery.$sortQuery.'" class=" flex items-center justify-center px-3 w-full h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-red-400 hover:text-white">
            Previous
          </a>
        </li>
          ';
        }
        // NO of pages
          for($page_num = 0; $page_num < $noOfPages; $page_num++) {  
            echo '
            <li>
            <a href = "list.php?page=' . $page_num + 1 . $searchQuery .$sortQuery.'" class="flex items-center justify-center px-3  w-full h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-red-400 hover:text-white">' . $page_num  + 1 . ' </a> 
              </li>
            ';  
          };
        // Next button hide when page is complete full
        if($page < $noOfPages) {
          echo '
            <li>
              <a href="list.php?page='.$page+1 .$searchQuery . $sortQuery.'" class="flex items-center justify-center px-3 w-full h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-red-400 hover:text-white ">Next </a>
            </li>
          ';
        }
        ?>     
      </ul>
    </nav>
  </div>
</body>
</html>

<?php

if (!isset($_SESSION['username'])) {
  $_SESSION['message'] = "You must logged in first";
  header("Location: ./login.php");
  exit();
}

if (isset($_SESSION['message'])) {
  echo "<script>alert('" . $_SESSION['message'] . "');</script>";
  unset($_SESSION['message']);  

}

?>