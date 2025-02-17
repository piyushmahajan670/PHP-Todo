<?php
  session_start();

  // Message for added successfully
  if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
  }

  // Getting the user ID and store it in the session so use in todos
  $dbname = "login";
  include "dbConnect.php";
  $sql = "SELECT `ID` FROM `users` WHERE `Name` = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $_SESSION['username']);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row['ID'];
    $_SESSION['ID'] = $id;
  } else {
    die("No user found.");
  }

  // adding todos
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['submit'])) {
      $todo = $_POST['todo'] ?? '';
      if (!empty($todo)) {
        $sql = "INSERT INTO `todos` (`UserId`, `LIST`) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $id, $todo);
        if ($stmt->execute()) {
          $_SESSION['message'] = "Added Successfully";
        } else {
          $_SESSION['message'] = "Error : " . $conn->error;
        }
        header('Location: ./todo.php');
        exit();
      }
    }
  }

 // Fetching the todos for displaying per the user id
  $sql = "SELECT * FROM `todos` WHERE `UserId` = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $_SESSION['ID']);
  $stmt->execute();
  $result = $stmt->get_result();
  $users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
      document.getElementById('edit').addEventListener('click', (e)=>{toggle(e)});
          function toggle(event){
            var input = document.getElementById('inputField');
            var display = document.getElementById('displayText');
            var edit = document.getElementById('edit');
            var div = document.getElementById('flex');
            if(input.style.display ==="none" ) {
              event.preventDefault();
              input.style.display = 'inline';
              input.style.width = '480px';
              display.style.display = 'none';
              edit.innerHTML = 'Save';
            } else {
              input.style.display = 'none';
              display.style.display = 'inline';
              edit.innerHTML = 'Edit';
            }
          }
        }
      )
    </script>
</head>
<body class="bg-[#fef8f8]">
    <?php include "header.php"; ?>
    <div class="w-[50%] mx-auto">
        <h1 class="text-5xl text-center mt-10 font-semibold text-red-400">
          Welcome, <?php echo $_SESSION['username'];?><br><br>
          <span class="hover:underline cursor-pointer text-blue-500 "> Your's Todo-s</span></h1>
        <div class="bg-white py-4 mt-10 shadow-md rounded-lg">
            <form method="post" action="todo.php" class="px-3 gap-4 flex flex-row items-center justify-center ">
                <input type="text" name="todo" placeholder="Add New" class="ps-4 h-18 w-[85%] outline-none">
                <button name="submit" type="submit" class="text-base cursor-pointer hover:opacity-85 bg-red-400 p-2 text-white rounded-md w-18 font-semibold">Add</button>
            </form>
        </div>
        <div class="todo-list flex flex-col mt-4">
            <?php
            if (count($users) > 0) { 
                foreach ($users as $row) {
            ?>
            <div class="flex flex-row items-center justify-between bg-white py-4 px-4 shadow hover:shadow-md hover:scale-105 gap-4 transition-all duration-400 ease-in-out rounded-md mt-4 w-full">
                <h5 class="text-base font-medium text-gray-500" id="displayText"><?php echo htmlspecialchars($row['List']);?></h5>
                
                <div class="flex items-center gap-2 justify-center" id="flex">
                  <form action="editTodo.php" method="post" class="flex justify-between w-full gap-4 ">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['No.'])?>">
                  <input type="text" id="inputField" name="input" value="<?php echo htmlspecialchars($row['List']); ?>" style="display: none;" class="w-fulll h-10 outline-0 border-gray-600 border ps-2 rounded-md">
                    <button name="edit" class="text-base cursor-pointer hover:bg-red-400 p-2  hover:text-white font-medium hover:rounded-md" id="edit">Edit</button>
                  </form>
                  <form action="deleteTodo.php" method="post">
                    <input type="hidden" name="list" value="<?php echo htmlspecialchars($row['List']);?>">
                    <button name="delete" id="delete" class="text-base cursor-pointer hover:bg-red-400 p-2 hover:text-white font-medium hover:rounded-md">Delete</button>
                  </form>
                </div>
            </div>
            <?php
                }
            } else {
                echo '             <div class="flex flex-col items-between justify-center bg-white py-4 px-4 shadow hover:shadow-md hover:scale-105 gap-4 transition-all duration-400 ease-in-out rounded-md mt-4">               <p class="text-base font-normal text-gray-500">The Todo-s List will appear here...</p>             </div>';
            }
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
   
</body>
</html>
