<?php
$dbname="login";
include "C:/Users/dell/Desktop/xampp/htdocs/Todos/DbConnect/dbConnect.php";

session_start();
// no.of entry to display
$pages = $_SESSION['page'];
$result = $_SESSION['result'];
// formula of pagination 
$page_first_result = ($pages - 1) * $result;
$sort ='';
  if ($_POST['action'] == 'sorting') {
    $sort = isset($_POST['sort']) ? $_POST['sort'] : 'ASC';
  }
if(isset($_POST['action']) == 'fetchData') {
  $sql = "SELECT * FROM `student list` ORDER BY `Name` $sort  LIMIT $page_first_result, $result";
  $stmt = $conn->prepare($sql);
}
if($_POST['action'] == 'searchbox') {
  $search = $_POST['search'];
  $sql = "SELECT * FROM `student list`  WHERE `Name` LIKE ? ORDER BY `Name` $sort   LIMIT $page_first_result, $result";
  $stmt = $conn->prepare($sql);
  $searchParam = "%" . $search . "%";
  $stmt->bind_param('s',$searchParam);
} 




if($stmt === false) {
  die('Prepare failed'. htmlspecialchars($conn->$error));
};

$stmt->execute();
$result = $stmt->get_result();
$list = $result->fetch_all(MYSQLI_ASSOC);
foreach($list as $li) {
  echo '
    <tr class="bg-white border-b border-gray-200 relative group">
      <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap "><img src="./student/image/'.$li['Image'].'" class="w-20 h-20 object-cover"/></td>
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
$conn->close();
$stmt->close();
?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll('[id=edit]').forEach(editButton => {
        editButton.addEventListener('click', function (event) {
          let row = event.target.closest('tr'); // Get the current row
          console.log(row);
          let form = row.nextElementSibling; // Get the form row
          let inputRow = form.nextElementSibling; // Get the button row
          let buttonRow = inputRow.nextElementSibling; // Get the button row
          if (inputRow.style.display === "none" || inputRow.style.display === "") {
            inputRow.style.display = 'revert';
            form.style.display = 'revert';
            buttonRow.style.display = 'revert';
            row.style.display = 'none';
          } else {
            inputRow.style.display = 'none';
            form.style.display = 'none';
            buttonRow.style.display = 'none';
            row.style.display = 'table-row';
          }
        });
      });
  })
</script>