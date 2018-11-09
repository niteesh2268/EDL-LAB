<?php
	// Supporting AJAX for live editing of information from home.php

	session_start();
  include_once("../connections/connect.php");

	if($_SESSION['level']=="faculty")
  {
    $sql = "select distinct r.id as id,s.roll_no as roll_no,r.name as name,r.type as type,r.quantity as quantity,r.cost as cost,r.cause as reason from student as s, request as r where r.student_id = s.id and r.status = 'Approval Pending' and r.faculty_id=" . $_SESSION['id'] ." order by id;";
    $result = pg_query($db, $sql);
    $rows = pg_num_rows($result);
    $output = '';
    $output .= '<head><h4>Approvals Pending</h4></head><br>';
    if($rows > 0)
    {
      $output .= "<table style='width:100%;' class=\"result-table\" id=\"request-pending-table\">
      <thead>
      <tr>
      <th rowspan=2>S.no.</th>
      <th rowspan=2>Roll Number</th>
      <th rowspan=2>Name</th>
      <th rowspan=2>Type</th>
      <th rowspan=2>Cost (per unit)</th>
      <th rowspan=2>Quantity</th>
      <th rowspan=2>Reason</th>
      <th style=\"text-align:center\" colspan=2 rowspan=2>Options</th>
      </tr>
      </thead>
      <tbody>";
      $i=0;
      while($row  = pg_fetch_array($result))
      {
        $i++;
        $output .= "<tr class=\"material-data\">
        <td>".$i."</td>
        <td class=\"roll_no\" >".$row["roll_no"]."</td>
        <td class=\"name\" >".$row["name"]."</td>
        <td class=\"type\">".$row["type"]."</td>
        <td class=\"cost\">".$row["cost"]."</td>
        <td class=\"quantity\">".$row["quantity"]."</td>
        <td class=\"reason\">".$row["reason"]."</td>
        <td><button type=\"button\" name=\"approve_btn\" data-id1=\"".$row["id"]."\" class=\"btn btn-xs btn_approve\">approve</button></td>
        <td><button type=\"button\" name=\"decline_btn\"  data-id2=\"".$row["id"]."\" class=\"btn btn-xs btn-danger btn_decline\">Decline</button></td>
        </tr>
          ";
      }

      $output .= "</tbody></table>";
    }
    else
    {
      $output .= "<span class=\"no_results\"><h4> No Requests </h4> </span>";
    }


    $output .= "<br />";
     // echo $output;

    $query = "select distinct r.id as id,s.roll_no as roll_no,r.status as status,r.name as name,r.type as type,r.quantity as quantity,r.cost as cost,r.cause as reason from student as s, request as r where r.student_id = s.id and r.status not in ('Approval Pending') and r.faculty_id=" . $_SESSION['id'] . " order by id;";
    $result = pg_query($db, $query);
    $rows = pg_num_rows($result);
    $output .= '<head><h4>Approvals Handled</h4></head><br>';
    if($rows > 0)
    {
      $output .= "<table style='width:100%;' class=\"result-table\" id=\"request-table\">
      <thead>
      <tr>
      <th rowspan=2>S.no.</th>
      <th rowspan=2>Roll Number</th>
      <th rowspan=2>Name</th>
      <th rowspan=2>Type</th>
      <th rowspan=2>Cost (per unit)</th>
      <th rowspan=2>Quantity</th>
      <th rowspan=2>Reason</th>
      <th rowspan=2>Status</th>
      </tr>
      </thead>
      <tbody>";
      $i=0;
      while($row  = pg_fetch_array($result))
      {
        $i++;
        $output.= "<tr class=\"material-data\">
        <td>".$i."</td>
        <td class=\"roll_no\" >".$row["roll_no"]."</td>
        <td class=\"name\" >".$row["name"]."</td>
        <td class=\"type\">".$row["type"]."</td>
        <td class=\"cost\">".$row["cost"]."</td>
        <td class=\"quantity\">".$row["quantity"]."</td>
        <td class=\"reason\">".$row["reason"]."</td>
        <td class=\"status\">".$row["status"]."</td>
        </tr>
          ";
      }
      $output .= "</tbody></table>";
       // echo $output;
    }
    else
    {
      $output .= "<span class=\"no_results\"><h4> No Approvals/Declines </h4> </span>";
    }

    echo $output;

  }


  if($_SESSION['level']=="staff") {

    $sql = "select distinct f.name as faculty,f.id as faculty_id,r.id as id,r.name as name,r.type as type,r.quantity as quantity,r.cost as cost from request as r,faculty as f where r.status = 'Approved' and f.id = r.faculty_id order by id;";
    $result = pg_query($db, $sql);
    $rows = pg_num_rows($result);
      $vomit = '';
    $vomit .= '<h4>Purchase Pending</h4><br>';

    if($rows > 0)
    {
      $vomit.= "<table class=\"result-table\" id=\"order-table\" style='width:100%;'>
      <thead>
      <tr>
      <th rowspan=2>S.no.</th>
      <th rowspan=2>Faculty</th>
      <th rowspan=2>Name</th>
      <th rowspan=2>Type</th>
      <th rowspan=2>Cost (per unit)</th>
      <th rowspan=2>Quantity</th>
      <th style=\"text-align:center\" colspan=2 rowspan=2>Options</th>
      </tr>
      </thead>
      <tbody>";
      $i=0;
      while($row  = pg_fetch_array($result))
      {
        $i++;
        $vomit.="<tr class=\"material-data\">
        <td>".$i."</td>
        <td class=\"faculty_name\" >".$row["faculty"]."</td>
        <td class=\"name\" >".$row["name"]."</td>
        <td class=\"type\">".$row["type"]."</td>
				<td class=\"cost\"><input type='text' value='" . $row["cost"] . "' onchange='updateOrder(" . $row['id'] . ",this.value,\"cost\")'></td>
        <td class=\"quantity\"><input type='text' value='" . $row["quantity"] . "' onchange='updateOrder(" . $row['id'] . ",this.value,\"quantity\")'></td>
        <td><button type=\"button\" name=\"purchase_btn\" data-id3=\"".$row["id"]."\" class=\"btn_purchase btn btn-xs\">purchase</button></td>
        </tr>
        ";
      }
      $vomit.= "</tbody></table>";
    }
    else
    {
        $vomit.= "<span class=\"no_results\"><h5> No Orders </h5> </span>";
    }

		echo $vomit . "<br /><br />";

		$sql = "select distinct f.name as faculty,f.id as faculty_id,r.id as id,r.name as name,r.type as type,r.quantity as quantity,r.cost as cost from request as r,faculty as f where r.status = 'Purchased' and f.id = r.faculty_id order by id;";
		$result = pg_query($db, $sql);
		$rows = pg_num_rows($result);
			$vomit = '';
		$vomit .= '<h4>Purchased</h4><br>';

		if($rows > 0)
		{
			$vomit.= "<table class=\"result-table\" id=\"order-table\" style='width:100%;'>
			<thead>
			<tr>
			<th rowspan=2>S.no.</th>
			<th rowspan=2>Faculty</th>
			<th rowspan=2>Name</th>
			<th rowspan=2>Type</th>
			<th rowspan=2>Cost (per unit)</th>
			<th rowspan=2>Quantity</th>
			<!--<th style=\"text-align:center\" colspan=2 rowspan=2>Options</th>-->
			</tr>
			</thead>
			<tbody>";
			$i=0;
			while($row  = pg_fetch_array($result))
			{
				$i++;
				$vomit.="<tr class=\"material-data\">
				<td>".$i."</td>
				<td class=\"faculty_name\" >".$row["faculty"]."</td>
				<td class=\"name\" >".$row["name"]."</td>
				<td class=\"type\">".$row["type"]."</td>
				<td class=\"cost\">".$row["cost"]."</td>
				<td class=\"quantity\">".$row["quantity"]."</td>
				</tr>
				";
			}
			$vomit.= "</tbody></table>";
		}
		else
		{
				$vomit.= "<span class=\"no_results\"><h5> No Orders </h5> </span>";
		}


    echo $vomit;
  }




 ?>
