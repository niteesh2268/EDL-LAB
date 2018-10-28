<?php
include_once("../connections/connect.php");

$i=1;
$sql = "select distinct material.id as material_id, material.name as name from issual, material where issual.material_id = material.id;";// . " and status='Approval Pending'";
$request=pg_query($db, $sql);
while($row = pg_fetch_array($request)){
  echo "{id: " . $row['material_id'] . ", content: '" . $row['name'] . "'}";
  if($i!=pg_num_rows($request)){ echo ","; }
  $i++;
}
?>
