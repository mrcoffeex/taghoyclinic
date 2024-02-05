<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=@$_GET['cd'];

    if (isset($_POST['my_acct_code'])) {
    	$my_acct_code = words($_POST['my_acct_code']);

    	//check if the account oe is existing
    	$check_account_code=$link->query("Select * From `gy_user` Where `gy_user_code`='$my_acct_code'");
    	$check_acct_row=$check_account_code->fetch_array();
    	$count_res=$check_account_code->num_rows;

    	if ($count_res == 0) {
    		header("location: replace_counter?note=no_code");
    	}else{
    		//udpate gy_prepared_by in transaction
    		$my_salesman_id = $check_acct_row['gy_user_id'];
    		$update_data=$link->query("Update `gy_transaction` SET `gy_prepared_by`='$my_salesman_id' Where `gy_trans_code`='$my_dir_value'"); 

    		if ($update_data) {
    			header("location: replace_counter?cd=$my_dir_value&note=acct_added");
    		}else{
    			header("location: replace_counter?note=error");
    		}
    	}
    }
?>