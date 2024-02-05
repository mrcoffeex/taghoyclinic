<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    if (isset($_POST['my_trans_code'])) {
    	$my_code = words($_POST['my_trans_code']);
        $my_branch = words($_POST['my_branch']);
    	$my_note = words($_POST['my_note']);
    	$my_prepared_by = words($_POST['my_prepared_by']);
        $date_now = date("Y-m-d H:i:s");

        $get_trans_dets=$link->query("Select * From `gy_rqt` Where `gy_rqt_code`='$my_code'");
        $count_items=$get_trans_dets->num_rows;

        if ($count_items == 0) {

            header("location: request_counter.php?note=empty");

        }else{

            //insert to database
            $update_data=$link->query("UPDATE `gy_rqt` SET `gy_rqt_note`='$my_note',`gy_rqt_branch`='$my_branch',`gy_rqt_status`='1' Where `gy_rqt_code`='$my_code' AND `gy_rqt_by`='$user_id'");

            if ($update_data) {
                //deduct the quantities on products

                $my_note_text = "Request Order Alert No. ".$my_code;
                my_notify($my_note_text,$user_info);
                header("location: print_rqt?cd=$my_code&encoder=$my_prepared_by");
                        
            }else{
                header("location: request_counter?note=error");
            }
        }
    }

?>