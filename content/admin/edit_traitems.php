<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    if (isset($_POST['btn-priceupdate'])) {

        $my_dir_value = words($_GET['cd']);
        $transcode = words($_GET['code']);

        //get full tra info
        $trainfo=$link->query("Select `gy_tra_details`.`gy_product_code`,`gy_tra_details`.`gy_trans_quantity`,`gy_tra_details`.`gy_product_price`,`gy_tra`.`gy_trans_total` From `gy_tra_details` LEFT JOIN `gy_tra` On `gy_tra_details`.`gy_trans_code`=`gy_tra`.`gy_trans_code` Where `gy_tra_details`.`gy_transdet_id`='$my_dir_value'");
        $trainforow=$trainfo->fetch_array();

        $itemcode = $trainforow['gy_product_code'];
        //get item info
        $getitem=$link->query("Select `gy_product_name` From `gy_products` Where `gy_product_code`='$itemcode'");
        $itemrow=$getitem->fetch_array();


        $oldtotal = $trainforow['gy_trans_total'];
        $qty = $trainforow['gy_trans_quantity'];

        $itemprice = $trainforow['gy_product_price'] * $qty;

        $waitingprice = $oldtotal - $itemprice;

        $my_retail_price = words($_POST['my_retail_price']);
        $my_quantity = words($_POST['my_quantity']);

        $newprice = $my_retail_price * $my_quantity;

        $newtotal = $waitingprice + $newprice;

        //delete to database
        $update_data=$link->query("Update `gy_tra_details` SET `gy_product_price`='$my_retail_price' Where `gy_transdet_id`='$my_dir_value'");

        if ($update_data) {
            //update total 
            $updatetotal=$link->query("Update `gy_tra` SET `gy_trans_total`='$newtotal' Where `gy_trans_code`='$transcode'");

            $my_note_text = "TRA Price Update -> from ".@number_format($trainforow['gy_product_price'],2)." to ".@number_format($my_retail_price,2)." on item ".$itemrow['gy_product_name'];
            my_notify($my_note_text,$user_info);

            echo "
                <script>
                    window.alert('Price Updated ...');
                    window.location.href = '{$_SERVER['HTTP_REFERER']}'
                </script>
            ";
        }else{
            echo "
                <script>
                    window.alert('There is something wrong! Call the Programmer.');
                    window.location.href = '{$_SERVER['HTTP_REFERER']}'
                </script>
            ";
        } 

    }
?>