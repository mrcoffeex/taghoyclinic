<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['my_date'])) {
        //elements
        $my_dir_value=words($_GET['cd']);
        $redirect=words($_GET['sd']);
        //get the values
        $get_exp=$link->query("Select * From `gy_expenses` where `gy_exp_id`='$my_dir_value'");
        $exp_row=$get_exp->fetch_array();

        $old_amount = $exp_row['gy_exp_amount'];
        $time_only = date("H:i:s", strtotime($exp_row['gy_exp_date']));

        $my_date = words($_POST['my_date']." ".$time_only);
        $my_note = words($_POST['my_note']);
        $my_amount = words($_POST['my_amount']);

        //insert to database
        $update_data=$link->query("Update `gy_expenses` SET `gy_exp_date`='$my_date',`gy_exp_note`='$my_note',`gy_exp_amount`='$my_amount',`gy_approved_by`='$user_id' Where `gy_exp_id`='$my_dir_value'");

        if ($update_data) {    
            $my_note_text = "Expenses Info is Updated from ".number_format($old_amount,2)." -> ".number_format($my_amount,2);
            my_notify($my_note_text,$user_info);
            header("location: $redirect?note=nice_update");
                    
        }else{
            header("location: $redirect?note=error");
        }
    }
?>