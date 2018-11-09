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
	$sql = "select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
          from issual as i,material as m,student as s
          where m.id = i.material_id and
          i.student_id = s.id and
          (s.roll_no ::text LIKE '$search' or LOWER(m.name) LIKE LOWER('$search') or LOWER(m.type) LIKE LOWER('$search') or i.quantity ::text LIKE '$search' or LOWER(i.comment) LIKE LOWER('$search'))
          and return_flag = '0' order by i.issual_instance desc;";

  $query = "select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
  from issual as i,material as m,student as s
  where m.id = i.material_id and
  i.student_id = s.id and
  (s.roll_no ::text LIKE '$search' or LOWER(m.name) LIKE LOWER('$search') or LOWER(m.type) LIKE LOWER('$search') or i.quantity ::text LIKE '$search' or LOWER(i.comment) LIKE LOWER('$search'))
  and return_flag = '1' order by i.actual_return desc;";

  $sql2="select distinct student.roll_no, material.name, issual.quantity, issual.issual_instance, issual.comment from issual, material, student where issual.material_id = material.id and issual.student_id = student.id and material.type='consumable' and (  LOWER(material.name) LIKE LOWER('$search') or issual.quantity ::text LIKE '$search' or LOWER(issual.comment) LIKE LOWER('$search') or
  student.roll_no ::text LIKE '$search') order by issual.issual_instance desc;";

  }

  else
{

	$sql = "select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
  from issual as i,material as m,student as s
  where m.id = i.material_id and
  i.student_id = s.id and return_flag = '0' and m.type!='consumable' order by i.issual_instance desc;";

  $query = "select distinct s.roll_no,i.id,m.name,m.type,i.quantity,i.issual_instance,i.actual_return,i.expected_return,i.comment
    from issual as i,material as m,student as s
    where m.id = i.material_id and
    i.student_id = s.id and return_flag = '1' order by i.actual_return desc;";

  $sql2="select distinct student.roll_no, material.name, issual.quantity, issual.issual_instance, issual.comment from issual, material, student where issual.material_id = material.id and issual.student_id = student.id and material.type='consumable';";

}
 $result = pg_query($db, $sql);
 $output .= '
      <div class="table-responsive">
        <h4>Issued</h4>
           <table class="result-table" style="width: 100%;">
           <thead>
                <tr>
                     <th>Roll Number </th>
                     <th>Name</th>
                     <th width="10%">Type</th>
		                 <th width="5%">Quantity</th>
                     <th width="20%">Issual instance</th>
		                 <th width="20%">Expected Return</th>
		                 <th width="20%">Comment</th>
                     <th width="5%">Return </th>
                </tr>
           </thead><tbody>';
 $rows = pg_num_rows($result);

 if($rows > 0)
 {
	    while($row = pg_fetch_array($result))
      {
          // echo $row["return_flag"];
           $output .= '
                      <tr id = "issual-'.$row["id"].'" ';

                      $dayStringSub = substr($row["expected_return"], 0, 10);

                      // $isToday = ( strtotime('now') >= strtotime($dayStringSub . " 00:00")
                      // && strtotime('now') <  strtotime($dayStringSub . " 23:59") );

                      $isPast = ( strtotime(date("Y-m-d")) > strtotime($dayStringSub . " 00:00") );
                      $isToday =  ( strtotime(date("Y-m-d")) == strtotime($dayStringSub . " 00:00") );

                      // echo strtotime(date("Y-m-d"))." ".strtotime($dayStringSub . " 00:00")."<br />";

                      if($isToday) $output.="class='return-pending-today'";
                      else if($isPast) $output.="class='return-pending-past'";
                      else $output.="class='return-pending-future'";

                    $output.=' ><td id="roll_no">'.$row["roll_no"].'</td>
                            <td id="name">'.$row["name"].'</td>
                            <td id="type">'.$row["type"].'</td>
                            <td id="quantity">'.$row["quantity"].'</td>
                            <td id="issual_instance" ';
            $output .= ' >'.$row["issual_instance"].'</td>
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

 $output .= '</table><br />';

 $output .= "return <span id='today'>today</span><span id='past'>past</span><br />";

 $output .= '<br /> <br />
 <h4>Returned</h4>
 <table class="result-table">
 <thead><tr>
 <th width="10%">Roll Number </th>
 <th width="10%">Name</th>
 <th width="10%">Type</th>
 <th width="5%">Quantity</th>
 <th width="20%">Issual instance</th>
 <th width="10%">Actual Return </th>
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

 $output .= '</table>';

 $output .= '<br /> <br />
 <h4>Consumed</h4>
 <table class="result-table">
 <thead><tr>
 <th width="10%">Roll Number </th>
 <th width="10%">Name</th>
 <th width="5%">Quantity</th>
 <th width="20%">Issual instance</th>
 <th width="15%">Comment</th>
 </tr></thead><tbody>';


 $result1 = pg_query($db, $sql2);
 $rows1 = pg_num_rows($result1);
 if($rows1 > 0)
 {
   while($row1 = pg_fetch_array($result1))
   {
       // echo $row["return_flag"];
       $output .= '
       <td id="roll_no">'.$row1["roll_no"].'</td>
       <td id="name">'.$row1["name"].'</td>
       <td id="quantity">'.$row1["quantity"].'</td>
       <td id="issual_instance">'.$row1["issual_instance"].'</td>
       <td id="comment">'.$row1["comment"].'</td>
       </tr>';
   }
   $output.='</tbody>';
 }
 else
 {
   $output .= '<tr><td></td><td></td><td></td><td></td><td></td></tr>';
   // $output .= '
   // <thead><tr>
   // <th width="10%">Roll Number </th>
   // <th width="10%">Name</th>
   // <th width="5%">Quantity</th>
   // <th width="20%">Issual instance</th>
   // <th width="15%">Comment</th>
   // </tr></thead>';
 }


 $output .= '</table>
              <br /> <br/>
      </div>';
 echo $output;
 ?>
