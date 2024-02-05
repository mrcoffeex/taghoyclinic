<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['submit_user'])) {
        //elements
        $my_code = words($_POST['my_code']);
        $my_name = words($_POST['my_name']);
        $my_role = words($_POST['my_role']);
        $my_username = words($_POST['my_username']);
        $my_password1 = words($_POST['my_password1']);
        $my_password2 = words($_POST['my_password2']);
        $my_branch = words($_POST['my_branch']);

        //check if there's duplicate acct. code
        $check_code=$link->query("Select * From `gy_user` Where `gy_user_code`='$my_code'");
        $count_duplicate=$check_code->num_rows;

        if ($my_password1 != $my_password2) {
            header("location: users?note=pin_out");
        }else  if ($count_duplicate > 0) {
            header("location: users?note=code_duplicate");
        }else{
            $my_password = encryptIt($my_password2);
            //insert to database
            $insert_data=$link->query("Insert Into `gy_user`(`gy_user_code`, `gy_full_name`, `gy_username`, `gy_password`, `gy_user_type`, `gy_branch_id`) Values('$my_code','$my_name','$my_username','$my_password','$my_role','$my_branch')");

            if ($insert_data) {    
                $my_note_text = $my_acc_name." is added to system users";
                my_notify($my_note_text,$user_info);
                header("location: users?note=nice");
                        
            }else{
                header("location: users?note=error");
            }
        }

        
    }
?>