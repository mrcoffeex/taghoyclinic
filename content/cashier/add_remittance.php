<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['my_secure_pin'])) {

        $my_type = words($_POST['my_type']);
        $my_amount = words($_POST['my_amount']);
        $my_secure_pin = words($_POST['my_secure_pin']);
        $date_now = words(date("Y-m-d H:i:s"));

        $approved_info = by_pin_get_user($my_secure_pin, 'remittance');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

        //remit type
        if ($my_type == 0) {
            $my_remit_type = "PARTIAL REMITTANCE";
        }else{
            $my_remit_type = "FULL REMITTANCE";
        }

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='remittance' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            $my_dir_value = words($_GET['cd']);
            
            //insert to database
            $insert_data=$link->query("Insert Into `gy_remittance`(`gy_remit_date`, `gy_remit_type`, `gy_user_id`, `gy_approved_by`, `gy_remit_value`) Values('$date_now','$my_type','$user_id','$approved_info','$my_amount')");

            if ($insert_data) {
                $my_note_text = $approved_by." -> approved an amount of ".number_format($my_amount,2)." as ".$my_remit_type;
                my_notify($my_note_text,$user_info);
                header("location: remittance?note=nice");
            }else{
                header("location: remittance?note=error");
            }
        }else{
            header("location: remittance?note=pin_out");
        }
    }
?>