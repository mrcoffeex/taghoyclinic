<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['delete_item'])) {

        $my_dir_value = words($_GET['cd']);

        //delete to database
        $delete_data=$link->query("Delete From `gy_rqt` Where `gy_rqt_id`='$my_dir_value'");

        if ($delete_data) {
            header("location: request_counter?note=item_remove");
        }else{
            header("location: request_counter?note=error");
        }
    }
?>