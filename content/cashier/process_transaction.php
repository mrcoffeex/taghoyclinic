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
        $my_cash = words($_POST['my_cash']);
        $my_change = words($_POST['my_change']);
        $date_now = date("Y-m-d H:i:s");

        //vars
        $total="";
        $srp_total="";
        $count_bodega=0;

        $get_trans_dets=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_id`=`gy_products`.`gy_product_id` Where `gy_trans_details`.`gy_trans_code`='$my_code'");
        $count_items=$get_trans_dets->num_rows;

        if ($count_items == 0) {

            header("location: cashier?note=empty");

        }else{

            while ($trans_dets_row=$get_trans_dets->fetch_array()) {

                $my_subtotal = $trans_dets_row['gy_product_price'] * $trans_dets_row['gy_trans_quantity'];

                $total += $my_subtotal;

                $srp_total += $trans_dets_row['gy_product_price_srp'] * $trans_dets_row['gy_trans_quantity'];

                $my_quantity = $trans_dets_row['gy_trans_quantity'];
                $my_product_id = $trans_dets_row['gy_product_id'];

                $detcode=words($trans_dets_row['gy_transdet_id']);

                //update bodega claim quantity
                $udpate_bodega=$link->query("Update `gy_trans_details` SET `gy_trans_claim_quantity`='$my_quantity' Where `gy_transdet_id`='$detcode'");

                //deduct the items
                $deduct_items=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_id`='$my_product_id'");

            }

            //values
            $total_discount = $srp_total - $total;

            //insert to database
            $update_data=$link->query("Update `gy_transaction` SET `gy_trans_pay`= 0 ,`gy_trans_custname`='$my_cust_name',`gy_trans_date`='$date_now', `gy_trans_type`='1', `gy_trans_total`='$total', `gy_trans_discount`='$total_discount',`gy_trans_cash`='$my_cash', `gy_trans_change`='$my_change', `gy_trans_status`='1',`gy_prepared_by`='$my_prepared_by',`gy_user_id`='$user_id' Where `gy_trans_code`='$my_code'");

            $my_note_text = "Cash Transaction ID ".$my_code." is sold";

            //update the item details
            $update_item_dets=$link->query("Update `gy_trans_details` SET `gy_transdet_type`='1',`gy_transdet_date`='$date_now' Where `gy_trans_code`='$my_code'");

            if ($update_data && $update_item_dets) {
                
                my_notify($my_note_text,$user_info);
                header("location: trans_summary?cd=$my_code");
                        
            }else{
                header("location: cashier?note=error&cd=$my_code");
            }
        }
    }
?>