<?php
 
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
 
	$my_trans_id=$_GET['trans_id'];

	if ($my_trans_id == "") {
		echo "";
	}else{
		$res=$link->query("SELECT * FROM `gy_tra` WHERE `gy_trans_id`='$my_trans_id'");
	 	$count=$res->num_rows;

		if(!$res){
			echo mysqli_error($link);
		}else if ($count == 0) {
			echo "";
		}else{
			$row=$res->fetch_array();
			echo ($row['gy_trans_total'] + $row['gy_trans_interest']) - $row['gy_trans_cash'];
			
		}
	}
 
?>
