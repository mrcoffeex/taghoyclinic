<?php
 
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
 
	$my_cust_name=$_GET['my_cust_name'];

	if ($my_cust_name == "") {
		echo "";
	}else{
		$res=$link->query("SELECT * FROM `gy_accounts` WHERE `gy_acc_name` LIKE '%$my_cust_name%' Order By `gy_acc_name` ASC LIMIT 30");
	 	$count=$res->num_rows;

		if(!$res){
			echo mysqli_error($db);
		}else if ($count == 0) {
			echo "<option>item not found ...</option>";
		}else{
			while($row=$res->fetch_array()){
				echo "<option value='".$row['gy_acc_name']."'>";
			}
		}
	}	
 
?>
