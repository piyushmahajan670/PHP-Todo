<?php
  $dbname = 'login';
  include "C:/Users/dell/Desktop/xampp/htdocs/test/testing/dbConnect.php";
 
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $id = (int) $_POST['id'];
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $number = $_POST['number'];
    session_start();

    if(isset($_POST['update']))
    {
      $_SESSION['username'] = "$username";
      $_SESSION['password'] = "$password";
      $sql = "UPDATE `users` SET `Name` = ?, `First Name` = ?, `Last Name` = ?, `Phone Number` = ? WHERE `users`.`ID` = ?;";

      $stmt = $conn->prepare($sql);
      if ($stmt === false) {
        die('Prepare failed'. htmlspecialchars($conn->$error));
      }

      $stmt->bind_param('ssssi',$username,$fname,$lname,$number,$id);

      // exceuting the statement
      $stmt->execute() ? $_SESSION['message'] = "User Updated Successfully!" : $_SESSION['message'] = "An error occurred while updating the user.";
      
      $stmt->close();
      $conn->close();
      header("Location: user.php");
      exit();
    } 
    elseif (isset($_POST["delete"])) 
    {
      $sql = "DELETE FROM `users` WHERE `users`.`ID` = ?";

      $stmt = $conn->prepare($sql);
      if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
      }

      // Bind the parameter
      $stmt->bind_param("i", $id);

      // Execute the statement
      if($stmt->execute()) {
        $_SESSION['message'] = "User Deleted Successfully!";
        unset($_SESSION['username']);
        unset($_SESSION['ID']);
        unset($_SESSION['password']);
        session_destroy();
      } else {
        $_SESSION['message'] = "An error occurred while deleting the user.";
      }
      
      $stmt->close();
      $conn->close();
      header("Location: register.php");
      exit();
    }
    elseif (isset($_POST["logout"]))
    {
      unset($_SESSION['username']);
      unset($_SESSION['ID']);
      unset($_SESSION['password']);
      session_destroy();
      header("Location: login.php");
      exit();
    }
  }
?>