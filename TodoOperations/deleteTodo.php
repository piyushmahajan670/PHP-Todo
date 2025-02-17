<?php
$dbname="login";
include "C:/Users/dell/Desktop/xampp/htdocs/todos/DbConnect/dbConnect.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $list = $_POST['list'];
  $sql = "DELETE FROM `todos` WHERE `todos`.`List` = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
  }

  // Bind the parameter
  $stmt->bind_param("s", $list);

  // Execute the statement
  if($stmt->execute()) {
    $_SESSION['message'] = "Your To-Do Deleted Successfully!";
  } else {
    $_SESSION['message'] = "An error occurred while deleting the todo.";
  }
  
  $stmt->close();
  $conn->close();
  header("Location: ../Pages/todo.php");
  exit();

}