<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //submit to cashier
    
    if (isset($_POST['my_trans_code'])) {
        //elements
        $my_code = words($_POST['my_trans_code']);
        $my_prepared_By = words($_POST['my_prepared_by']);
        $my_branch = words($_POST['my_branch']);
        $my_note = words($_POST['my_note']);
        $date_now = date("Y-m-d H:i:s");
        $branch_id = words($_POST['my_branch_from']);

        //vars
        $total="";
        $srp_total="";

        $get_trans_dets=$link->query("Select * From `gy_stock_transfer` Where `gy_transfer_code`='$my_code'");
        $count_items=$get_trans_dets->num_rows;

        if ($count_items == 0) {

            header("location: stock_transfer_counter?br_id=$branch_id&note=empty");

        }else{
            while ($trans_dets_row=$get_trans_dets->fetch_array()) {
                //vars
                $my_quantity = words($trans_dets_row['gy_transfer_quantity']);
                $my_product_id = words($trans_dets_row['gy_product_id']);

                $getinfo=$link->query("SELECT * From `gy_products` Where `gy_product_id`='$my_product_id'");
                $info=$getinfo->fetch_array();

                $product_codes = words($info['gy_product_code']);

                $exist=$link->query("SELECT * From `gy_products` Where `gy_product_code`='$product_codes' AND `gy_branch_id`='$my_branch'");
                $count_exist=$exist->num_rows;

                $my_product_code = $info['gy_product_code'];
                $my_product_name = $info['gy_product_name'];
                $my_product_cat = $info['gy_product_cat'];
                $my_product_desc = $info['gy_product_desc'];
                $my_supplier_code = $info['gy_supplier_code'];
                $my_product_price_cap = $info['gy_product_price_cap'];
                $my_product_price_srp = $info['gy_product_price_srp'];
                $my_product_unit = $info['gy_product_unit'];
                $my_product_discount_per = $info['gy_product_discount_per'];
                $my_product_restock_limit = $info['gy_product_restock_limit'];
                $randoms = latest_code("gy_products", "gy_update_code", "10001");

                if ($count_exist > 0) {
                    $udpate_data_link=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$my_quantity' Where `gy_product_code`='$my_product_code' AND `gy_branch_id`='$my_branch'");
                }else{
                    $udpate_data_link=$link->query("INSERT Into `gy_products`(`gy_product_code`, `gy_product_cat`, `gy_supplier_code`, `gy_product_name`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_quantity`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_added_by`,`gy_update_code`,`gy_product_update_date`,`gy_branch_id`) Values('$my_product_code','$my_product_cat','$my_supplier_code','$my_product_name','$my_product_desc','$my_product_unit','$my_product_price_cap','$my_product_price_srp','$my_quantity','$my_product_discount_per','$my_product_restock_limit','$date_now','$date_now','$user_id','$randoms','$date_now','$my_branch')");
                }

                $udpate_products=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_id`='$my_product_id'");
                
            }

            //insert to database
            $update_data=$link->query("Update `gy_stock_transfer` SET `gy_transfer_note`='$my_note', `gy_branch_id`='$my_branch', `gy_transfer_status`='1' Where `gy_transfer_code`='$my_code' AND `gy_transfer_by`='$user_id'");

            if ($update_data) {
                //deduct the quantities on products

                $my_note_text = "Stock-Transfer Alert by ".$user_info." to ".get_branch_name($my_branch)." Stock-Transfer Code No. ".$my_code;
                my_notify($my_note_text,$user_info);

                header("location: print_tsummary?br_id=$branch_id&cd=$my_code&mode=trans");
                        
            }else{
                header("location: stock_transfer_counter?br_id=$branch_id&note=error");
            }
        }

        
    }

?>