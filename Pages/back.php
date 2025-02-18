<?php
$result_per_page = 10;

// get the total entries in the database
$sql_count = "SELECT COUNT(*) as total FROM `student list`";
$result_count = mysqli_query($conn, $sql_count);

if ($result_count === false) {
    die('Query failed: ' . htmlspecialchars(mysqli_error($conn)));
}

$row = mysqli_fetch_assoc($result_count);
$total_records = $row['total'];

// $total_records = $row['total'];
$number_of_page = ceil($number_of_result / $result_per_page);
echo $number_of_page;


//determine which page number visitor is currently on  
if (!isset ($_GET['page']) ) {  
  $page = 1;  
} else {  
  $page = $_GET['page'];  
}
$page_first_result = ($page - 1) * $result_per_page;

$query = "SELECT `Id`,`Name`,`Gender`,`Phone Number`,`Qualification`,`Residence`,`Age` FROM `student list` LIMIT " . $page_first_result . ',' . $result_per_page;

$result = mysqli_query($conn, $query);
$list = mysqli_fetch_all($result, MYSQLI_ASSOC);
