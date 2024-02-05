<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    require __DIR__ . '\printer\autoload.php';
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
    
    //add member
    if (isset($_POST['submit_exp'])) {
        //elements
        $my_date = words($_POST['my_date']);
        $my_note = words($_POST['my_note']);
        $my_amount = words($_POST['my_amount']);
        $my_secure_pin = words($_POST['my_secure_pin']);

        $final_date = words(date($my_date." H:i:s"));

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
            $insert_data=$link->query("Insert Into `gy_expenses`(`gy_exp_date`, `gy_exp_type`, `gy_exp_note`, `gy_exp_amount`, `gy_user_id`, `gy_approved_by`) Values('$final_date','CASH','$my_note','$my_amount','$user_id','$approved_info')");

            if ($insert_data) {
                
                $my_note_text = number_format($my_amount,2)." is added to expenses";
                my_notify($my_note_text,$user_info);
                header("location: expenses?note=nice");
                        
            }else{
                header("location: expenses?note=error");
            }
        }else{
            header("location: expenses?note=pin_out");
        }
    }
?>