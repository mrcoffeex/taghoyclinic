<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['my_secure_pin'])) {

        $my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'void_remittance');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='void_remittance' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            $my_dir_value = words($_GET['cd']);
            
            //delete to database
            $delete_data=$link->query("Delete From `gy_remittance` Where `gy_remit_id`='$my_dir_value'");

            if ($delete_data) {
                $my_note_text = $approved_by." -> approved Void Remittance Record in Cashier";
                my_notify($my_note_text,$user_info);
                header("location: remittance?note=delete");
            }else{
                header("location: remittance?note=error");
            }
        }else{
            header("location: remittance?note=pin_out");
        }
    }
?>