<?php

session_start();
if($_SESSION['level']!="staff") die();

  include_once("../connections/connect.php");

  if(isset($_POST["material_id"])){

    // $sql = "select * from material where id=" . $_POST["material_id"] . ";";
    $sql = "select  distinct m.id,m.type,m.name,m.quantity,m.cost,m.comment,(m.quantity - (select coalesce(sum(quantity),0) as available from issual where material_id = ". $_POST["material_id"]." and  (actual_return = '0001-01-01 00:00:00' ))) as available from material as m,issual as i where m.id = ". $_POST["material_id"]." ;
";
    $request = pg_query($db, $sql);

    $row = pg_fetch_array($request);

    echo json_encode($row);

  }

 ?>
