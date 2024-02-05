<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
        $my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'delete_trans');

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='delete_trans' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
            //delete to database
            $delete_data=$link->query("Delete From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
            $delete_data_details=$link->query("Delete From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");

            if ($delete_data && $delete_data_details) {
                $my_note_text = "Transaction Code: ".$my_dir_value." has been removed";
                my_notify($my_note_text,$user_info);
                header("location: index?note=nice");
            }else{
                header("location: index?note=error");
            }
        }else{
            header("location: index?note=pin_out");
        }
    }
?>