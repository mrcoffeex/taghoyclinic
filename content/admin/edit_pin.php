<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['submit_pin_edit'])) {

        $my_dir_value = words($_GET['cd']);

        $my_user = words($_POST['my_user']);
        $my_pin_type = words($_POST['my_pin_type']);
        $my_password1 = words($_POST['my_password1']);
        $my_password2 = words($_POST['my_password2']);

        if ($my_password1 != $my_password2) {
            header("location: pins?note=pin_out");
        }else{
            $my_password = encryptIt($my_password2);
            //insert to database
            $update_data=$link->query("Update `gy_optimum_secure` SET `gy_sec_value`='$my_password',`gy_sec_type`='$my_pin_type',`gy_user_id`='$my_user' Where `gy_sec_id`='$my_dir_value'");

            if ($update_data) {    
                $my_note_text = $my_pin_type." command -> is Updated";
                my_notify($my_note_text,$user_info);
                header("location: pins?note=nice_update");
                        
            }else{
                header("location: pins?note=error");
            }
        }
    }
?>