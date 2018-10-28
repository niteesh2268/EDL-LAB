<?php
// (c) Harshal Gajjar (gotoharshal@gmail.com)
// This code is available under GNU General Public Licence v3

session_start(); //starting session to retrieve session variables
date_default_timezone_set('Asia/Kolkata'); //setting default time zone

if($_SESSION['level']=="staff"){ //isset($_SESSION['login']) AND $_SESSION['login']=="success" AND $_SESSION['level']=="gsha"){ //checking for login status

	if($_SERVER['REQUEST_METHOD']=='POST'){

		include_once "../connections/connect.php";

		if(isset($_POST['update'])){
			$sql = "UPDATE staff SET name='" . $_POST['name'] . "', phone_no='" . $_POST['phone'] . "', email='" . $_POST['email'] . "' WHERE id='" . $_POST['id'] . "'";
			$request = pg_query($db, $sql);
			header('Location:../team.php');
		} else if(isset($_POST['remove'])){
			$sql = "DELETE FROM staff WHERE id='" . $_POST['id'] . "'";
			$request = pg_query($db, $sql);
			header('Location:../team.php');
		}else if(isset($_POST['add'])){
			$sql = "SELECT * FROM staff WHERE name='" . $_POST['name'] . "' and phone_no='" . $_POST['phone'] . "' and email='" . $_POST['email'] . "'";
			$request = pg_query($db, $sql);
			if(pg_num_rows($request)>0){
				$_SESSION['edit_staff_message'] = "Staff account exists!";
			}else{
				$rset_flag = md5(rand(10,100));
				// echo $rset_flag;
				$sql = "INSERT INTO staff (name, email, phone_no, designation, reset_flag, verified) VALUES ('" . $_POST['name'] . "','" . $_POST['email'] . "','" . $_POST['phone'] . "','jts', '$rset_flag', 'false')" ;
				$request = pg_query($db, $sql);
				$_SESSION['edit_staff_message'] = "Staff added.";

				$sql = "SELECT * FROM staff WHERE name='" . $_POST['name'] . "' and email='" . $_POST['email'] . "' and phone_no='" . $_POST['phone'] . "' and designation='jts'";
				$request = pg_query($db, $sql);
				$newstaff = pg_fetch_array($request);

				require_once "../resources/mail/PHPMailerAutoload.php";
				ini_set('include_path', 'resources');
				$mail = new PHPMailer;
				$mail->Host = 'localhost';
				$mail->From = "edllab-noreply@iitdh.ac.in";
				$mail->FromName = "[Sambhal] IITDh";
				$mail->addAddress($newstaff['email'], $newstaff['name']);
				// $mail->addCC("gsha@iitdh.ac.in"); //sending one copy to the hostel secretary
				$mail->isHTML(true);

				$linkdata = array(
					'who' => 'staff',
			    'id' => $newstaff['id'],
			    'flag' => $rset_flag
				);

				$link = "http://fromabctill.xyz/iitdh/actions/preset.php?" . http_build_query($linkdata);

				// echo $link;

				$mail->Subject = "[Sambhal] Welcome to IITDH Sambhal!";
				$mail->Body = "Hello " . $newstaff['name'] . ",<br /> <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> is a CMS for labs.<br /><br />An account for you has been created by " . $_SESSION['name'] . ", following are the details:<br />username: '" . $newstaff['email'] . "'<br />Password: Click <a href='" . $link . "'>here</a> to set a password.<br /><br />Sambhal";
				$mail->AltBody = "Hello " . $newstaff['name'] . ",<br /> <a href='http://fromabctill.xyz/sambhal/'>Sambhal</a> is a CMS for labs.<br /><br />An account for you has been created by " . $_SESSION['name'] . ", following are the details:<br />username: '" . $newstaff['email'] . "'<br />Password: Click <a href='" . $link . "'>here</a> to set a password.<br /><br />Sambhal";

				if(!$mail->send())
				{
					echo "Mailer Error: " . $mail->ErrorInfo;
				}
				else
				{
					echo "Message has been sent successfully";
				}

			}

			header('Location:../team.php');
		} else{
			header('Location:../home.php');
		}
	}else{
		header('Location:../home.php');
	}

} else{ //if user is not logged in
	header('Location:../home.php');
}

?>
