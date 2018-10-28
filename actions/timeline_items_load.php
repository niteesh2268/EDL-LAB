<?php
include_once("../connections/connect.php");

$i=1;
// select  from issual, student, material where issual.material_id = material.id and issual.student_id = student.id;

$sql = "select issual.id as id, issual.quantity as quantity, student.roll_no as roll_no, material.id as material_id, issual_instance, expected_return, actual_return from issual, student, material where issual.material_id = material.id and issual.student_id = student.id and issual.return_flag='f'" ;// . " and status='Approval Pending'";
$request = pg_query($db, $sql);
echo "{";
while($row = pg_fetch_array($request)){

  $dDate = date_parse($row['issual_instance']);
  $dMonth = intval($dDate['month'])-1 ;
  $dDate = "'" . $dDate['year'] . "', '" . $dMonth . "', '" . $dDate['day'] . "', '" . $dDate['hour'] . "', '" . $dDate['minute'] . "', '" . $dDate['second'] . "'";

  $aDate = date_parse($row['expected_return']);
  $aMonth = intval($aDate['month'])-1 ;
  $aDate = "'" . $aDate['year'] . "', '" . $aMonth . "', '" . $aDate['day'] . "', '" . $aDate['hour'] . "', '" . $aDate['minute'] . "', '" . $aDate['second'] . "'";

  //Date('2018', '10', '13', '23', '25', '0')
  //Date('2011', '04' - 1, '11', '11', '51', '00')

  //{id: 40, content: "Harshal Gajjar", start: Tue Nov 13 2018 23:25:00 GMT+0530 (IST), end: Thu Nov 15 2018 07:30:00 GMT+0530 (IST), group: 6}oca
  //{id: 2, group: 1, start: Sun Oct 14 2018 02:54:37 GMT+0530 (IST), end: Sun Oct 14 2018 06:54:37 GMT+0530 (IST), type: "background", …}

  $entry = "{id: " . $row['id'] . ", content: '" . $row['quantity'] . " <sup><a href=\'mailto:" . $row['roll_no'] . "@iitdh.ac.in\'>" . $row['roll_no'] . "</a></sup>" . "', start: new Date(" . $dDate . "),end: new Date(" . $aDate . "), group: " . $row['material_id'] . ", roll_no:'" . $row["roll_no"] . "', style: '";
    // if($row['status']=="Approved") $entry = $entry . "background-color: rgba(100,200,100,0.6); border: rgb(0,255,0);";
    // else if($row['status']=="Declined") $entry = $entry . "background-color: rgba(200,100,100,0.5); border: rgb(255,0,0);";
    // else $entry = $entry . "background-color: rgba(0,0,0,0.2); border: #000;";
    $entry = $entry . "', editable: {updateTime: false, remove: false}}";

  echo $entry;

  if($i!=pg_num_rows($request)){ echo ","; }
  $i++;
}
echo "}";

?>
