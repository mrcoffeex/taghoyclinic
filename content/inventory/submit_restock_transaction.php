<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    if (isset($_POST['my_trans_code'])) {
        //elements
        $my_code = words($_POST['my_trans_code']);
        $my_prepared_By = words($_POST['my_prepared_by']);
        $my_supplier = words($_POST['my_supplier']);
        $my_note = words($_POST['my_note']);
        $date_now = date("Y-m-d H:i:s");
        $branch_id = words($_POST['my_branch']);

        //get the supplier info
        $get_supplier=$link->query("Select * From `gy_supplier` Where `gy_supplier_code`='$my_supplier'");
        $supp_row=$get_supplier->fetch_array();
        $count_supp_row=$get_supplier->num_rows;

        if ($count_supp_row > 0) {
            $my_supp_name = $supp_row['gy_supplier_name'];
        }else{
            $my_supp_name = "NONE";
        }

        //vars
        $total="";
        $srp_total="";

        $get_trans_dets=$link->query("Select * From `gy_restock` Where `gy_restock_code`='$my_code'");
        $count_items=$get_trans_dets->num_rows;

        if ($count_items == 0) {

            header("location: restock_counter?br_id=$branch_id&note=empty");

        }else{
            while ($trans_dets_row=$get_trans_dets->fetch_array()) {
                //vars
                $my_quantity = words($trans_dets_row['gy_restock_quantity']);
                $my_product_id = words($trans_dets_row['gy_product_id']);
                $my_new_cap_price = words($trans_dets_row['gy_product_price_cap']);
                $my_new_srp_price = words($trans_dets_row['gy_product_price_srp']);

                //update product quantity
                $udpate_products=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$my_quantity',`gy_supplier_code`='$my_supplier',`gy_product_date_restock`='$date_now',`gy_product_price_cap`='$my_new_cap_price',`gy_product_price_srp`='$my_new_srp_price' Where `gy_product_id`='$my_product_id'");
            }

            //insert to database
            $update_data=$link->query("UPDATE `gy_restock` SET `gy_restock_note`='$my_note', `gy_supplier_code`='$my_supplier', `gy_supplier_name`='$my_supp_name', `gy_restock_status`='1' Where `gy_restock_code`='$my_code' AND `gy_restock_by`='$user_id'");

            if ($update_data) {
                //deduct the quantities on products

                $my_note_text = "Restock Alert from ".$my_supp_name." Re-Stock Code No. ".$my_code;
                my_notify($my_note_text,$user_info);
                header("location: print_rsummary?br_id=$branch_id&cd=$my_code&mode=trans");
                        
            }else{
                header("location: restock_counter?br_id=$branch_id&note=error");
            }
        }

        
    }

?>