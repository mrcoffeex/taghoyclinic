<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = $_GET['cd'];
    $my_dir_value_s = $_GET['sd'];

    $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_dir_value'");
    $product_row=$get_product_info->fetch_array();

    $my_product_name=$product_row['gy_product_name'];
    
    //add member
    if (isset($_POST['restock'])) {
        //elements
        $my_restock_quantity = words($_POST['my_restock_quantity']);
        $my_supplier = words($_POST['my_supplier']);
        $my_note = words($_POST['my_note']);
        $date_now = date("Y-m-d H:i:s");

        //get the restock code
        $get_code=$link->query("Select * From `gy_restock` Order By `gy_restock_code` DESC LIMIT 1");
        $restock_code_row=$get_code->fetch_array();

        if ($restock_code_row['gy_restock_code'] == 0) {
            $my_restock_code = "10000001";
        }else{
            $my_restock_code = $restock_code_row['gy_restock_code'] + 1;
        }

        //insert to database
        $insert_data=$link->query("Insert Into `gy_restock`(`gy_restock_code`,`gy_product_code`, `gy_product_name`, `gy_supplier_code`, `gy_restock_note`, `gy_restock_quantity`, `gy_restock_date`, `gy_restock_status`, `gy_restock_by`) Values('$my_restock_code','$my_dir_value','$my_product_name','$my_supplier','$my_note','$my_restock_quantity','$date_now', 1,'$user_id')");

        if ($insert_data) {    
            
            //udpate quantity
            $update_product=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity`+'$my_restock_quantity', `gy_product_date_restock`='$date_now',`gy_supplier_code`='$my_supplier' Where `gy_product_code`='$my_dir_value'");

            if ($update_product) {

                //redirect
                $my_note_text = $my_restock_quantity." stock units is added to ".$product_row['gy_product_name'];
                my_notify($my_note_text,$user_info);
                header("location: $my_dir_value_s?note=nice_update");

            }else{
                header("location: $my_dir_value_s?note=error");
            }
                    
        }else{
            header("location: $my_dir_value_s?note=error");
        }
    }
?>