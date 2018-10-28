<?php
// Supporting AJAX from index.php to add new component/equipment

session_start();
if($_SESSION['level']!="staff") die();

include_once("../connections/connect.php");

$sql = "select * from student where roll_no = '" . $_POST["roll_no"] . "';";
$result = pg_query($db, $sql);

$row = pg_fetch_array($result);

$student_id = $row['id'];

// print_r($_POST);

$sql = "insert INTO issual (student_id,staff_id,material_id,quantity,expected_return) VALUES('" .$student_id. "','" ;
  $sql.=$_POST["staff_id"] . "', '" ;
  $sql.=$_POST["material_id"]."','";
  $sql.=$_POST["quantity"]."','";
  $sql.=$_POST["expected_return"]. "')";

// echo $sql;

if(pg_query($db, $sql))
{
     echo 'success';
}

// $sql = "select * from issual where student_id='" . "'$student_id and staff_id='" .  $_POST["staff_id"] . "' and material_id='" . $_POST["material_id"] . "' and quantity='" . $_POST["quantity"] . "' and expected_return='" . $_POST["expected_return"] . "'";
// $result = pg_query($db, $sql);
// $row = pg_fetch_array($result);

 ?>
