<?php
// Supporting AJAX from index.php to add new component/equipment

include_once("../connections/connect.php");

session_start();
if($_SESSION['login']!="success") header("Location: index.php");

$sql = "Select * from faculty where email = '" . $_POST['facultyRef'] . "'";
$result = pg_query($db, $sql);

$nrows = pg_num_rows($result);

if($nrows!=1){
  echo "Please enter valid faculty email";
  die();
}

$row = pg_fetch_array($result);
$sql = "INSERT INTO request (name,type,cost,quantity,cause, student_id, faculty_id, status) VALUES('".$_POST["name"]."','".$_POST["type"]."', ". $_POST['cost'] . "," . $_POST["quantity"] . ",'" . $_POST["cause"] . "'," . $_SESSION['id'] . ", '" . $row['id'] . "', 'Approval Pending')";

if(pg_query($db, $sql))
{
     echo $_POST["type"] . ' Requested';
}
 ?>
