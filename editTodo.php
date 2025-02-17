<?php
  $dbname="login";
  include "dbConnect.php";

  session_start();
  if($_SERVER['REQUEST_METHOD']=="POST") {
    $list = $_POST['input'];
    $id = $_POST['id'];

    $sql = "UPDATE `todos` SET `List`=? WHERE `todos`.`No.` = ?";
    $stmt = $conn->prepare($sql);
      if ($stmt === false) {
        die('Prepare failed'. htmlspecialchars($conn->$error));
      }

      $stmt->bind_param('si',$list,$id);
      // exceuting the statement
      $stmt->execute() ? $_SESSION['message'] = "Todo Updated Successfully!" : $_SESSION['message'] = "An error occurred while updating the todo.";
      
      $stmt->close();
      $conn->close();
      header("Location: todo.php");
      exit();
  }
?>