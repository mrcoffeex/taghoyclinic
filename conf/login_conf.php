<?php  
	session_start();

	include 'conn.php';
	include 'function.php';

	if(isset($_POST['login'])){
		$username = words($_POST['username']);
		$password = encryptIt(words($_POST['password']));

		$identify=$link->query("Select * From `gy_user` Where `gy_username`='$username' AND `gy_password`='$password'");
		$count=$identify->num_rows;
		$row=$identify->fetch_array();

		if($count > 0){
			if($row['gy_user_type'] == "0"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d H:i:s"));

	            if ($row['gy_user_id'] == 1) {
	            	//empty
	            }else{
	            	$insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) values('$activity_name','$activity_date')");
	            }
	            
				header("location: ../content/admin/");
			}else if($row['gy_user_type'] == "1"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d H:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) values('$activity_name','$activity_date')");
				header("location: ../content/inventory/");
			}else if($row['gy_user_type'] == "2"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d H:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) values('$activity_name','$activity_date')");
				header("location: ../content/cashier/");
			}else if($row['gy_user_type'] == "3"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d H:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) values('$activity_name','$activity_date')");
				header("location: ../content/moderator/");
			}else if($row['gy_user_type'] == "4"){
				$_SESSION['fus_user_id'] = $row['gy_user_id'];
				$_SESSION['fus_user_type'] = $row['gy_user_type'];
				$activity_name = words("Login Notification by ".$row['gy_full_name']);
	            $activity_date = words(date("Y-m-d H:i:s"));
	            $insert_activity_log=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) values('$activity_name','$activity_date')");
				header("location: ../content/preview/");
			}else{
				session_destroy();
				echo "
					<script>
						window.alert('Access Failed!');
						window.location.href = '../index'
					</script>
				";
			}
		}else{
			session_destroy();
			echo "
				<script>
					window.alert('Access Failed!');
					window.location.href = '../index'
				</script>
			";
		}
	}
?>