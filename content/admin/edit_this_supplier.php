<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['auth_edit_supplier'])) {

        $my_dir_value = words($_GET['cd']);
        $my_name = words($_POST['my_name']);
        $my_desc = words($_POST['my_desc']);
        $my_address = words($_POST['my_address']);
        $my_contact = words($_POST['my_contact']);

        //update to database
        $update_data=$link->query("Update `gy_supplier` SET `gy_supplier_name`='$my_name',`gy_supplier_desc`='$my_desc',`gy_supplier_address`='$my_address',`gy_supplier_contact`='$my_contact' Where `gy_supplier_id`='$my_dir_value'");

        if ($update_data) { 
            $my_note_text = "Supplier ".$my_name." info is updated";
            my_notify($my_note_text,$user_info);
            header("location: edit_supplier?note=nice_update&cd=$my_dir_value");
        }else{
            header("location: edit_supplier?note=error");
        }
    }
?>