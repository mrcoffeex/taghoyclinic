<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['submit_discount'])) {

        $my_dir_value = words($_GET['cd']);
        $my_discount = words($_POST['my_discount']);
        $my_pin = encryptIt($_POST['my_pin']);

        $get_info=$link->query("Select * From `gy_trans_details` Where `gy_transdet_id`='$my_dir_value'");
        $info_row=$get_info->fetch_array();

        $my_product_code=$info_row['gy_product_code'];

        $approved_info = by_pin_get_user($_POST['my_pin'], 'add_discount');

        //check pin
        $check_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_value`='$my_pin' AND `gy_sec_type`='add_discount' AND `gy_user_id`='$approved_info'");
        $count_res=$check_pin->num_rows;

        if ($count_res > 0) {
            //check discount limit
            $get_discount_limit=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_product_code'");
            $product_row=$get_discount_limit->fetch_array();

            //my discount limit
            $my_discount_limit = $product_row['gy_product_price_srp'] - $product_row['gy_product_discount_per'];

            if ($my_discount <= $my_discount_limit) {
                //delete to database
                $update_data=$link->query("Update `gy_trans_details` SET `gy_product_discount`='$my_discount' Where `gy_transdet_id`='$my_dir_value'");

                if ($update_data) {
                    $my_note_text = $my_discount." percent discount is added to Transaction ID: ".$info_row['gy_trans_code'];
                    my_notify($my_note_text,$user_info);
                    header("location: cashier?note=add_discount&cd=".$info_row['gy_trans_code']."");
                }else{
                    header("location: cashier?note=error&cd=".$info_row['gy_trans_code']."");
                }
            }else{
                header("location: cashier?note=discount_limit&cd=".$info_row['gy_trans_code']."");
            }

            
        }else{
            header("location: cashier?note=pin_error&cd=".$info_row['gy_trans_code']."");
        }

        
    }
?>