<?php
$dbname = "login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";

if($_SERVER["REQUEST_METHOD"]== "POST") {
  $name = $_POST['name'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $qualification = $_POST['qualification'];
  $reside = $_POST['residence'];
  $num = $_POST['number'];
  session_start();
  $sql = "INSERT INTO `student list` (`Id`, `Name`, `Age`, `Gender`, `Phone Number`, `Qualification`, `Residence`) VALUES (NULL, ?,?,?,?,?,?); ";

  $stmt = $conn->prepare($sql);
  if($stmt === false) {
    die('Prepare failed'. htmlspecialchars($conn->$error));
  };
  $stmt->bind_param('ssssss',$name,$age,$gender,$num,$qualification,$reside);
  // exceuting the statement
  $stmt->execute() ? $_SESSION['message'] = "Student Added Successfully!" : $_SESSION['message'] = "An error occurred while adding the student.";
      
  $stmt->close();
  $conn->close();
  header("Location: ../student.php");
  exit();
}
?>