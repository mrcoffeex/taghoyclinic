<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //process

    if (isset($_POST['my_trans_code'])) {
        //elements
        $my_code = words($_POST['my_trans_code']);
        $my_cust_name = words($_POST['my_cust_name']);
        $my_prepared_by = words($_POST['my_prepared_by']);
        $my_cash = words($_POST['my_cash'] + $_POST['my_add']);
        $my_change = words($_POST['my_change']);
        $date_now = words(date("Y-m-d H:i:s"));
        $my_secure_pin = words($_POST['my_secure_pin']);

        //royal fee calculation
        $royals = $my_check_percentage / 100;
        $my_royal_fee = $my_change * $royals;

        $approved_info = by_pin_get_user($my_secure_pin, 'ref_rep');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='ref_rep' AND `gy_user_id`='$approved_info'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            //vars
            $total="";
            $srp_total="";

            $get_trans_dets=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$my_code'");
            $count_items=$get_trans_dets->num_rows;

            if ($count_items == 0) {

                header("location: replace_counter?cd=$my_code&note=empty");

            }else{

                while ($trans_dets_row=$get_trans_dets->fetch_array()) {

                    $my_subtotal = $trans_dets_row['gy_product_price'] * $trans_dets_row['gy_trans_quantity'];

                    $total += $my_subtotal;

                    $srp_total += $trans_dets_row['gy_product_price'] * $trans_dets_row['gy_trans_quantity'];

                    $my_quantity = $trans_dets_row['gy_trans_quantity'];
                    $my_product_code = $trans_dets_row['gy_product_code'];

                    //deduct the items
                    $deduct_items=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_code`='$my_product_code'");

                }

                //values
                $total_discount = $srp_total - $total;
                
                $update_data=$link->query("Update `gy_transaction` SET `gy_trans_pay`= 0 ,`gy_trans_custname`='$my_cust_name',`gy_trans_date`='$date_now', `gy_trans_type`='1', `gy_trans_total`='$total', `gy_trans_discount`='$total_discount',`gy_trans_cash`='$my_cash', `gy_trans_change`='$my_change', `gy_trans_status`='1',`gy_prepared_by`='$my_prepared_by',`gy_user_id`='$user_id' Where `gy_trans_code`='$my_code'");
            
                //update the item details
                $update_item_dets=$link->query("Update `gy_trans_details` SET `gy_transdet_type`='1',`gy_transdet_date`='$date_now' Where `gy_trans_code`='$my_code'");

                if ($update_data && $update_item_dets) {
                    $my_note_text = "Replace Items approved by -> ".$approved_by." Transaction ID ".$my_code;
                    my_notify($my_note_text,$user_info);
                    header("location: print_receipt_thermal?cd=$my_code");
                            
                }else{
                    header("location: replace_counter?cd=$my_code&note=error");
                }
            }
        }
    }
?>