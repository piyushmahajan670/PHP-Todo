<?php
$dbname = "login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  session_start();

  // Retrieve form data
  $name = $_POST['name'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $qualification = $_POST['qualification'];
  $reside = $_POST['residence'];
  $num = $_POST['number'];

  // Handle file upload
  if (isset($_FILES["uploadfile"]) && $_FILES["uploadfile"]["error"] == 0) {
      $filename = basename($_FILES["uploadfile"]["name"]);
      $tempname = $_FILES["uploadfile"]["tmp_name"];
      $folder = "./image/" . $filename;

      // Create directory if it doesn't exist
      if (!is_dir('./image/')) {
          mkdir('./image/', 0777, true);
      }

      // Move uploaded file
      if (move_uploaded_file($tempname, $folder)) {
        // Prepare SQL statement
        $sql = "INSERT INTO `student list` 
              (`Id`, `Name`, `Age`, `Gender`, `Phone Number`, `Qualification`, `Residence`, `Image`) 
              VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
          $_SESSION['message'] = "Prepare failed: " . htmlspecialchars($conn->error);
          header("Location: ../student.php");
          exit();
        }

        // Bind parameters and execute
        $stmt->bind_param('sssssss', $name, $age, $gender, $num, $qualification, $reside, $filename);

        if ($stmt->execute()) {
          $_SESSION['message'] = "Student Added Successfully!";
        } else {
          $_SESSION['message'] = "Error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
      } else {
        $_SESSION['message'] = "Failed to upload image.";
      }
  } else {
    $_SESSION['message'] = "No file uploaded or an error occurred.";
  }

  $conn->close();
  header("Location: ../student.php");
  exit();
}
?>