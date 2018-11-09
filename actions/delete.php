<?php

	session_start();
	if($_SESSION['level']!="staff") die();

	include_once("../connections/connect.php");

	$sql = "UPDATE material SET delete_flag = '1' WHERE id = '".$_POST["id"]."'" ;
	$result = pg_query($db, $sql);
	$value = pg_affected_rows($result);
	if($value == 0)
	{
		echo 'failure';
	}
	else
	{
		echo 'success';
	}
 ?>
