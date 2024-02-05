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
            $get_pullout_data=$link->query("Select * From `gy_stock_transfer` Where `gy_transfer_id`='$my_dir_value'");
            while ($transfer_row=$get_pullout_data->fetch_array()) {

                //items array
                $item_code = words($transfer_row['gy_product_id']);

                $getinfo=$link->query("SELECT * From `gy_products` Where `gy_product_id`='$item_code'");
                $info=$getinfo->fetch_array();

                $product_codes = words($info['gy_product_code']);
                $my_branch = words($transfer_row['gy_branch_id']);
                $my_quantity = words($transfer_row['gy_transfer_quantity']);

                $exist=$link->query("SELECT * From `gy_products` Where `gy_product_code`='$product_codes' AND `gy_branch_id`='$my_branch'");
                $exists=$exist->fetch_array();

                $product_exists = words($exists['gy_product_id']);

                //deduct the transfered quantity
                $udpate_data_link=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_id`='$product_exists' AND `gy_branch_id`='$my_branch'");

                //recover items
                $recover_items=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$my_quantity' Where `gy_product_id`='$item_code'");
            }

            if ($recover_items) {
                //delete query
                $delete_transfer_data=$link->query("Delete From `gy_stock_transfer` Where `gy_transfer_id`='$my_dir_value'");

                if ($delete_transfer_data) {
                    $my_note_text = "SOME STOCKS WAS REMOVED FROM STOCK-TRANSFER SUMMARY";
                    my_notify($my_note_text,$user_info);
                    header("location: transfer_reports?note=delete");
                }else{
                    header("location: transfer_reports?note=error");
                }
            }else{
                header("location: transfer_reports?note=error");
            }
    		
    	}else{
    		header("location: transfer_reports?note=pin_out");
    	}
    }
?>