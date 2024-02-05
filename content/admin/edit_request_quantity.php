<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['submit_edit'])) {

        $my_dir_value = words($_GET['cd']);
        $my_quantity = words($_POST['my_quantity']);

        //delete to database
        $update_data=$link->query("Update `gy_rqt` SET `gy_rqt_quantity`='$my_quantity' Where `gy_rqt_id`='$my_dir_value'");

        if ($update_data) {
            header("location: request_counter?note=item_update");
        }else{
            header("location: request_counter?note=error");
        }
    }
?>