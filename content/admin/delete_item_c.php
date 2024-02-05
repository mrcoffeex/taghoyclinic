<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['delete_item'])) {

        $my_dir_value = words($_GET['cd']);
        $my_dir_value_s = words($_GET['sd']);

        //delete to database
        $delete_data=$link->query("Delete From `gy_trans_details` Where `gy_transdet_id`='$my_dir_value'");

        if ($delete_data) {
            header("location: cashier?note=item_remove&cd=$my_dir_value_s");
        }else{
            header("location: cashier?note=error&cd=$my_dir_value_s");
        }
    }
?>