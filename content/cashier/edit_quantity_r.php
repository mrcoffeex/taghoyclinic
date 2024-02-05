<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['submit_edit'])) {

        $my_dir_value = words($_GET['cd']);
        $my_dir_value_s = words($_GET['sd']);
        $my_retail_price = words($_POST['my_retail_price']);
        $my_quantity = words($_POST['my_quantity']);

        $my_pin_edit = words($_POST['my_pin_edit']);

        //get the srp
        $get_product_info=$link->query("Select * From `gy_products` LEFT JOIN `gy_trans_details` On `gy_products`.`gy_product_code`=`gy_trans_details`.`gy_product_code` Where `gy_trans_details`.`gy_transdet_id`='$my_dir_value'");
        $product_row=$get_product_info->fetch_array();

        //my discount
        $my_discount = words($product_row['gy_product_price_srp'] - $my_retail_price);

        if ($my_pin_edit == "") {
            //Update Discount
            $update_data=$link->query("Update `gy_trans_details` SET `gy_trans_quantity`='$my_quantity',`gy_trans_ref_rep_quantity`='$my_quantity',`gy_product_price`='$my_retail_price',`gy_product_discount`='$my_discount' Where `gy_transdet_id`='$my_dir_value'");

            if ($update_data) {
                header("location: replace_counter?note=item_update&cd=$my_dir_value_s");
            }else{
                header("location: replace_counter?note=error&cd=$my_dir_value_s");
            }
        }else{
            //check password
            $my_final_pin = words(encryptIt($my_pin_edit));

            $check_password=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='add_discount' AND `gy_sec_value`='$my_final_pin'");
            $count_res=$check_password->num_rows;

            $approved_info = by_pin_get_user($my_pin_edit, 'add_discount');
            //get approved_by info
            $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
            $approved_row=$get_approved_by_info->fetch_array();

            //value here
            $approved_by = $approved_row['gy_full_name'];

            if ($count_res > 0) {
                //Update Discount
                $update_data=$link->query("Update `gy_trans_details` SET `gy_trans_quantity`='$my_quantity',`gy_trans_ref_rep_quantity`='$my_quantity',`gy_product_price`='$my_retail_price',`gy_product_discount`='$my_discount' Where `gy_transdet_id`='$my_dir_value'");

                if ($update_data) {
                    $my_note_text = $approved_by." -> approved a discount with an amount of ".number_format($my_retail_price,2)." on Item: ".$product_row['gy_product_name'];
                    my_notify($my_note_text,$user_info);
                    header("location: replace_counter?note=item_update&cd=$my_dir_value_s");
                }else{
                    header("location: replace_counter?note=error&cd=$my_dir_value_s");
                }
            }else{
                header("location: replace_counter?note=pin_out&cd=$my_dir_value_s");
            }
        }        
    }
?>