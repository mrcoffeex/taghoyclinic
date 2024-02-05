<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['my_secure_pin'])) {

        $my_dir_value = @$_GET['cd'];

        $over_all = words($_POST['over_all']);
        $over_all_reading = words($_POST['over_all_reading']);
        $my_over_all_status = words($_POST['my_over_all_status']);
        $my_secure_pin = words($_POST['my_secure_pin']);

        //get status value
        if ($my_over_all_status == "PERFECT") {
            $my_status_value = 0;
        }else if ($my_over_all_status == "OVER") {
            $my_status_value = $over_all_reading - $over_all;
        }else if ($my_over_all_status == "SHORT") {
            $my_status_value = $over_all - $over_all_reading;
        }else{
            $my_status_value = 0;
        }

        $a_a = words($_POST['a_a']);
        $a_b = words($_POST['a_b']);
        $a_c = words($_POST['a_c']);
        $a_d = words($_POST['a_d']);
        $a_e = words($_POST['a_e']);
        $a_f = words($_POST['a_f']);
        $a_g = words($_POST['a_g']);
        $a_h = words($_POST['a_h']);
        $a_i = words($_POST['a_i']);

        $date_now = words($my_dir_value." ".date("H:i:s"));
        $date = words($my_dir_value);

        $approved_info = by_pin_get_user($my_secure_pin, 'cash_breakdown');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='cash_breakdown' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            $my_dir_value = words($_GET['cd']);

            //check if there is existing
            $check_exist=$link->query("Select * From `gy_breakdown` Where date(`gy_break_date`)='$date' AND `gy_user_id`='$user_id'");
            $count_exist=$check_exist->num_rows;
            $exist_row=$check_exist->fetch_array();

            //my exist id
            $my_break_id = words($exist_row['gy_break_id']);

            if ($count_exist > 0) {
                $update_data=$link->query("Update `gy_breakdown` SET `gy_break_date`='$date_now', `gy_break_a_a`='$a_a', `gy_break_a_b`='$a_b', `gy_break_a_c`='$a_c', `gy_break_a_d`='$a_d', `gy_break_a_e`='$a_e', `gy_break_a_f`='$a_f', `gy_break_a_g`='$a_g', `gy_break_a_h`='$a_h', `gy_break_a_i`='$a_i' Where `gy_break_id`='$my_break_id'");
            }else{
                //insert new data
                $update_data=$link->query("Insert Into `gy_breakdown`(`gy_break_date`, `gy_user_id`, `gy_break_a_a`, `gy_break_a_b`, `gy_break_a_c`, `gy_break_a_d`, `gy_break_a_e`, `gy_break_a_f`, `gy_break_a_g`, `gy_break_a_h`, `gy_break_a_i`) Values('$date_now','$user_id','$a_a','$a_b','$a_c','$a_d','$a_e','$a_f','$a_g','$a_h','$a_i')");
            }

            if ($update_data) {
                $my_note_text = $approved_by." -> approved Over-All Cash BreakDown with an amount of -> ".number_format($over_all_reading,2);
                my_notify($my_note_text,$user_info);
                header("location: custom_breakdown?search_text=$my_dir_value&note=nice_update_all");
            }else{
                header("location: custom_breakdown?search_text=$my_dir_value&note=error");
            }
        }else{
            header("location: custom_breakdown?search_text=$my_dir_value&note=pin_out");
        }
    }
?>