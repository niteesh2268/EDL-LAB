<?php
// Supporting AJAX from index.php to add new component/equipment

session_start();
if($_SESSION['level']!="staff") die();

include_once("../connections/connect.php");

if($_SESSION['level']!="staff") header("Location: ../index.php");

$sql = "INSERT INTO material(name,type,cost,quantity,comment) VALUES('".$_POST["name"]."','".$_POST["type"]."', '".$_POST["cost"]."','".$_POST["quantity"]."','".$_POST["comment"]."')";
if(pg_query($db, $sql))
{
     echo 'Data Inserted';
}
 ?>
