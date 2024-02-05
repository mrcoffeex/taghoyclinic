<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['delete_item'])) {

        $my_dir_value = words($_GET['cd']);
        $branch_id = $_GET['br_id'];

        //delete to database
        $delete_data=$link->query("Delete From `gy_restock` Where `gy_restock_id`='$my_dir_value'");

        if ($delete_data) {
            header("location: restock_counter?br_id=$branch_id&note=item_remove");
        }else{
            header("location: restock_counter?br_id=$branch_id&note=error");
        }
    }
?>