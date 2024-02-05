<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];

    $getuserdata=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_dir_value'");
    $userdatarow=$getuserdata->fetch_array();

    if ($userdatarow['gy_user_status'] == '0') {
        $myupdatequery = "Update `gy_user` SET `gy_user_status`='1' Where `gy_user_id`='$my_dir_value'";
        $note_type = "user_moved";
        $redirectpage = "users";
    }else{
        $myupdatequery = "Update `gy_user` SET `gy_user_status`='0' Where `gy_user_id`='$my_dir_value'";
        $note_type = "user_recover";
        $redirectpage = "user_archieve";
    }
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'users');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='users' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
    		//update user visibility
    		$update_data=$link->query($myupdatequery);

    		if ($update_data) {
            	header("location: $redirectpage?note=$note_type");
    		}else{
    			header("location: $redirectpage?note=error");
    		}
    	}else{
    		header("location: $redirectpage?note=pin_out");
    	}
    }
?>