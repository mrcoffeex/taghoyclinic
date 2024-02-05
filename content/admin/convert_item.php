<?php 
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = words($_GET['cd']);

    //get product data
    $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_id`='$my_dir_value'");
    $product_row=$get_product_info->fetch_array();

    $my_product_code = $product_row['gy_product_code'];
    $my_product_name = $product_row['gy_product_name'];
    $my_product_unit = $product_row['gy_product_unit'];

    $my_convert_code = $product_row['gy_convert_item_code'];

    //get convert item values
    $get_convert_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_convert_code'");
    $convert_row=$get_convert_info->fetch_array();

    $my_convert_code = $convert_row['gy_product_code'];
    $my_convert_name = $convert_row['gy_product_name'];
    $my_convert_unit = $convert_row['gy_product_unit'];

    //add member
    if (isset($_POST['submit_convert'])) {
    	$my_quantity = words($_POST['my_quantity']);
    	$my_convert_quantity = words($_POST['my_convert_quantity']);

    	//deduct the item
    	$deduct_item=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_code`='$my_product_code'");

    	//add the items
    	$add_item=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$my_convert_quantity' Where `gy_product_code`='$my_convert_code'");

    	if ($deduct_item && $add_item) {
    		$my_note_text = $my_product_name." converted ".$my_quantity." ".$my_product_unit." to ".$my_convert_quantity." ".$my_convert_unit." of ".$my_convert_name;
                my_notify($my_note_text,$user_info);
    		header("location: products?note=converted");
    	}else{
    		header("location: products?note=error");
    	}

    }
?>