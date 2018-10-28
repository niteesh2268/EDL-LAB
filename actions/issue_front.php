<?php
// supporting AJAX from issue_front.php

session_start();
if($_SESSION['level']!="staff") die();

 include_once("../connections/connect.php");
 $output = '';

 if(isset($_POST["b"]))
 {
	$search =  $_POST["b"];
	$search = "%".$search."%";
	$sql = <<<EOF
          select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
          from issual as i,material as m,student as s
          where m.id = i.material_id and
          i.student_id = s.id and
          (s.roll_no ::text LIKE '$search' or LOWER(m.name) LIKE LOWER('$search') or LOWER(m.type) LIKE LOWER('$search') or i.quantity ::text LIKE '$search' or LOWER(i.comment) LIKE LOWER('$search'))
          and return_flag = '0' order by i.issual_instance desc;
EOF;
  $query = "select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
  from issual as i,material as m,student as s
  where m.id = i.material_id and
  i.student_id = s.id and
  (s.roll_no ::text LIKE '$search' or LOWER(m.name) LIKE LOWER('$search') or LOWER(m.type) LIKE LOWER('$search') or i.quantity ::text LIKE '$search' or LOWER(i.comment) LIKE LOWER('$search'))
  and return_flag = '1' order by i.actual_return desc;";
  }

  else
{

	$sql =<<<EOF
  select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
  from issual as i,material as m,student as s
  where m.id = i.material_id and
  i.student_id = s.id and return_flag = '0' order by i.issual_instance desc;
EOF;

  $query = "select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
    from issual as i,material as m,student as s
    where m.id = i.material_id and
    i.student_id = s.id and return_flag = '1' order by i.actual_return desc;";
}
 $result = pg_query($db, $sql);
 $output .= '
      <div class="table-responsive">
           <table class="result-table">
           <thead>
                <tr>
                     <th width="10%"> Roll Number </th>
                     <th width="15%">Name</th>
                     <th width="10%">Type</th>
		                 <th width="5%">Quantity</th>
                     <th width="20%">Issual instance</th>
		                 <th width="20%">Expected Return</th>
		                 <th width="15%">Comment</th>
                     <th width="5%"> Return </th>
                </tr>
           </thead><tbody>';
 $rows = pg_num_rows($result);
 if($rows > 0)
 {
	    while($row = pg_fetch_array($result))
      {
          // echo $row["return_flag"];
           $output .= '
                      <tr id = "issual-'.$row["id"].'">
                            <td id="roll_no">'.$row["roll_no"].'</td>
                            <td id="name">'.$row["name"].'</td>
                            <td id="type">'.$row["type"].'</td>
                            <td id="quantity">'.$row["quantity"].'</td>
                            <td id="issual_instance">'.$row["issual_instance"].'</td>
                            <td id="expected_return">'.$row["expected_return"].'</td>
                            <td class="comment" style="width:100%;"><textarea placeholder="-" style="border:none;background:rgba(0,0,0,0);" onchange="edit_issual_data(this.value,' . $row["id"] . ',\'comment\')" type="text">' . $row["comment"] . '</textarea></td>
                            <td><button onclick="return_material('.$row["id"].')" type="button" name="return_btn" id="'.$row["id"].'" class="btn_return btn btn-xs">return</button></td>
                    </tr>';
      }
      $output.='</tbody>';
 }
 else
 {
      $output .= '
				<thead><tr>
          <td id="roll_no"></td>
					<td id="name"></td>
					<td id="type"></td>
					<td id="quantity"></td>
          <td id="issual_instance"></td>
          <td id="expected_return"></td>
					<td id="comment"></td>
          <td id="button"></td>
			   </tr></thead><tbody>';
 }

 $output .= '</table>';

 $output .= '<br /> <br />
 <table class="result-table">
 <thead><tr>
 <th width="10%">Roll Number </th>
 <th width="10%">Name</th>
 <th width="10%">Type</th>
 <th width="5%">Quantity</th>
 <th width="20%">Issual instance</th>
 <th width="10%"> Actual Return </th>
 <th width="20%">Expected Return</th>
 <th width="15%">Comment</th>
 </tr></thead><tbody>';

 $result1 = pg_query($db, $query);
 $rows1 = pg_num_rows($result1);
 if($rows1 > 0)
 {
   while($row1 = pg_fetch_array($result1))
   {
       // echo $row["return_flag"];
       $output .= '
       <tr id = '.$row1["id"].'>
       <td id="roll_no">'.$row1["roll_no"].'</td>
       <td id="name">'.$row1["name"].'</td>
       <td id="type">'.$row1["type"].'</td>
       <td id="quantity">'.$row1["quantity"].'</td>
       <td id="issual_instance">'.$row1["issual_instance"].'</td>
       <td id="actual_return">'.$row1["actual_return"].'</td>
       <td id="expected_return">'.$row1["expected_return"].'</td>
       <td id="comment">'.$row1["comment"].'</td>
       </tr>';
   }
   $output.='</tbody>';
 }
 else
 {
   $output .= '
   <thead><tr>
   <td id="roll_no"></td>
   <td id="name"></td>
   <td id="type"></td>
   <td id="quantity"></td>
   <td id="issual_instance"></td>
   <td id="actual_return"></td>
   <td id="expected_return"></td>
   <td id="comment"></td>
   </tr></thead>';
 }


 $output .= '</table>
              <br /> <br/>
      </div>';
 echo $output;
 ?>
