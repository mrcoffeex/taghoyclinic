<?php
 
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
 
	$my_account_code=$_GET['my_account_code'];

	if ($my_account_code == "") {
		echo "";
	}else{
		$res=$link->query("SELECT * FROM `gy_accounts` WHERE `gy_acc_code`='$my_account_code' LIMIT 1");
	 	$count=$res->num_rows;
	 	$row=$res->fetch_array();

		if(!$res){
			echo mysqli_error($db);
		}else if ($count == 0) {
			echo "";
		}else{
			echo $row['gy_acc_name'];
		}
	}	
 
?>
