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

        //delete to database
        $update_data=$link->query("Update `gy_trans_details` SET `gy_trans_quantity`='$my_quantity',`gy_product_price`='$my_retail_price' Where `gy_transdet_id`='$my_dir_value'");

        if ($update_data) {
            header("location: cashier?note=item_update&cd=$my_dir_value_s");
        }else{
            header("location: cashier?note=error&cd=$my_dir_value_s");
        }
    }
?>