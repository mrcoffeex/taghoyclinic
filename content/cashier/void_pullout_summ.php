<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'delete_sales');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='delete_sales' AND `gy_user_id`='$approved_info'");
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
                $delete_pullout_data=$link->query("Delete From `gy_pullout` Where `gy_pullout_id`='$my_dir_value'");

                if ($delete_pullout_data) {
                    $my_note_text = "SOME STOCKS WAS REMOVED FROM PULL-OUT SUMMARY";
                    my_notify($my_note_text,$user_info);
                    header("location: pullout_reports?note=delete");
                }else{
                    header("location: pullout_reports?note=error");
                }
            }else{
                header("location: pullout_reports?note=error");
            }
    		
    	}else{
    		header("location: pullout_reports?note=pin_out");
    	}
    }
?>