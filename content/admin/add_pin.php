<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['submit_pin'])) {
        //elements
        $my_user = words($_POST['my_user']);
        $my_pin_type = words($_POST['my_pin_type']);
        $my_password1 = words($_POST['my_password1']);
        $my_password2 = words($_POST['my_password2']);

        //check if there's duplicate pin in the specific command
        $check_code=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='$my_pin_type' AND `gy_user_id`='$my_user'");
        $count_duplicate=$check_code->num_rows;

        if ($my_password1 != $my_password2) {
            header("location: pins?note=pin_out");
        }else  if ($count_duplicate > 0) {
            header("location: pins?note=code_duplicate");
        }else{
            $my_password = encryptIt($my_password2);
            //insert to database
            $insert_data=$link->query("Insert Into `gy_optimum_secure`(`gy_sec_value`, `gy_sec_type`, `gy_user_id`) Values('$my_password','$my_pin_type','$my_user')");

            if ($insert_data) {    
                $my_note_text = $my_pin_type." command -> Another Password PIN is created";
                my_notify($my_note_text,$user_info);
                header("location: pins?note=nice");
                        
            }else{
                header("location: pins?note=error");
            }
        }

        
    }
?>