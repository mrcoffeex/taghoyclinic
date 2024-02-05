<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['submit_replace'])) {

        $my_dir_value=$_GET['cd'];
        //get the dir values
        $get_dir_value=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_transaction` On `gy_trans_details`.`gy_trans_code`=`gy_transaction`.`gy_trans_code` Where `gy_trans_details`.`gy_transdet_id`='$my_dir_value'");
        $dir_row=$get_dir_value->fetch_array();

        //values
        $my_trans_code=words($dir_row['gy_trans_code']);
        $my_code=words($dir_row['gy_product_id']);
        $my_price=words($dir_row['gy_product_price']);
        $my_custname=words($dir_row['gy_trans_custname']);
        $my_user_ids=words($dir_row['gy_user_id']); 
        $my_trans_date=words($dir_row['gy_trans_date']);   

        //get the transaction array
        $get_transaction=$link->query("Select * From `gy_products` Where `gy_product_id`='$my_code'");
        $get_trans_row=$get_transaction->fetch_array();

        $my_item=words($get_trans_row['gy_product_name']);
        $my_unit=words($get_trans_row['gy_product_unit']);

        //elements
        $my_refund_quantity = words($_POST['my_refund_quantity']);
        $my_note = words($_POST['my_note']);
        $my_secure_pin = words($_POST['my_secure_pin']);
        $date_now = words(date("Y-m-d H:i:s"));

            if ($my_refund_quantity == 0) {
                header("location: sales?note=zero");
            }else{

                $approved_info = by_pin_get_user($my_secure_pin, 'ref_rep');
                //get approved_by info
                $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
                $approved_row=$get_approved_by_info->fetch_array();

                //value here
                $approved_by = $approved_row['gy_full_name'];

                $my_final_note = $my_note." - Approved By: ".$approved_by;

                //get secure pin
                $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='ref_rep' AND `gy_user_id`='$approved_info'");
                $get_values=$get_secure_pin->fetch_array();

                if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

                    //add the refund record and update to products

                    $add_to_replace=$link->query("Insert Into `gy_refund`(`gy_refund_type`,`gy_trans_code`,`gy_trans_custname`, `gy_product_code`, `gy_product_name`, `gy_product_price`, `gy_product_quantity`, `gy_refund_note`, `gy_user_id`, `gy_refund_date`, `gy_trans_date`) VALUES ('REPLACE','$my_trans_code','$my_custname','$my_code','$my_item','$my_price','$my_refund_quantity','$my_final_note','$user_id','$date_now','$my_trans_date')");

                    if ($add_to_replace) {

                        //Update Transaction
                        $update_trans=$link->query("Update `gy_trans_details` SET `gy_trans_ref_rep_quantity`=`gy_trans_ref_rep_quantity` - '$my_refund_quantity' Where `gy_transdet_id`='$my_dir_value'");

                        //Update Product
                        $update_inventory=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$my_refund_quantity' Where `gy_product_id`='$my_code'");

                        if ($update_trans && $update_inventory) {

                            $my_note_text = $approved_by." -> approved Replace Notification from TransCode: ".$my_trans_code." - ".$my_code." -> ".$my_refund_quantity." ".$my_unit." of ".$my_item;
                            my_notify($my_note_text,$user_info);
                            header("location: sales?note=replace");
                        }else{
                            header("location: sales?note=error");
                        }   
                    }else{
                        header("location: sales?note=add_refund_error");
                    }
            }else{
                header("location: sales?note=pin_out");
            }
        }
    }
?>