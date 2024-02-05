<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['for_use'])) {

        $my_dir_value = words($_GET['cd']);

        $my_type = "FOR_USE";
        $my_pullout_quantity = words($_POST['my_pullout_quantity']);
        $my_note = words($_POST['my_note']);
        $my_date = words(date("Y-m-d H:i:s"));

        //get product price
        $get_item_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_dir_value'");
        $item_row=$get_item_info->fetch_array();

        $my_total_amount = $item_row['gy_product_price_cap'] * $my_pullout_quantity;

        if ($my_pullout_quantity == 0) {
            //empty quantity
            header("location: products?cd=empty_input");
        }else{
            //insert pull-out
            $insert_data=$link->query("Insert Into `gy_pullout`(`gy_pullout_type`,`gy_product_code`,`gy_pullout_note`,`gy_pullout_quantity`,`gy_pullout_date`,`gy_pullout_status`,`gy_pullout_by`) Values('$my_type','$my_dir_value','$my_note','$my_pullout_quantity','$my_date','1','$user_id')");

            if ($insert_data) {
                //update product quantity
                $update_data=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_pullout_quantity' Where `gy_product_code`='$my_dir_value'");

                //add expenses
                $my_exp_note = "Pull-Out (For Use) Item: ".$item_row['gy_product_name']." ".$my_pullout_quantity." ".$item_row['gy_product_unit']." Note: ".$my_note;
                
                $insert_exp=$link->query("Insert Into `gy_expenses`(`gy_exp_note`,`gy_exp_amount`,`gy_user_id`,`gy_exp_date`) Values('$my_exp_note','$my_total_amount','$user_id','$my_date')");

                if ($update_data) {
                    $my_note_text = "Pull-out Item ".$my_dir_value." for use";
                    my_notify($my_note_text,$user_info);
                    header("location: products?note=nice");
                }else{
                    header("location: products?note=error");
                }

            }else{
                header("location: products?note=error");
            }
        }       
    }

    if (isset($_POST['stock_transfer'])) {

        $my_dir_value = words($_GET['cd']);

        $my_type = "STOCK_TRANSFER";
        $my_pullout_quantity = words($_POST['my_pullout_quantity']);
        $my_note = words($_POST['my_note']);
        $my_date = words(date("Y-m-d H:i:s"));

        //get product price
        $get_item_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_dir_value'");
        $item_row=$get_item_info->fetch_array();

        $my_total_amount = $item_row['gy_product_price_cap'] * $my_pullout_quantity;

        if ($my_pullout_quantity == 0) {
            //empty quantity
            header("location: products?cd=empty_input");
        }else{
            //insert pull-out
            $insert_data=$link->query("Insert Into `gy_pullout`(`gy_pullout_type`,`gy_product_code`,`gy_pullout_note`,`gy_pullout_quantity`,`gy_pullout_date`,`gy_pullout_status`,`gy_pullout_by`) Values('$my_type','$my_dir_value','$my_note','$my_pullout_quantity','$my_date','1','$user_id')");

            if ($insert_data) {
                //update product quantity
                $update_data=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_pullout_quantity' Where `gy_product_code`='$my_dir_value'");

                //add expenses
                $my_exp_note = "Pull-Out (Stock Transfer/DR) Item: ".$item_row['gy_product_name']." ".$my_pullout_quantity." ".$item_row['gy_product_unit']." Note: ".$my_note;

                $insert_exp=$link->query("Insert Into `gy_expenses`(`gy_exp_note`,`gy_exp_amount`,`gy_user_id`,`gy_exp_date`) Values('$my_exp_note','$my_total_amount','$user_id','$my_date')");

                if ($update_data) {
                    $my_note_text = "Pull-out Item ".$my_dir_value." for stock transfer/DR";
                    my_notify($my_note_text,$user_info);
                    header("location: products?note=nice");
                }else{
                    header("location: products?note=error");
                }

            }else{
                header("location: products?note=error");
            }
        }       
    }

    if (isset($_POST['back_order'])) {

        $my_dir_value = words($_GET['cd']);

        $my_type = "BACK_ORDER";
        $my_pullout_quantity = words($_POST['my_pullout_quantity']);
        $my_note = words($_POST['my_note']);
        $my_date = words(date("Y-m-d H:i:s"));

        //get product price
        $get_item_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_dir_value'");
        $item_row=$get_item_info->fetch_array();

        $my_total_amount = $item_row['gy_product_price_cap'] * $my_pullout_quantity;

        if ($my_pullout_quantity == 0) {
            //empty quantity
            header("location: products?cd=empty_input");
        }else{
            //insert pull-out
            $insert_data=$link->query("Insert Into `gy_pullout`(`gy_pullout_type`,`gy_product_code`,`gy_pullout_note`,`gy_pullout_quantity`,`gy_pullout_date`,`gy_pullout_status`,`gy_pullout_by`) Values('$my_type','$my_dir_value','$my_note','$my_pullout_quantity','$my_date','1','$user_id')");

            if ($insert_data) {
                //update product quantity
                $update_data=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_pullout_quantity' Where `gy_product_code`='$my_dir_value'");

                //add expenses
                $my_exp_note = "Pull-Out (Back-Order) Item: ".$item_row['gy_product_name']." ".$my_pullout_quantity." ".$item_row['gy_product_unit']." Note: ".$my_note;

                $insert_exp=$link->query("Insert Into `gy_expenses`(`gy_exp_note`,`gy_exp_amount`,`gy_user_id`,`gy_exp_date`) Values('$my_exp_note','$my_total_amount','$user_id','$my_date')");

                if ($update_data) {
                    $my_note_text = "Pull-out Item ".$my_dir_value." for back order";
                    my_notify($my_note_text,$user_info);
                    header("location: products?note=nice");
                }else{
                    header("location: products?note=error");
                }

            }else{
                header("location: products?note=error");
            }
        }        
    }
?>