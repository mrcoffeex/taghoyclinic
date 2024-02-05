<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
        $my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'delete_sales');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='delete_sales' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        $new_trans_code = latest_code("gy_void", "gy_trans_code", "100001");

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
            //get items
            $get_items=$link->query("Select * From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");
            while ($get_items_row=$get_items->fetch_array()) {

                $my_code = words($new_trans_code);
                $my_date = words($get_items_row['gy_transdet_date']);
                $my_product_id = words($get_items_row['gy_product_id']);
                $my_product_price = words($get_items_row['gy_product_price']);
                $my_product_discount = words($get_items_row['gy_product_discount']);
                $my_quantity = words($get_items_row['gy_trans_quantity']);
                $my_type = words($get_items_row['gy_transdet_type']);

                //recover items
                $recover_items=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$my_quantity' Where `gy_product_id`='$my_product_id'");

                //insert to void details table
                $trans_void_details=$link->query("Insert Into `gy_void_details`(`gy_trans_code`, `gy_transdet_date`, `gy_product_id`, `gy_product_price`, `gy_product_discount`, `gy_trans_quantity`, `gy_transdet_type`) Values('$my_code','$my_date','$my_product_id','$my_product_price','$my_product_discount','$my_quantity','$my_type')");
            }

            //get transaction info
            $get_trans=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
            $trans_row=$get_trans->fetch_array();

            //transaction info
            $my_trans_code=words($new_trans_code);
            $my_trans_pay=words($trans_row['gy_trans_pay']);
            $my_trans_check_per=words($trans_row['gy_trans_check_per']);
            $my_trans_check_num=words($trans_row['gy_trans_check_num']);
            $my_trans_royal_fee=words($trans_row['gy_trans_royal_fee']);
            $my_trans_custname=words($trans_row['gy_trans_custname']);
            $my_trans_date=words($trans_row['gy_trans_date']);
            $my_trans_type=words($trans_row['gy_trans_type']);
            $my_trans_total=words($trans_row['gy_trans_total']);
            $my_trans_discount=words($trans_row['gy_trans_discount']);
            $my_trans_cash=words($trans_row['gy_trans_cash']);
            $my_trans_change=words($trans_row['gy_trans_change']);
            $my_prepared_by=words($trans_row['gy_prepared_by']);
            $my_user_id=words($trans_row['gy_user_id']);
            $my_trans_status=words($trans_row['gy_trans_status']);
            $my_trans_check=words($trans_row['gy_trans_check']);
            $my_trans_check_date=words($trans_row['gy_trans_check_date']);
            $my_branch=words($trans_row['gy_branch_id']);

            //insert to void table
            $trans_void=$link->query("Insert Into `gy_void`(`gy_trans_code`, `gy_trans_pay`, `gy_trans_check_per`, `gy_trans_check_num`, `gy_trans_royal_fee`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`, `gy_void_by`, `gy_branch_id`) Values('$my_trans_code','$my_trans_pay','$my_trans_check_per','$my_trans_check_num','$my_trans_royal_fee','$my_trans_custname','$my_trans_date','$my_trans_type','$my_trans_total','$my_trans_discount','$my_trans_cash','$my_trans_change','$my_prepared_by','$my_user_id','$my_trans_status','$my_trans_check','$my_trans_check_date','$approved_by','$my_branch')");

            if ($recover_items && $trans_void_details && $trans_void) {
                //delete query
                $delete_transaction=$link->query("Delete From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
                $delete_trans_details=$link->query("Delete From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");

                if ($delete_transaction && delete_trans_details) {
                    $my_note_text = "VOID ALERT TRANSACTION - ".$my_dir_value." APPROVED BY -> ".$approved_by." AND WAS TRANSFERED TO VOID TRANSACTIONS";
                    my_notify($my_note_text,$user_info);
                    header("location: sales?note=delete");
                }else{
                    header("location: sales?note=error");
                }
            }else{
                header("location: sales?note=error");
            }
            
        }else{
            header("location: sales?note=pin_out");
        }
    }
?>