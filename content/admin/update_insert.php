<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $getnew=$link->query("Select `gy_product_code`,`gy_product_name`,`gy_product_cat`,`gy_product_desc`,`gy_product_unit`,`gy_product_price_cap`,`gy_product_price_srp`,`gy_product_discount_per`,`gy_product_restock_limit`,`gy_product_date_restock`,`gy_product_date_reg`,`gy_update_code` From `gy_update` Where `gy_update_id`='0'");
    while ($newrow=$getnew->fetch_array()) {
        //insert new records
        $mycode = words($newrow['gy_product_code']);
        $myname = words($newrow['gy_product_name']); 
        $mycat = words($newrow['gy_product_cat']);
        $mydesc = words($newrow['gy_product_desc']);
        $myunit = words($newrow['gy_product_unit']);
        $mycap = words($newrow['gy_product_price_cap']);
        $mysrp = words($newrow['gy_product_price_srp']);
        $myper = words($newrow['gy_product_discount_per']);
        $mylimit = words($newrow['gy_product_restock_limit']);
        $myrestock = words($newrow['gy_product_date_restock']);
        $myreg = words($newrow['gy_product_date_reg']);
        $updatecode = words($newrow['gy_update_code']);

        //insert records
        $insertdata=$link->query("INSERT INTO `gy_products`(`gy_product_code`, `gy_product_name`, `gy_product_cat`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_quantity`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_added_by`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_update_code`) SELECT (`gy_product_code`, `gy_product_name`, `gy_product_cat`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_quantity`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_added_by`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_update_code`) From `gy_update` Where `gy_update`.`gy_update_code`='$updatecode'");
    }

    if ($insertdata) {
    	header("location: update_item?note=success");
    }else{
    	header("location: update_item?note=error");
    }
?>