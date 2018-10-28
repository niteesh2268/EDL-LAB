<?php

	session_start();
	if($_SESSION['level']!="staff") die();

	include_once("../connections/connect.php");

	$sql = "DELETE FROM material WHERE id = '".$_POST["id"]."'";
	if(pg_query($db, $sql))
	{
		echo 'Data Deleted';
	}
 ?>
