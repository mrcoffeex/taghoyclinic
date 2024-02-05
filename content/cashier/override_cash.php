<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];

    if ($my_dir_value != "") {
        $my_redirect_location = "cd=$my_dir_value&";
    }else{
        $my_redirect_location = "";
    }
    
    //add member
    if (isset($_POST['override'])) {
        //elements
        $my_cash = words($_POST['my_cash']);
        $my_secure_pin = words($_POST['my_secure_pin']);
        $date_now = words(date("Y-m-d H:i:s"));

        $approved_info = by_pin_get_user($my_secure_pin, 'update_cash');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='update_cash' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            $my_dir_value = words($_GET['cd']);
            
            //delete to database
            $insert_data=$link->query("Insert Into `gy_begin_cash`(`gy_beg_date`, `gy_beg_cash`, `gy_beg_by`) Values('$date_now','$my_cash','$user_id')");

            if ($insert_data) {
                $my_note_text = $approved_by." -> approved Beginning Balance Update";
                my_notify($my_note_text,$user_info);
                header("location: cashier?".$my_redirect_location."note=update_cash");
            }else{
                header("location: cashier?".$my_redirect_location."note=error");
            }
        }else{
            header("location: cashier?".$my_redirect_location."note=pin_out");
        }
    }
?>