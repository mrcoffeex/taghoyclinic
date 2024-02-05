<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['submit_user_edit'])) {

        $my_dir_value = words($_GET['cd']);

        $my_code = words($_POST['my_code']);
        $my_name = words($_POST['my_name']);
        $my_username = words($_POST['my_username']);
        $my_password1 = words($_POST['my_password1']);
        $my_password2 = words($_POST['my_password2']);

        //check if there's duplicate acct. code
        $check_code=$link->query("Select * From `gy_user` Where `gy_user_code`='$my_code' AND `gy_user_id` != '$my_dir_value'");
        $count_duplicate=$check_code->num_rows;

        if ($my_password1 != $my_password2) {
            header("location: users?note=pin_out");
        }else if ($count_duplicate > 0) {
            header("location: users?note=code_duplicate");
        }else{
            $my_password = encryptIt($my_password2);
            //insert to database
            $update_data=$link->query("Update `gy_user` SET `gy_user_code`='$my_code',`gy_full_name`='$my_name',`gy_username`='$my_username',`gy_password`='$my_password' Where `gy_user_id`='$my_dir_value'");

            if ($update_data) {    
                $my_note_text = $my_name." info info is updated";
                my_notify($my_note_text,$user_info);
                header("location: users?note=nice_update");
                        
            }else{
                header("location: users?note=error");
            }
        }
    }
?>