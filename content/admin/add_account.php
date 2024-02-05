<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['submit_acc'])) {
        //elements
        $my_acc_name = words($_POST['my_acc_name']);
        $my_acc_address = words($_POST['my_acc_address']);
        $my_acc_contact = words($_POST['my_acc_contact']);
        $date_now = date("Y-m-d H:i:s");

        //get code
        $get_code=$link->query("SElect * From `gy_accounts` Order By `gy_acc_code` DESC LIMIT 1");
        $code_row=$get_code->fetch_array();

        if ($code_row['gy_acc_code'] == 0) {
            $my_code = "101";
        }else{
            $my_code = $code_row['gy_acc_code'] + 1;
        }

        //insert to database
        $insert_data=$link->query("Insert Into `gy_accounts`(`gy_acc_code`, `gy_acc_name`, `gy_acc_address`, `gy_acc_contact`, `gy_acc_reg`) Values('$my_code','$my_acc_name','$my_acc_address','$my_acc_contact','$date_now')");

        if ($insert_data) {    
            $my_note_text = $my_acc_name." is added to accounts";
            my_notify($my_note_text,$user_info);
            header("location: tra_counter?note=add_account");
                    
        }else{
            header("location: tra_counter?note=error");
        }
    }
?>