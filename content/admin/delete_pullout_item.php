<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['delete_item'])) {

        $branch_id = @$_GET['br_id'];
        $my_dir_value = words($_GET['cd']);

        //delete to database
        $delete_data=$link->query("Delete From `gy_pullout` Where `gy_pullout_id`='$my_dir_value'");

        if ($delete_data) {
            header("location: pullout_counter?br_id=$branch_id&note=item_remove");
        }else{
            header("location: pullout_counter?br_id=$branch_id&note=error");
        }
    }
?>