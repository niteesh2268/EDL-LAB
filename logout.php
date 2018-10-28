<?php
session_start();
session_unset(); //unsetting the session and deleting session variables
session_destroy();
header('Location:index.php'); //redirecting user to login page
?>
