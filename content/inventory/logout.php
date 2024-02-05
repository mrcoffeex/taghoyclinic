<?php 

	include("../../conf/conn.php");
	include("../../conf/function.php");
	include("session.php");

	$activity_name = words("Logout Notification by ".$user_info);
    $activity_date = words(date("Y-m-d H:i:s"));

	if ($user_id == 1) {
		//empty
	}else{
		$insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) values('$activity_name','$activity_date')");
	}

	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Logout</title>
	<meta http-equiv="refresh" content="1;url=../../index">
</head>
<body>
	<h3>Preparing to Logout ... Loading</h3>
</body>
</html>