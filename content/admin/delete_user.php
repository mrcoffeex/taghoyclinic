<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'users');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='users' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
    		//delete query
    		$delete_user=$link->query("Delete From `gy_user` Where `gy_user_id`='$my_dir_value'");

    		if ($delete_user) {
    			$my_note_text = "One User has been removed from users";
            	my_notify($my_note_text,$user_info);
            	header("location: users?note=delete");
    		}else{
    			header("location: users?note=error");
    		}
    	}else{
    		header("location: users?note=pin_out");
    	}
    }
?>