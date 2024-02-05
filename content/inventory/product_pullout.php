<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = $_GET['cd'];

    $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_dir_value'");
    $product_row=$get_product_info->fetch_array();
    
    //add member
    if (isset($_POST['my_pullout_quantity'])) {
        //elements
        $my_pullout_quantity = words($_POST['my_pullout_quantity']);
        $date_now = date("Y-m-d H:i:s");

        //insert to database
        $insert_data=$link->query("Insert Into `gy_pullout`(`gy_product_code`, `gy_pullout_quantity`, `gy_pullout_date`, `gy_pullout_by`) Values('$my_dir_value','$my_pullout_quantity','$date_now','$user_id')");

        if ($insert_data) {    
            
            //udpate quantity
            $update_product=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity`-'$my_pullout_quantity' Where `gy_product_code`='$my_dir_value'");

            if ($update_product) {

                //redirect
                $my_note_text = $my_pullout_quantity." stock units has been pulled-out from ".$product_row['gy_product_name'];
                my_notify($my_note_text,$user_info);
                header("location: products?note=pullout");

            }else{
                header("location: products?note=error");
            }
                    
        }else{
            header("location: products?note=error");
        }
    }
?>