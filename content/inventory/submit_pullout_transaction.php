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
        $my_type = words($_POST['my_type']);
        $my_note = words($_POST['my_note']);
        $date_now = date("Y-m-d H:i:s");
        $branch_id = words($_POST['my_branch']);

        //vars
        $total="";
        $srp_total="";

        $get_trans_dets=$link->query("Select * From `gy_pullout` Where `gy_pullout_code`='$my_code'");
        $count_items=$get_trans_dets->num_rows;

        if ($count_items == 0) {

            header("location: pullout_counter?br_id=$branch_id&note=empty");

        }else{
            while ($trans_dets_row=$get_trans_dets->fetch_array()) {
                //vars
                $my_quantity = words($trans_dets_row['gy_pullout_quantity']);
                $my_product_id = words($trans_dets_row['gy_product_id']);

                //update product quantity
                $udpate_products=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_id`='$my_product_id'");
            }

            //insert to database
            $update_data=$link->query("Update `gy_pullout` SET `gy_pullout_note`='$my_note', `gy_pullout_type`='$my_type', `gy_pullout_status`='1' Where `gy_pullout_code`='$my_code' AND `gy_pullout_by`='$user_id'");

            if ($my_type == "FOR_USE") {
                //for use add to expenses
                //get for use status with this pullout code
                $get_trans_use=$link->query("Select * From `gy_pullout` Where `gy_pullout_code`='$my_code' AND `gy_pullout_type`='FOR_USE'");
                while ($use_row=$get_trans_use->fetch_array()) {

                    //the product codes
                    $product_codes=words($use_row['gy_product_id']);

                    //get the total amount
                    $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_id`='$product_codes'");
                    $product_row=$get_product_info->fetch_array();

                    $my_total_amount = $product_row['gy_product_price_srp'] * $use_row['gy_pullout_quantity'];

                    $my_exp_note = "Pull-Out (For Use) Item: ".$use_row['gy_product_name']." ".$use_row['gy_pullout_quantity']." ".$product_row['gy_product_unit']." Note: ".$my_note;
                    //add to expenses
                    $insert_exp=$link->query("Insert Into `gy_expenses`(`gy_exp_note`,`gy_exp_type`,`gy_exp_amount`,`gy_user_id`,`gy_approved_by`,`gy_exp_date`,`gy_branch_id`) Values('$my_exp_note','$my_type','$my_total_amount','$user_id','$user_id','$date_now','$branch_id')");
                    

                    if (!$insert_exp) {
                        header("location: pullout_counter?br_id=$branch_id&note=error");
                    }
                }

            }else if ($my_type == "DAMAGE") {
                //for use add to expenses
                //get for use status with this pullout code
                $get_trans_use=$link->query("Select * From `gy_pullout` Where `gy_pullout_code`='$my_code' AND `gy_pullout_type`='DAMAGE'");
                while ($use_row=$get_trans_use->fetch_array()) {

                    //the product codes
                    $product_codes=words($use_row['gy_product_id']);

                    //get the total amount
                    $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_id`='$product_codes'");
                    $product_row=$get_product_info->fetch_array();

                    $my_total_amount = $product_row['gy_product_price_srp'] * $use_row['gy_pullout_quantity'];

                    $my_exp_note = "Pull-Out (Damaged Item) Item: ".$use_row['gy_product_name']." ".$use_row['gy_pullout_quantity']." ".$product_row['gy_product_unit']." Note: ".$my_note;
                    //add to expenses
                    $insert_exp=$link->query("Insert Into `gy_expenses`(`gy_exp_note`,`gy_exp_type`,`gy_exp_amount`,`gy_user_id`,`gy_approved_by`,`gy_exp_date`,`gy_branch_id`) Values('$my_exp_note','$my_type','$my_total_amount','$user_id','$user_id','$date_now','$branch_id')");
                    

                    if (!$insert_exp) {
                        header("location: pullout_counter?br_id=$branch_id&note=error");
                    }
                }
                
            }else{
                //nothing to do
            }

            if ($update_data) {
                //deduct the quantities on products

                $my_note_text = "Pull-Out Alert by ".$user_info." Pull-Out Code No. ".$my_code;
                my_notify($my_note_text,$user_info);
                // header("location: pullout_counter?note=stocks_pullout");
                header("location: print_psummary?br_id=$branch_id&cd=$my_code&mode=trans");
                        
            }else{
                header("location: pullout_counter?br_id=$branch_id&note=error");
            }
        }

        
    }

?>