<?php
// (c) Harshal Gajjar (gotoharshal@gmail.com)
// This code is available under GNU General Public Licence v3

session_start();
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER['REQUEST_METHOD']=='GET' AND isset($_GET['id']) AND isset($_GET['flag']) AND isset($_GET['who'])){
		include_once "../connections/connect.php";

		$sql = "SELECT * FROM " . $_GET['who'] . " WHERE id='" . $_GET['id'] . "' AND rset_flag='" . $_GET['flag'] . "'";
		// echo $sql;
		$request = pg_query($pg,$sql);
		$valid = pg_num_rows($request);

		if($valid==0){
			header('Location:../login.php');
		}else{
			$user = pg_fetch_array($request);

			$_SESSION['preset_check']=1;
			$_SESSION['preset_id']=$_GET['id'];
			$_SESSION['preset_flag']=$_GET['flag'];
			$_SESSION['user'] = $user;
      $_SESSION['type'] = isset($_GET['who'];
			// echo $_SESSION['user']['name'];
			header('Location:../reset.php');
		}

} else{
	header('Location:../home.php');
}

?>
