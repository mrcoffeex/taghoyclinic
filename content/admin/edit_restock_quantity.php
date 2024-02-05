<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $branch_id = $_GET['br_id'];

    if (isset($_POST['submit_edit'])) {

        $my_dir_value = words($_GET['cd']);
        $mypcode = words($_GET['pcode']);
        $my_quantity = words($_POST['my_quantity']);
        $my_capital = words($_POST['my_capital']);
        $my_srp = words($_POST['my_srp']);
        $my_limit = words($_POST['my_limit']);

        //delete to database
        $update_data=$link->query("UPDATE `gy_restock` SET `gy_restock_quantity`='$my_quantity',`gy_product_price_cap`='$my_capital',`gy_product_price_srp`='$my_srp' Where `gy_restock_id`='$my_dir_value'");

        $update_product=$link->query("UPDATE `gy_products` SET `gy_product_discount_per`='$my_limit' Where `gy_product_code`='$mypcode'");

        if ($update_data && $update_product) {
            header("location: restock_counter?br_id=$branch_id&note=item_update");
        }else{
            header("location: restock_counter?br_id=$branch_id&note=error");
        }
    }
?>