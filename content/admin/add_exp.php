<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    sleep(3);

    if (isset($_POST['my_date'])) {
        //elements
        $my_date = words($_POST['my_date']);
        $my_note = words($_POST['my_note']);
        $my_amount = words($_POST['my_amount']);

        $final_date = words(date($my_date." H:i:s"));

        //insert to database
        $insert_data=$link->query("Insert Into `gy_expenses`(`gy_exp_date`, `gy_exp_type`, `gy_exp_note`, `gy_exp_amount`, `gy_user_id`, `gy_approved_by`) Values('$final_date','OTHER','$my_note','$my_amount','$user_id','$user_id')");

        if ($insert_data) {    
            $my_note_text = number_format($my_amount,2)." is added to expenses as other expenses";
            my_notify($my_note_text,$user_info);
            header("location: expenses?note=nice");
                    
        }else{
            header("location: expenses?note=error");
        }
    }
?>