<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    $redirect=$_GET['sd'];
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'expenses');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='expenses' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
    		//delete query
    		$delete_exp=$link->query("Delete From `gy_expenses` Where `gy_exp_id`='$my_dir_value'");

    		if ($delete_exp) {
    			$my_note_text = "One Expense record has been removed";
            	my_notify($my_note_text,$user_info);
            	header("location: $redirect?note=delete");
    		}else{
    			header("location: $redirect?note=error");
    		}
    	}else{
    		header("location: $redirect?note=pin_out");
    	}
    }
?>