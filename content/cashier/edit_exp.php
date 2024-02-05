<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['update_exp'])) {
        //elements
        $my_dir_value=words($_GET['cd']);
        //get the values
        $get_exp=$link->query("Select * From `gy_expenses` where `gy_exp_id`='$my_dir_value'");
        $exp_row=$get_exp->fetch_array();

        $old_amount = $exp_row['gy_exp_amount'];
        $time_only = date("H:i:s", strtotime($exp_row['gy_exp_date']));

        $my_date = words($_POST['my_date']." ".$time_only);
        $my_note = words($_POST['my_note']);
        $my_amount = words($_POST['my_amount']);
        $my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'expenses');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];
        
        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='expenses' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            //insert to database
            $update_data=$link->query("Update `gy_expenses` SET `gy_exp_date`='$my_date',`gy_exp_note`='$my_note',`gy_exp_amount`='$my_amount',`gy_approved_by`='$approved_info' Where `gy_exp_id`='$my_dir_value'");

            if ($update_data) {    
                $my_note_text = "Expenses Info is Updated from ".number_format($old_amount,2)." -> ".number_format($my_amount,2);
                my_notify($my_note_text,$user_info);
                header("location: expenses?note=nice_update");
                        
            }else{
                header("location: expenses?note=error");
            }
        }else{
            header("location: expenses?note=pin_out");
        }
    }
?>