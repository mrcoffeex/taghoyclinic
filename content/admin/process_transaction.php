<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //process
    
    if (isset($_POST['cash'])) {
        //elements
        $my_code = words($_POST['my_trans_code']);
        $my_cust_name = words($_POST['my_cust_name']);
        $my_cash = words($_POST['my_cash']);
        $my_change = words($_POST['my_change']);

        //vars
        $total="";
        $srp_total="";

        $get_trans_dets=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$my_code'");
        $count_items=$get_trans_dets->num_rows;

        if ($count_items == 0) {

            header("location: cashier.php?note=empty");

        }else{

            while ($trans_dets_row=$get_trans_dets->fetch_array()) {

                if ($trans_dets_row['gy_product_discount'] == 0) {

                    $my_final_price = $trans_dets_row['gy_product_price_srp'];

                }else{

                    $my_final_price = $item_row['gy_product_price_srp'] - $item_row['gy_product_discount'];

                }

                $my_subtotal = $my_final_price * $trans_dets_row['gy_trans_quantity'];

                $total += $my_subtotal;

                $srp_total += $trans_dets_row['gy_product_price_srp'] * $trans_dets_row['gy_trans_quantity'];

                $my_quantity = $trans_dets_row['gy_trans_quantity'];
                $my_product_code = $trans_dets_row['gy_product_code'];

                //deduct the items
                $deduct_items=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_code`='$my_product_code'");

            }

            //values
            $total_discount = $srp_total - $total;

            //insert to database
            $update_data=$link->query("Update `gy_transaction` SET `gy_trans_custname`='$my_cust_name', `gy_trans_type`='1', `gy_trans_total`='$total', `gy_trans_discount`='$total_discount',`gy_trans_cash`='$my_cash', `gy_trans_change`='$my_change', `gy_trans_status`='1',`gy_user_id`='$user_id' Where `gy_trans_code`='$my_code'");

            //update the item details
            $update_item_dets=$link->query("Update `gy_trans_details` SET `gy_transdet_type`='1' Where `gy_trans_code`='$my_code'");

            if ($update_data && $update_item_dets) {
                $my_note_text = "Transaction ID ".$my_code." is sold";
                my_notify($my_note_text,$user_info);
                header("location: trans_summary?cd=$my_code");
                        
            }else{
                header("location: cashier?note=error");
            }
        }
    }
?>