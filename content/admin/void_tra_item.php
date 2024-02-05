<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];
    $dir = @$_GET['dir'];

    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'void_tra');

        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='void_tra' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

		    //void item
		    $getitem=$link->query("SELECT `gy_products`.`gy_product_name`,`gy_tra_details`.`gy_trans_code`,`gy_tra_details`.`gy_product_price`,`gy_tra_details`.`gy_trans_quantity`,`gy_tra_details`.`gy_product_code` From `gy_tra_details` LEFT JOIN `gy_products` On `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_transdet_id`='$my_dir_value'");
		    $itemrow=$getitem->fetch_array();

		    $transcode=words($itemrow['gy_trans_code']);
		    $itemname = $itemrow['gy_product_name'];
		    $transprice = $itemrow['gy_product_price'] * $itemrow['gy_trans_quantity'];
		    $itemcount = words($itemrow['gy_trans_quantity']);
		    $itemcode = words($itemrow['gy_product_code']);

		    //update total price in tra
		    $updatetotal=$link->query("UPDATE `gy_tra` SET `gy_trans_total`=`gy_trans_total` - '$transprice' Where `gy_trans_code`='$transcode'");

		    //update item quantity
		    $updatequantity=$link->query("UPDATE `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$itemcount' Where `gy_product_code`='$itemcode'");

		    if ($updatetotal && $updatequantity) {
		    	//delete item
		    	$deleteitem=$link->query("DELETE FROM `gy_tra_details` Where `gy_transdet_id`='$my_dir_value'");

		    	if ($deleteitem) {
		    		//success
		    		$my_note_text = "VOID TRA ITEM approved by ".$approved_by." -> Item: ".$itemname." with an amount of ".@number_format($transprice,2);
		            my_notify($my_note_text,$user_info);
		    		echo "
		                <script>
		                    window.alert('Item has been removed ...');
		                    window.location.href = '{$_SERVER['HTTP_REFERER']}'
		                </script>
		            ";
		    	}else{
		    		//failed
		    		echo "
		                <script>
		                    window.alert('System Error!');
		                    window.location.href = '{$_SERVER['HTTP_REFERER']}'
		                </script>
		            ";
		    	}
		    }else{
		    	//failed
				echo "
		            <script>
		                window.alert('System Error!');
		                window.location.href = '{$_SERVER['HTTP_REFERER']}'
		            </script>
		        ";
		    }

		}else{
    		echo "
                    <script>
                        window.alert('Incorrect PIN!');
                        window.location.href = '{$_SERVER['HTTP_REFERER']}'
                    </script>
                ";
    	}
    }
?>