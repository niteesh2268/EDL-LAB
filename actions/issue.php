<?php
// Clicking issue button on the page index.php brings the user here

session_start();
if($_SESSION['level']!="staff") header("Location: ../index.php");

include_once("../connections/connect.php");

 ?>
<html>
    <head>
        <title>Issue - EDL Lab</title>
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <!-- <script src="jquery-ui-1.10.3/ui/jquery.ui.datepic÷ker.js"></script> -->


            <script type="text/javascript" src="../resources/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
            <link href="../resources/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

            <script src="../resources/js/vis.js"></script>
            <link href="../resources/css/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <link rel="stylesheet" href="../style/style.css" />
        <link rel="stylesheet" href="../style/style_issue.css" />

    </head>
    <body style="padding-top:70px;">
      <header>
       <nav class="navbar navbar-default navbar-fixed-top">
               <div class="container">
                       <div class="navbar-header">
                               <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainbar">
                                       <span class="icon-bar"></span>
                                       <span class="icon-bar"></span>
                                       <span class="icon-bar"></span>
                               </button>
                               <a class="navbar-brand logo" href="#">Sambhal</a>
                       </div>

                       <div class="collapse navbar-collapse" id="mainbar">
                               <ul class="nav navbar-nav navbar-right">
                                 <?php if($_SESSION['level']=="staff"){?>
                                        <li><a href="../home.php">Home</a></li>
                                        <li><a href="../actions/issue.php">Issue Component</a></li>
                                        <li><a href="../team.php">Team</a></li>
                                <?php } ?>
                                       <li><a href="../logout.php">Log out</a></li>
                               </ul>
                       </div>
               </div>
       </nav>
       </header>

      <div class="container">
        <div class="row">
          <div class="col-sm-3">
            <div class="well">
              <h3>Issue</h3>
            <form name="new-issual-form" method="POST" action="" onsubmit="return new_issual();">

              <span class="form-label">Material</span>
              <select id="material-selector" onchange="material_info()" name="component">
                <option value="-1">Select</option>
              <?php

              $sql = "select * from material;";
              $request = pg_query($db, $sql);

              while($row = pg_fetch_array($request)){
                echo "<option value=" . $row['id'];

                if(isset($_GET['request']) AND $_GET['request']=="issue" AND $row['id']==$_GET['id']){
                  echo " selected=\"selected\" ";
                }

                echo ">" . $row['name'] . "</option>";
              }

                //select sum(quantity) from issual where material_id='3';
                //select * from material where material_id='3'
              ?>
              </select>
              <br /><br />
              <!-- type cost comment -->

              <span class="form-label">Type</span><span id="newtype" class="material_info"></span>
              <span class="form-label">Cost</span><span id="newcost" class="material_info"></span>
              <span class="form-label">Available Quantity</span><span id="newavailable" class="material_info"></span>

              <?php
                // echo "<span class=\"form-label\">Type</span><input id = \"newtype\" type=\"text\" name=\"type\" value='";
                // if(isset($_GET['type'])) echo $_GET['type'];
                // echo "' class=\"new-issue-input\" readonly> </input>";
                // echo "<span class=\"form-label\">Cost</span><input id = \"newcost\" type=\"text\" name=\"cost\" value='";
                // if(isset($_GET['cost'])) echo $_GET['cost'];
                // echo "' class=\"new-issue-input\" readonly> </input>";
                // echo "<span class=\"form-label\">Available Quantity</span><input id = \"newavailable\" type=\"text\" name=\"available\" value='";
                //
                // if(isset($_GET['available'])) echo $_GET['available'];
                // echo "' class=\"new-issue-input\" readonly> </input>";
              ?>
              <span class="form-label">Quantity</span><input type="number"  min="1" name="quantity" value="1" class="new-issue-input"/>
              <span class="form-label">Roll Number</span><input type="number" name="roll_no" class="new-issue-input"/>
              <!-- <span class="form-label">Expected Return</span><input type="date" id = "return_date" name="expected_return" class="new-issue-input"/> -->

              <span class="form-label">Expected Return</span>
              <div class="input-group date form_datetime" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy HH:ii p" data-link-field="dtp_input1">
                <input id="return_date" name="expected_return" class="form-control new-issue-input" type="text" value="" readonly>
                <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span> -->
                <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span> -->
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
              </div>

              <br />
              <input class="submit-button" type="submit" name="issue" value="Submit" />

            </form>

              <!-- <button onclick="new_issual()"> test</button> -->

            </div>
          </div>
          <div class="col-sm-9">
            <div class="well">
            <h2>Overview</h2>

            <div class="row">
              <div class="col-sm-12">
                <div id="component-wise"></div>

              <script type="text/javascript">
              google.charts.load('current', {'packages':['corechart']});
              google.charts.setOnLoadCallback(drawChart);
              function drawChart() {
                var data = google.visualization.arrayToDataTable([
                <?php
                $sql_2="select * from material order by type, name;"; //preparing SQL command to get all issue types // type = 'component'
                $request_2=pg_query($db,$sql_2);
                $ntypes=pg_num_rows($request_2);
                echo "['Name','Issued','Left'],";

                $sql_4="select * from material order by type, name;"; // type = 'component'
                $request_4=pg_query($db,$sql_4);
                $ntypes_4=pg_num_rows($request_4);

                while($row_4 = pg_fetch_array($request_4)){

                  echo "['" . $row_4['name'] . "',";

                    $sql_5="select sum(quantity) from issual where material_id=" . $row_4['id'] . " and return_flag='f';";
                    //echo $sql_5;
                    $request_5=pg_query($db,$sql_5);
                    $ntypes_5=pg_fetch_array($request_5);

                    if(is_null($ntypes_5['sum'])){
                      echo "0,";
                    }else
                    echo $ntypes_5['sum'] . ",";

                    $sql_6="select quantity from material where id=" . $row_4['id'] . ";";
                    //echo $sql_6;
                    $request_6=pg_query($db,$sql_6);
                    $ntypes_6=pg_fetch_array($request_6);
                    echo $ntypes_6['quantity']-$ntypes_5['sum'];

                  echo "]";
                  if($ntypes_4>1) echo ",";
                  $ntypes_4--;

                }

                ?>

                ]);

                var options = {
                  legend: { position: 'top', maxLines: 10 },
                  bar: { groupWidth: '75%' },
                  isStacked: 'percent',
                  hAxis: {
                    minValue: 0,
                    ticks: [0, .3, .6, .9, 1]
                  },
                  colors: ['rgb(100,100,200)','rgb(175,175,200)'],
                  backgroundColor: { fill:'transparent' }
                };

                var chart = new google.visualization.BarChart(document.getElementById('component-wise'));

                chart.draw(data, options);
              }
              </script>

            </div>
            </div>
            <div class="row">
            <div class="col-sm-6">
              <div id="components"></div>

              <script type="text/javascript">
              google.charts.load('current', {'packages':['corechart']});
              google.charts.setOnLoadCallback(drawChart);
              function drawChart() {
                var data = google.visualization.arrayToDataTable([
                <?php
                $sql_2="select * from material where type = 'component' order by name;"; //preparing SQL command to get all issue types
                $request_2=pg_query($db,$sql_2);
                $ntypes=pg_num_rows($request_2);
                echo "['Name','Quantity'],";

                $sql_4="select * from material where type = 'component' order by name;";
                $request_4=pg_query($db,$sql_4);
                $ntypes_4=pg_num_rows($request_4);

                while($row_4 = pg_fetch_array($request_4)){

                  echo "['" . $row_4['name'] . "'," . $row_4['quantity'];
                  echo "]";
                  if($ntypes_4>1) echo ",";
                  $ntypes_4--;

                }

                ?>

                ]);

                var options = {
                  title: 'Components',
                  colors: ['rgb(100,100,200)','rgb(150,150,200)','rgb(175,175,200)'],
                  backgroundColor: { fill:'transparent' }
                };

                var chart = new google.visualization.PieChart(document.getElementById('components'));

                chart.draw(data, options);
              }
               </script>

            </div>
            <div class="col-sm-6">
              <div id="equipments"></div>

              <script type="text/javascript">
              google.charts.load('current', {'packages':['corechart']});
              google.charts.setOnLoadCallback(drawChart);
              function drawChart() {
                var data = google.visualization.arrayToDataTable([
                <?php
                $sql_2="select * from material where type = 'equipment' order by name;"; //preparing SQL command to get all issue types
                $request_2=pg_query($db,$sql_2);
                $ntypes=pg_num_rows($request_2);
                echo "['Name','Quantity'],";

                $sql_4="select * from material where type = 'equipment' order by name;";
                $request_4=pg_query($db,$sql_4);
                $ntypes_4=pg_num_rows($request_4);

                while($row_4 = pg_fetch_array($request_4)){

                  echo "['" . $row_4['name'] . "'," . $row_4['quantity'];
                  echo "]";
                  if($ntypes_4>1) echo ",";
                  $ntypes_4--;

                }

                ?>

                ]);

                var options = {
                  title: 'Equipments',
                  colors: ['rgb(100,100,200)','rgb(150,150,200)','rgb(175,175,200)'],
                  backgroundColor: { fill:'transparent' }
                };

                var chart = new google.visualization.PieChart(document.getElementById('equipments'));

                chart.draw(data, options);
              }
               </script>

            </div>

          </div><br />

            <div id="visualization">
            </div><br />

            </div>
            <h3>Details</h3>
            <div class="table-responsive">
      					<input type="text" name="search_text" id="search_text" placeholder="Search" class="form-control" />
      					<br>
      				<div id="result"></div>
      				<div id="live_data"></div>
      			</div>


          </div>
        </div>
      </div>

    </body>
</html>

<script>

// $('#return_date').datepicker();

function new_issual(){

  var quantity =  document.forms['new-issual-form'].elements['quantity'].value; //quantity
  var roll_no =  document.forms['new-issual-form'].elements['roll_no'].value; //student_id
  var staff_id = <?php echo $_SESSION["id"]; ?>;//staff_id
  var expected_return =  document.forms['new-issual-form'].elements['expected_return'].value;//expected_return
  var material_id =  document.forms['new-issual-form'].elements['component'].value;//material_id

  if(parseInt(quantity)>parseInt(document.getElementById('newavailable').innerHTML)){
    window.alert("quantity>available");
    return false;
  }

  if(document.getElementById('return_date').value == null || document.getElementById('return_date').value == ""){
    window.alert("select expected return date and time");
    return false;
  }

  $.ajax({
    url:"new_issual.php",
    method:"post",
    data:{"quantity":quantity, "roll_no":roll_no, "staff_id":staff_id, "expected_return":expected_return, "material_id":material_id},
    success:function(data)
    {
      console.log(data);
      if(data == 'success')
      {

        location.reload();

        var search = $("#search_text").val();
        if(search != '')
        {
          load_data(search);
        }
        else
        {
          load_data();
        }

        material_info();

      }else{
        console.log(data);
        window.alert("Issue failure");
      }
    }
  });


  return false;
}

var returning; //for removing the returning component from timeline;
function removeTimelineItem(id){
  items.remove(id);
  console.log(items);
  timeline.setItems(items);
  returning=-1;
}

function return_material(id){
  var return_id = "issual-"+id;
  returning = id;

  $.ajax({
    url:"return_component.php",
    method:"post",
    data:{'id':id},
    success:function(data)
    {
      if(data == 'success')
      {
        location.reload();
        removeTimelineItem(returning);
        // removeTimelineItem(id);

        var id = "#"+return_id;

        // $(id).remove();

        var search = $("#search_text").val();
        if(search != '')
        {
          load_data(search);
        }
        else
        {
          load_data();
        }
      }else{
        window.alert("Return failure");
      }
    }
  });

}

function edit_issual_data(value, id, change){
  console.log(id+ " " +change+" val="+value);

  $.ajax({
      url:"./edit_issual.php",
      method:"POST",
      data:{id:id, text:value, column_name:change},
      dataType:"text",
      success:function(data){
          // alert(data);
	$('#result').html("<div class='alert alert-success'>"+data+"</div>");
      }
  });
}
function material_info(){

  console.log("material_info called");

  var material_id = document.getElementById('material-selector').value;

  if(material_id==-1){
    $('#newtype').html("-");
    $('#newcost').html("-");
    $('#newavailable').html("-");
    return;
  }

  $.ajax({
    url:"material_info.php",
    method:"post",
    data:{material_id: material_id},
    success:function(data)
    {
      // console.log(data);
      var obj = JSON.parse(data);
      // console.log(obj.type);
      // console.log(obj.cost);
      $('#newtype').html(obj.type);
      $('#newcost').html(obj.cost);
      $('#newavailable').html(obj.available);
    }
  });
}

//for VIS timeline
var items;
var groups;

var container = document.getElementById('visualization');
var d = new Date();
// d.setDate(d.getDate() - 1);

var options;
var timeline;
// /for VIS timeline
load_timeline();

function load_timeline(){
  items = new vis.DataSet([
    <?php
    include_once("../connections/connect.php");

    $i=1;
    // select  from issual, student, material where issual.material_id = material.id and issual.student_id = student.id;

    $sql = "select issual.id as id, issual.quantity as quantity, student.roll_no as roll_no, material.id as material_id, issual_instance, expected_return, actual_return from issual, student, material where issual.material_id = material.id and issual.student_id = student.id and issual.return_flag='f'" ;// . " and status='Approval Pending'";
    $request = pg_query($db, $sql);
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
    ?>

  ]);

  groups = new vis.DataSet([
    <?php
    include_once("../connections/connect.php");

    $i=1;
    $sql = "select distinct material.id as material_id, material.name as name from issual, material where issual.material_id = material.id and issual.return_flag='f' order by material.name;";// . " and status='Approval Pending'";
    $request=pg_query($db, $sql);
    while($row = pg_fetch_array($request)){
      echo "{id: " . $row['material_id'] . ", content: '" . $row['name'] . "'}";
      if($i!=pg_num_rows($request)){ echo ","; }
      $i++;
    }
    ?>

  ]);

  options = {
    start: d,
    editable: false,
    // onRemove: removing,
    margin: {
      item: 10
    },
    zoomMin: 1000 * 60 * 60 * 24,
    zoomMax: 1000 * 60 * 60 * 24 * 31 * 6
    // onRemove: function (item, callback) {
    //   if(confirm("Confirm Delete?"))
    //   {
    //     if (ok) {
    //       callback(item); // confirm deletion
    //     }
    //     else {
    //       callback(null); // cancel deletion
    //     }
    //   });
    // }
  };

  timeline = new vis.Timeline(container, items, groups, options);
}

function load_data(search)
{

  $.ajax({
    url:"issue_front.php",
    method:"post",
    data:{b:search},

    success:function(data)
    {
      // console.log(data);
      $('#result').html("");
      $('#live_data').html(data);
    }
  });
}

$('.form_datetime').datetimepicker({
  //language:  'fr',
  startDate: new Date(),
  weekStart: 1,
  todayBtn:  1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 2,
  forceParse: 0,
  showMeridian: 1
});
$('.form_date').datetimepicker({
  //language:  'fr',
  weekStart: 1,
  todayBtn:  1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 2,
  minView: 2,
  forceParse: 0
});
$('.form_time').datetimepicker({
  //language:  'fr',
  weekStart: 1,
  todayBtn:  1,
  autoclose: 1,
  todayHighlight: 1,
  startView: 1,
  minView: 0,
  maxView: 1,
  forceParse: 0
});


$(document).ready(
function()
{

  material_info();
	load_data();
  material_info();

	$('#search_text').keyup(function(){
		var search = $(this).val();
		if(search != '')
		{
			load_data(search);
		}
		else
		{
			load_data();
		}
	});

});

</script>
