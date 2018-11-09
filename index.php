<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Login - EDL Lab</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <!-- <script src="jquery-ui-1.10.3/ui/jquery.ui.datepic÷ker.js"></script> -->


        <!-- <script type="text/javascript" src="../resources/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
        <link href="../resources/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

        <script src="../resources/js/vis.js"></script>
        <link href="../resources/css/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->

    <link rel="stylesheet" href="style/style.css" />
    <link rel="stylesheet" href="style/style_login.css" />

</head>
<body>
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
                           </ul>
                   </div>
           </div>
   </nav>
   </header>

<?php

//error_reporting(0);

    $error_login=$error_signup="";
    if(isset($_POST['login_submit'])){ //checking if 'login' button was clicked
        $username=$_POST['username']; //storing entered data
        $password=$_POST['password']; //storing entered data

        if($username=="" || $password==""){
          $error_login="Incorrect credentials";
        }else{

            include_once "./connections/connect.php"; //connecting to mysql database

            // this is for students
            $sql="SELECT * FROM student WHERE roll_no='$username' AND password='$password'"; //sql query
            $request=pg_query($db,$sql); //searching for a user with given credentials in the table 'users'

            if(pg_num_rows($request) > 0){ //if user found

                $row = pg_fetch_array($request);
                //correct credentials
                //student: login, level, roll_no, dept, id, name
                $_SESSION['login'] = "success"; //using session variables to remember that user has logged in
                $_SESSION['level'] = "student";
                $_SESSION['roll_no'] = $username; //storing username for further use (if any)
                $_SESSION['dept'] = $row['dept'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];

                header('Location:home.php'); //redirecting user to admin page
                pg_close($handle); //closing MySQL connection
            } else{
                //wrong credentials
                $error_login="Incorrect credentials"; //storing error in error variable to output on screen
            }

            // this is for staff
            $sql="SELECT * FROM staff WHERE email='$username' AND password='$password'"; //sql query
            $request=pg_query($db,$sql); //searching for a user with given credentials in the table 'users'

            if(pg_num_rows($request) > 0){ //if user found

                $row = pg_fetch_array($request);
                //correct credentials
                //student: login, level, roll_no, dept, id, name
                $_SESSION['login'] = "success"; //using session variables to remember that user has logged in
                $_SESSION['level'] = "staff";
                $_SESSION['email'] = $username; //storing username for further use (if any)
                $_SESSION['designation'] = $row['designation'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];

                header('Location:home.php'); //redirecting user to admin page
                pg_close($handle); //closing MySQL connection
            } else{
                //wrong credentials
                $error_login="Incorrect credentials"; //storing error in error variable to output on screen
            }

            // this is for faculty
            $sql="SELECT * FROM faculty WHERE email='$username' AND password='$password'"; //sql query
            $request=pg_query($db,$sql); //searching for a user with given credentials in the table 'users'

            if(pg_num_rows($request) > 0){ //if user found

                $row = pg_fetch_array($request);
                //correct credentials
                //student: login, level, roll_no, dept, id, name
                $_SESSION['login'] = "success"; //using session variables to remember that user has logged in
                $_SESSION['level'] = "faculty";
                $_SESSION['email'] = $username; //storing username for further use (if any)
                // $_SESSION['designation'] = $row['designation'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];

                header('Location:home.php'); //redirecting user to admin page
                pg_close($handle); //closing MySQL connection
            } else{
                //wrong credentials
                $error_login="Incorrect credentials"; //storing error in error variable to output on screen
            }

          }
    // } elseif(isset($_POST['signup_submit'])){
    //     $username=$_POST['username'];
    //     $password=$_POST['password'];
    //
    //         include_once "./connections/connect.php";
    //
    //         if(!empty($username) && !empty($password)){
    //             $sql="SELECT * FROM users WHERE username='$username'";
    //             $request=pg_query($handle,$sql);
    //
    //             if(pg_num_rows($request) > 0){
    //                 $error_signup="Username already taken";
    //             } else{
    //                 $sql="INSERT INTO users (username,password) VALUES ('$username','$password')";
    //                 if(pg_query($handle,$sql)){
    //                     $error_signup="User created";
    //                 } else{
    //                     $error_signup="User creation failed";
    //                 }
    //             }
    //         } else{
    //             $error_signup="Please enter valid credentials";
    //         }

    }else if(isset($_POST['reset_submit'])){
      $username=str_replace(";","",str_replace("--","",str_replace("#","",$_POST['username']))); //removing sql injection attempt

      if($username==""){
        $error_login = "Invalid username";
      }else{

      include_once "connections/connect.php"; //connecting to database
      if(preg_match("/DROP/i",$username) OR preg_match("/DELETE/i",$username)){ //if DROP or DELETE found in username field, password is anyways hashed
        $error = "Invalid keywords found"; //show error message below login form
      } else{
    		//no drop or delete keyword found in username
    		// if($_SESSION['db_connection_status']==0)
        if(false){
    			//database connection has failed
    			// $error = "Database connection failed";
    		} else{
    			//connection to database successful

          $found = false;

          //for student
          if($found==false){
    			$sql="SELECT * FROM student WHERE roll_no='$username'"; //sql to find user with entered username and password
    			$request=pg_query($db,$sql);

    			if(pg_num_rows($request) == 1){
    				//user found
            $found=true;
            $user = pg_fetch_array($request);

            $reset_flag = md5(rand(10,100));
            $sql = "UPDATE student SET reset_flag='" . $reset_flag . "' WHERE id='" . $user['id'] . "'";
            $request = pg_query($db, $sql);

            require_once "resources/mail/PHPMailerAutoload.php";
            ini_set('include_path', 'resources');
            $mail = new PHPMailer;
            $mail->Host = 'localhost';
            $mail->From = "edllab-noreply@iitdh.ac.in";
            $mail->FromName = "[Sambhal] IITDh";
            $mail->addAddress($user['roll_no']."@iitdh.ac.in",$user['name']); //sending one copy to the hostel secretary
            $mail->isHTML(true);

            $linkdata = array(
              'who' => "student",
              'id' => $user['id'],
              'flag' => $reset_flag
            );
            $link = "http://fromabctill.xyz/iitdh/actions/preset.php?" . http_build_query($linkdata);

            // echo $link;

    				$mail->Subject = "[Sambhal] Reset Password";
            $mail->Body = "Hello " . $user['name'] . ",<br />Password reset link was requested for your <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> account.<br />Click <a href='" . $link . "'>here</a> to reset password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Sambhal";
            $mail->AltBody = "Hello " . $user['name'] . ",<br />Password reset link was requested for your <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> account.<br />Click <a href='" . $link . "'>here</a> to reset password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Sambhal";

            if(!$mail->send())
    				{
    					$error_login = "Mailer Error: " . $mail->ErrorInfo;
    				}
    				else
    				{
    					$error_login = "Please check your email account for password reset link";
    				}
    				pg_close($db); //closing sql connection
    			}else{
    				//user with entered username and password not found
    				$error_login = "Please check your email account for password reset link"; //showing error below login form
    				//echo $sql; //for debugging
    			}
        }

          if($found==false){
          //for faculty
    			$sql="SELECT * FROM faculty WHERE email='$username'"; //sql to find user with entered username and password
    			$request=pg_query($db,$sql);

    			if(pg_num_rows($request) == 1){
    				//user found
            $found=true;
            $user = pg_fetch_array($request);

            $reset_flag = md5(rand(10,100));
            $sql = "UPDATE faculty SET reset_flag='" . $reset_flag . "' WHERE id='" . $user['id'] . "'";
            $request = pg_query($db, $sql);

            require_once "resources/mail/PHPMailerAutoload.php";
            ini_set('include_path', 'resources');
            $mail = new PHPMailer;
            $mail->Host = 'localhost';
            $mail->From = "edllab-noreply@iitdh.ac.in";
            $mail->FromName = "[Sambhal] IITDh";
            $mail->addAddress($user['email'],$user['name']); //sending one copy to the hostel secretary
            $mail->isHTML(true);

            $linkdata = array(
              'who' => "faculty",
              'id' => $user['id'],
              'flag' => $reset_flag
            );
            $link = "http://fromabctill.xyz/iitdh/actions/preset.php?" . http_build_query($linkdata);

            // echo $link;

    				$mail->Subject = "[Sambhal] Reset Password";
            $mail->Body = "Hello " . $user['name'] . ",<br />Password reset link was requested for your <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> account.<br />Click <a href='" . $link . "'>here</a> to reset password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Sambhal";
            $mail->AltBody = "Hello " . $user['name'] . ",<br />Password reset link was requested for your <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> account.<br />Click <a href='" . $link . "'>here</a> to reset password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Sambhal";

            if(!$mail->send())
    				{
    					$error_login = "Mailer Error: " . $mail->ErrorInfo;
    				}
    				else
    				{
    					$error_login = "Please check your email account for password reset link";
    				}
    				pg_close($db); //closing sql connection
    			}else{
    				//user with entered username and password not found
    				$error_login = "Please check your email account for password reset link"; //showing error below login form
    				//echo $sql; //for debugging
    			}
        }

          if($found==false){
          //for staff
    			$sql="SELECT * FROM staff WHERE email='$username'"; //sql to find user with entered username and password
          // echo $sql;
          $request=pg_query($db,$sql);

    			if(pg_num_rows($request) == 1){
    				//user found
            $found=true;
            $user = pg_fetch_array($request);

            $reset_flag = md5(rand(10,100));
            $sql = "UPDATE staff SET reset_flag='" . $reset_flag . "' WHERE id='" . $user['id'] . "'";
            $request = pg_query($db, $sql);

            require_once "resources/mail/PHPMailerAutoload.php";
            ini_set('include_path', 'resources');
            $mail = new PHPMailer;
            $mail->Host = 'localhost';
            $mail->From = "edllab-noreply@iitdh.ac.in";
            $mail->FromName = "[Sambhal] IITDh";
            $mail->addAddress($user['email'],$user['name']); //sending one copy to the hostel secretary
            $mail->isHTML(true);

            $linkdata = array(
              'who' => "staff",
              'id' => $user['id'],
              'flag' => $reset_flag
            );
            $link = "http://fromabctill.xyz/iitdh/actions/preset.php?" . http_build_query($linkdata);

            // echo $link;

    				$mail->Subject = "[Sambhal] Reset Password";
            $mail->Body = "Hello " . $user['name'] . ",<br />Password reset link was requested for your <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> account.<br />Click <a href='" . $link . "'>here</a> to reset password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Sambhal";
            $mail->AltBody = "Hello " . $user['name'] . ",<br />Password reset link was requested for your <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> account.<br />Click <a href='" . $link . "'>here</a> to reset password.<br />It is safe to ignore this mail if you didn't request password reset.<br /><br />Sambhal";

            if(!$mail->send())
    				{
    					$error_login = "Mailer Error: " . $mail->ErrorInfo;
    				}
    				else
    				{
    					$error_login = "Please check your email account for password reset link";
    				}
    				pg_close($db); //closing sql connection
    			}else{
    				//user with entered username and password not found
    				$error_login = "Please check your email account for password reset link"; //showing error below login form
    				//echo $sql; //for debugging
    			}

    		}
        }
    	}
    }
    }

?>


    <!-- <div id="login-form">
        Log in
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

            Username:<br /><input type="text" name="username"/><br />
            Password:<br /><input type="password" name="password"/><br />
            <input type="submit" name="login_submit" value="Login"/><br />
            <?php echo $error_login; // showing error (if any)?>

        </form>
    </div> -->

    <div class="container" id="login_container">
    	<div class="well" id="login_well">
        <!-- <h3>Sambhal IITDh</h3> -->
    		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    			Username<br /><input type="text" name="username"><br />
    			Password<br /><input type="password" name="password"><br /><br/>
    			<input type="submit" name="login_submit" value="Login"/>
          <input id="forgot-password" type="submit" name="reset_submit" value="Forgot Password?"><br />
    		</form>
    		<span id="login-error"><?php echo $error_login; // showing error (if any)?></span>
    	</div>
    </div>

    <!-- <div id="signup-form">
        Sign up
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

            Username:<br /><input type="text" name="username"/><br />
            Password:<br /><input type="password" name="password"/><br />
            <input type="submit" name="signup_submit" value="Signup"/><br />
            <?php echo $error_signup; // showing error (if any)?>

        </form>
    </div> -->



</body>
</html>
