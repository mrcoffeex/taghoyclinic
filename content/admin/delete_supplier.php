<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];

    $get_supp=$link->query("Select * From `gy_supplier` Where `gy_supplier_id`='$my_dir_value'");
    $supp_row=$get_supp->fetch_array();
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'delete_supplier');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='delete_supplier' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
    		//delete query
    		$delete_supp=$link->query("Delete From `gy_supplier` Where `gy_supplier_id`='$my_dir_value'");

    		if ($delete_supp) {
    			$my_note_text = $supp_row['gy_supplier_name']." was removed from suppliers";
            	my_notify($my_note_text,$user_info);
            	header("location: suppliers?note=delete");
    		}else{
    			header("location: suppliers?note=error");
    		}
    	}else{
    		header("location: suppliers?note=pin_out");
    	}
    }
?>