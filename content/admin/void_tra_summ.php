<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];

    $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_dir_value'");
    $accountrow=$getaccount->fetch_array();

    $accname = $accountrow['gy_acc_name'];

    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'void_tra');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='void_tra' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
            
            //remove edata
            $delete_data=$link->query("DELETE FROM `gy_accounts` Where `gy_acc_id`='$my_dir_value'");

            if ($delete_data) {
                $my_note_text = "VOID TRA ACCOUNT approved by ".$approved_by." -> ".$accname;
                my_notify($my_note_text,$user_info);
                header("location: tra_accounts?note=delete");
            }else{
                header("location: tra_accounts?note=error");
            }
    		
    	}else{
    		header("location: tra_accounts?note=pin_out");
    	}
    }
?>