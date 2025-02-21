<?php
session_start();
$dbname="login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $qualification = $_POST['qualification'];
    $reside = $_POST['residence'];
    $num = $_POST['number'];
    $checkbox = $_POST['subject'];
    $sub = implode(', ', $checkbox);

    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "./image/" . $filename;

    if ($filename) {
      if (move_uploaded_file($tempname, $folder)) {
        $sql = "UPDATE `student list` 
                SET `Name` = ?, `Age` = ?, `Gender` = ?, `Phone Number` = ?, `Qualification` = ?, `Residence` = ?, `Image` = ? , `Subject`=? WHERE `ID` = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssi', $name, $age, $gender, $num, $qualification, $reside, $filename,$sub, $id);
      } else {
        $_SESSION['message'] = "Failed to upload image.";
        header("Location: ../list.php");
        exit();
      }
    } else {
      $sql = "UPDATE `student list` 
          SET `Name` = ?, `Age` = ?, `Gender` = ?, `Phone Number` = ?,      `Qualification` = ?, `Residence` = ? ,`Subject` =?  WHERE `ID` = ?;";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('sssssssi', $name, $age, $gender, $num, $qualification, $reside,$sub, $id);
    }

    if ($stmt->execute()) {
      $_SESSION['message'] = "Student Updated Successfully!";
    } else {
      $_SESSION['message'] = "Error: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close();
    header("Location: ../list.php");
    exit();
  } elseif (isset($_POST["delete"])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM `student list` WHERE `student list`.`ID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
      $_SESSION['message'] = "List Deleted Successfully!";
    } else {
      $_SESSION['message'] = "An error occurred while deleting the user.";
    }
    
    $stmt->close();
    $conn->close();
    header("Location: ../list.php");
    exit();
  } elseif (isset($_POST["cancel"])) {
    $conn->close();
    header("Location: ../list.php");
  }
}
?>