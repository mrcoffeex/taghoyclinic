<?php  
    //my scripts
    $check_transaction=$link->query("Select * From `gy_tra` Where `gy_prepared_by`='$user_id' AND `gy_trans_status`='0'");
    $count_trans=$check_transaction->num_rows;

    if ($count_trans > 0) {
        $get_trans=$link->query("Select * From `gy_tra` Where `gy_prepared_by`='$user_id' AND `gy_trans_status`='0' Order By `gy_trans_code` DESC");
        $get_trans_row=$get_trans->fetch_array();

        $my_trans_code = $get_trans_row['gy_trans_code'];
    }else{
        $get_latest_trans=$link->query("Select * From `gy_tra` Order By `gy_trans_code` DESC LIMIT 1");
        $trans_row=$get_latest_trans->fetch_array();

        if ($trans_row['gy_trans_code'] == 0) {
            $create_trans_code = "1000001";
        }else{
            $create_trans_code = $trans_row['gy_trans_code'] + 1;
        }

        //add this trans code to database
        $add_this_trans_code=$link->query("Insert Into `gy_tra`(`gy_trans_code`,`gy_trans_type`, `gy_trans_total`, `gy_trans_interest`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_trans_status`) Values('$create_trans_code','0','0','0','0','0','0','$user_id','0','0')");

        if (!$add_this_trans_code) {
            echo "
                <script>
                    window.alert('Transfer Error!');
                </script>
            ";
        }else{
            $get_trans=$link->query("Select * From `gy_tra` Where `gy_prepared_by`='$user_id' AND `gy_trans_status`='0' AND `gy_trans_code`='$create_trans_code'");
            $get_trans_row=$get_trans->fetch_array();

            $my_trans_code = $get_trans_row['gy_trans_code'];
        }
    }
?>