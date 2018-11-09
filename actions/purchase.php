<?php
	// Supporting AJAX for live editing of information from home.php

	session_start();
	if($_SESSION['level']!="staff") die();

	include_once("../connections/connect.php");
	$id = $_POST["id"];
	$sql = "UPDATE request SET status = 'Purchased' WHERE id='".$id."'";
	if(pg_query($db, $sql))
	{
		echo 'Approved Succesfully';
	}

 ?>
