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
        $my_srp = words($_POST['my_srp']);

        //delete to database
        $update_data=$link->query("UPDATE `gy_restock` SET `gy_restock_quantity`='$my_quantity',`gy_product_price_srp`='$my_srp' Where `gy_restock_id`='$my_dir_value'");

        if ($update_data) {
            header("location: restock_counter?br_id=$branch_id&note=item_update");
        }else{
            header("location: restock_counter?br_id=$branch_id&note=error");
        }
    }
?>