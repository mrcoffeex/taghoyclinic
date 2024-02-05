<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    $branch_id = @$_GET['br_id'];

    if (isset($_POST['submit_edit'])) {

        $my_dir_value = words($_GET['cd']);
        $my_quantity = words($_POST['my_quantity']);

        //delete to database
        $update_data=$link->query("Update `gy_stock_transfer` SET `gy_transfer_quantity`='$my_quantity' Where `gy_transfer_id`='$my_dir_value'");

        if ($update_data) {
            header("location: stock_transfer_counter?br_id=$branch_id&note=item_update");
        }else{
            header("location: stock_transfer_counter?br_id=$branch_id&note=error");
        }
    }
?>