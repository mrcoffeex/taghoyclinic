<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'restock_pullout_stock_transfer');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='restock_pullout_stock_transfer' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {
            //get items
            $get_pullout_data=$link->query("Select * From `gy_pullout` Where `gy_pullout_id`='$my_dir_value'");
            $pullout_row=$get_pullout_data->fetch_array();
                //items array
                $item_quantity = words($pullout_row['gy_pullout_quantity']);
                $item_code = words($pullout_row['gy_product_code']);

                //recover items
                $recover_items=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$item_quantity' Where `gy_product_code`='$item_code'");

            if ($recover_items) {
                //delete query
                $update_back_order_data=$link->query("Update `gy_pullout` SET `gy_backorder_status`='1' Where `gy_pullout_id`='$my_dir_value'");

                if ($update_back_order_data) {
                    $my_note_text = "Back Order Item -> ".$pullout_row['gy_product_name']." new item returned to inventory";
                    my_notify($my_note_text,$user_info);
                    header("location: back_order_reports?note=checked");
                }else{
                    header("location: back_order_reports?note=error");
                }
            }else{
                header("location: back_order_reports?note=error");
            }
    		
    	}else{
    		header("location: back_order_reports?note=pin_out");
    	}
    }
?>