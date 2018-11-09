<?php
session_start(); //starting session to retrieve session variables
date_default_timezone_set('Asia/Kolkata'); //setting default time zone

if($_SESSION['level']=="staff" && $_SERVER['REQUEST_METHOD']=="POST"){

  include_once "../connections/connect.php";

  $sql = "update request set " . $_POST['attr'] . "=". $_POST['val'] . " where id=" . $_POST['id'] . ";";
  $request = pg_query($db, $sql);

  if($request) echo "Success";
  else echo "Failure";

}
?>
