<?php
//supporting AJAX from index.php

  session_start();
  include_once("../connections/connect.php");

  if($_SESSION['login']!="success") die();

 $output = '';
 if(isset($_POST["query"]))

{
	$search =  $_POST["query"];
  $searchOptions = json_decode($_POST['selectedMaterials']);

  // print_r($searchOptions);
  // echo $searchOptions[0];

  $search = "%".$search."%";
  $sql = " select  distinct m.id,m.type,m.name,m.quantity,m.cost,m.comment,(m.quantity - (select coalesce(sum(quantity),0) as available from issual where material_id = m.id and actual_return = '0001-01-01 00:00:00')) as available from material as m where m.type in (";
  for($i=0; $i<sizeof($searchOptions); $i++){
    $sql = $sql . "'" . $searchOptions[$i] . "'";
    if($i!=sizeof($searchOptions)-1) $sql = $sql . ",";
  }
  $sql = $sql . ") and (LOWER(m.name) like LOWER('" . $search . "') or LOWER(m.comment) like LOWER('" . $search . "'))";
  // echo $sql;

}
else
{
	$sql =" select  distinct m.id,m.type,m.name,m.quantity,m.cost,m.comment,(m.quantity - (select coalesce(sum(quantity),0) as available from issual where material_id = m.id and actual_return = '0001-01-01 00:00:00')) as available from material as m,issual as i order by id;
";
}
 $result = pg_query($db, $sql);

 $output .= '
      <div>
        <table class="result-table" id="result-table">
        <thead>
        <tr>
        <th rowspan=2>Id</th>
        <th rowspan=2>Name</th>
		    <th rowspan=2>Type</th>
        <th rowspan=2>Cost</th>
		    <th colspan=2>Quantity</th>
		    <th rowspan=2>Comment</th>';

         if($_SESSION['level']=="staff")
         $output.= '<th style="text-align:center" colspan=2 rowspan=2>Options</th>';

         $output.='</tr>';
         $output.='<tr>';
         $output.='<td>Available</td><td>Total</td>';

         $output.='</tr></thead><tbody>';

 $rows = pg_num_rows($result);
 if($rows > 0)
 {
	  if($rows > 10)
	  {
		  $delete_records = $rows - 10;
		  $delete_sql = "DELETE FROM material LIMIT $delete_records";
		  pg_query($db, $delete_sql);
	  }
      while($row = pg_fetch_array($result))
      {

        $output .= '<tr class="material-data">';

        $output .= '<td>'.$row["id"].'</td>';

        $output .= '<td class="name" ><input onchange="edit_data(this.value,' . $row["id"] . ', \'name\')" id="" type="text" value="' . $row["name"] . '"'; if($_SESSION['level']!="staff") $output .= " readonly "; $output.= '/></td>';

        $output .= '<td class="type"><input onchange="edit_data(this.value,' . $row["id"] . ',\'type\')" type="text" value="' . $row["type"] . '"'; if($_SESSION['level']!="staff") $output .= " readonly ";  $output.= '/></td>';
        $output .= '<td class="cost"><input onchange="edit_data(this.value,' . $row["id"] . ',\'cost\')" type="text" value="' . $row["cost"] . '"'; if($_SESSION['level']!="staff") $output .= " readonly ";  $output.= '/></td>';
        $output .= '<td class="available">'. $row["available"];   $output.= '</td>';
        $output .= '<td class="quantity"><input onchange="edit_data(this.value,' . $row["id"] . ',\'quantity\')" type="text" value="' . $row["quantity"] . '"'; if($_SESSION['level']!="staff") $output .= " readonly ";  $output.= '/></td>';


        if($_SESSION['level']=="staff"){
          $link = "issue.php";

          $linkdata = array(
              'request' => "issue",
              'id' => $row["id"]
          );

          $link = "./actions/" . $link . "?" . http_build_query($linkdata);

          $output .= '<td class="comment" style="width:100%;"><textarea onchange="edit_data(this.value,' . $row["id"] . ',\'comment\')" type="text"'; if($_SESSION['level']!="staff") $output .= " readonly "; $output .= '> ' . $row["comment"] . '</textarea></td>';
          $output .= '<td><button onclick="window.location.assign(\'' . $link . '\')" type="button" name="issue_btn" data-id7="'.$row["id"].'" class="btn_issue btn btn-xs">issue</button></td>';
          $output .= '<td><button type="button" name="delete_btn" data-id6="'.$row["id"].'" class="btn btn-xs btn-danger btn_delete">Delete</button></td>';
        }else{
          $output .= '<td class="comment" style="width:100%;"><textarea placeholder="-" style="border:none;background:rgba(0,0,0,0);" onchange="edit_data(this.value,' . $row["id"] . ',\'comment\')" type="text"'; if($_SESSION['level']!="staff") $output .= " readonly "; $output .= '>' . $row["comment"] . '</textarea></td>';
        }

        $output .= '</tr>';
      }
 }


  if($_SESSION['level']=="staff"){
   $output .= '
        <tr>
             <td></td>
             <td id="name" ><input class="new-material" type="text" id="new-name" placeholder="Name" /></td>
 <td id="type" ><input class="new-material" type="text" id="new-type" placeholder="Type" /></td>
             <td id="cost" ><input class="new-material" type="text" id="new-cost" placeholder="Cost (per unit)" /></td> <td></td>
 <td id="quantity" ><input class="new-material" type="text" id="new-quantity" placeholder="Total Quantity" /></td>
 <td id="comment" ><textarea type="text" id="new-comment" ></textarea></td>
             <td colspan=2><button type="button" name="btn_add" id="btn_add" class="btn btn-xs btn-success">+</button></td>

        </tr>
   ';
 };

 if($_SESSION['level']!="staff" && $_SESSION['level']!="faculty"){
  $output .= '
       <tr>
            <td>Request Material</td>
            <td id="name" ><input class="new-material" type="text" id="new-name" placeholder="Name" /></td>
<td id="type" >
<input class="new-material" type="text" id="new-type" placeholder="component/equipment"/>
</td>
            <td id="cost" ><input class="new-material" type="text" id="new-cost" placeholder="Cost (per unit)" /></td> <td></td>
<td id="quantity" ><input class="new-material" type="text" id="new-quantity" placeholder="Total Quantity" /></td>
<td id="comment" >
  <textarea type="text" id="new-cause" placeholder="Reason"></textarea>
  <input class="new-material" type="text" id="new-faculty-ref" placeholder="Faculty email" />
</td>
            <td colspan=2><button type="button" name="btn_request" id="btn_request" class="btn btn-xs btn-success">+</button></td>
       </tr>
  ';
};

 $output .= '</tbody></table>
      </div>';
 echo $output;
 ?>
