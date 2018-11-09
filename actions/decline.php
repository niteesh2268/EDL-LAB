<?php
	// Supporting AJAX for live editing of information from home.php

	session_start();
	if($_SESSION['level']!="faculty") die();

	include_once("../connections/connect.php");
	$id = $_POST["id"];
	$sql = "UPDATE request SET status = 'Declined' WHERE id='".$id."'";
	if(pg_query($db, $sql))
	{
		echo 'Declined Succesfully';
	}

 ?>
