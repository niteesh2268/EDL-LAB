<?php
// (c) Harshal Gajjar (gotoharshal@gmail.com)
// This code is available under GNU General Public Licence v3

session_start(); //starting session
date_default_timezone_set('Asia/Kolkata'); //changing default time zone

if($_SESSION['level']=="staff"){ //isset($_SESSION['login']) AND $_SESSION['login']=="success" AND $_SESSION['level']=='gsha'){ //checking for login status
include_once "connections/connect.php"; //connecting to database
$formcounter=0;
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Team - EDL Lab</title>
  <meta type="robots" content="nofollow">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="This is an example of a meta description. This will often show up in search results.">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<!-- <link rel="stylesheet" href="bootstrap.min.css"> -->

	<script src="jquery.min.js"></script>
	<script src="bootstrap.min.js"></script>
	<script src="jquery.table2excel.js"></script>
	<link rel="stylesheet" href="style/style.css">
	<link rel="stylesheet" href="style/style_team.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript" scr="gchart.js"></script>
</head>
<body style="padding-top:80px;">
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
                                    <li><a href="home.php">Home</a></li>
                                    <li><a href="actions/issue.php">Issue Component</a></li>
                                    <li><a href="team.php">Team</a></li>
                            <?php } ?>
                                   <li><a href="logout.php">Log out</a></li>
                           </ul>
                   </div>
           </div>
   </nav>
   </header>

	<!-- CONTENT INSIDE -->

    <div class="container-fluid" id="team_container_fluid">
      <div class="container" id="team">
        <h3>Team</h3>
      <div class="row">
        <div class="col-sm-6 bottom-gap">
          <i><h4>Staff</h4></i>
          <?php
          $sql = "select * from staff order by id";
          $request = pg_query($db, $sql);
          while($row = pg_fetch_array($request)){
            $formcounter++;
            echo "<div class='team_member'>";
            echo "<form name='form-" . $formcounter . "' action='actions/edit_staff.php' method='post' onsubmit='return checkform(" . $formcounter . ",this.submited)'>";
            echo "<input hidden name='id' type='number' value='" . $row['id'] . "'/>" .
            "<span class='input-label'>Name</span><input name='name' type='text' value='" . $row['name'] . "'/><br />" .
            "<!--<span class='input-label'>Designation</span><input name='designation' type='text' value='" . $row['designation'] . "'/><br />-->" .
            "<span class='input-label'><a href='mailto:" . $row['email'] . "?subject=[EDL Lab] Message'>Email</a></span><input name='email' type='text' value='" . $row['email'] . "'/><br />" .
            "<span class='input-label'><a href='tel:" . $row['phone_no'] . "'>Phone</a></span><input name='phone' type='text' value='" . $row['phone_no'] . "'/><br />";
            echo "<input type='submit' onclick='this.form.submited=this.name;' name='update' value='>'/>";
            echo "<input type='submit' onclick='this.form.submited=this.name;' name='remove' value='x'/>";
            echo "</form>";
            echo "</div>";
          }
          ?>
          <div class='team_member' id='new_staff_form'>
          <form name="form-<?php $formcounter++; echo $formcounter; ?>" action='actions/edit_staff.php' method='post' onsubmit='return checkform(<?php echo $formcounter; ?>)'>
            <span class='input-label'>Name</span><input name='name' type='text' placeholder='John Appleseed'/><br />
            <!-- <span class='input-label'>Designation</span><input name='designation' type='text' placeholder='jts'/><br /> -->
            <span class='input-label'>Email</span><input name='email' type='text' placeholder='john@iitdh.ac.in'/><br />
            <span class='input-label'>Phone</span><input name='phone' type='text' placeholder='9876543210'/><br />
            <input type='submit' name='add' value='+' />
            <span id="staff-add-msg">
              <?php
              if(isset($_SESSION['edit_staff_message'])) {echo $_SESSION['edit_staff_message']; unset($_SESSION['edit_staff_message']);}
              ?>
            </span>
          </form>
          </div>
        </div>

        <div class="col-sm-6 bottom-gap">
          <i><h4>Faculty</h4></i>
          <?php
          $sql = "select * from faculty order by id;";
          $request = pg_query($db, $sql);
          while($row = pg_fetch_array($request)){
            $formcounter++;
            echo "<div class='team_member'>";
            echo "<form name='form-" . $formcounter . "' action='actions/edit_faculty.php' method='post' onsubmit='return checkform(" . $formcounter . ",this.submited)'>";
            echo "<input hidden name='id' type='number' value='" . $row['id'] . "'/>" .
            "<span class='input-label'>Name</span><input name='name' type='text' value='" . $row['name'] . "'/><br />" .
            "<span class='input-label'>Department</span><input name='department' type='text' value='" . $row['dept'] . "' readonly /><br />" .
            "<span class='input-label'><a href='mailto:" . $row['email'] . "?subject=[EDL Lab] Message'>Email</a></span><input name='email' type='text' value='" . $row['email'] . "'/><br />" .
            "<span class='input-label'><a href='tel:" . $row['phone_no'] . "'>Phone</a></span><input name='phone' type='text' value='" . $row['phone_no'] . "'/><br />";
            echo "<input type='submit' onclick='this.form.submited=this.name;' name='update' value='>'/>";
            echo "<input type='submit' onclick='this.form.submited=this.name;' name='remove' value='x'/>";
            echo "</form>";
            echo "</div>";
          }
          ?>
          <div class='team_member' id='new_faculty_form'>
          <form name='form-<?php $formcounter++; echo $formcounter; ?>' action='actions/edit_faculty.php' method='post' onsubmit='return checkform(<?php echo $formcounter; ?>)'>
            <span class='input-label'>Name</span><input name='name' type='text' placeholder='John Appleseed'/><br />
            <span class='input-label'>Department</span>
            <input type="radio" name="department" value="electrical"> EE
              <input type="radio" name="department" value="computerscience"> CSE
              <input type="radio" name="department" value="mechanical"> ME
            <br />
            <span class='input-label'>Email</span><input name='email' type='text' placeholder='john@iitdh.ac.in'/><br />
            <span class='input-label'>Phone</span><input name='phone' type='text' placeholder='9876543210'/><br />
            <input type='submit' name='add' value='+' />
            <span id="faculty-add-msg">
              <?php
              if(isset($_SESSION['edit_faculty_message'])) {echo $_SESSION['edit_faculty_message']; unset($_SESSION['edit_faculty_message']);}
              ?>
            </span>
          </form>
        </div>

      </div>
      </div>
    </div>
  </div>

    <script>
    function checkform(number, submit){
      var form = "form-".concat(number);

      if(submit=="remove"){
        return true;
      }

      // var x = document.forms[form]["remove"].value;
      // if(typeof x != "undefined")
      // window.alert(x);

      var x = document.forms[form]["email"].value;

      if(x.includes("@iitdh.ac.in")) return true;
      else{
        window.alert("Please enter a valid @iitdh.ac.in email address");
        return false;
      }
    }
    </script>


<?php
} else{
	//user not logged in
		header('Location:admin.php');
?>

Authentication failed

<?php
}
?>

<div id="stats">
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-87834345-3', 'auto');
    ga('send', 'pageview');
  </script>
</div>
</body>
</html>
