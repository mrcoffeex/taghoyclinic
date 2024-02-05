<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = $_GET['cd'];

    //add member
    if (isset($_POST['product_search'])) {
        //elements
        $product_search = words($_POST['product_search']);
        $date_now = date("Y-m-d H:i:s");

        $get_product_info=$link->query("Select `gy_product_code`,`gy_product_name`,`gy_supplier_code`,`gy_product_price_srp`,`gy_product_price_cap`,`gy_product_date_restock` From `gy_products` Where `gy_product_code`='$product_search'");
        $product_row=$get_product_info->fetch_array();
        $count_results=$get_product_info->num_rows;

        $my_product_code=words($product_row['gy_product_code']);
        $my_product_name=words($product_row['gy_product_name']);
        $my_supplier_code=words($product_row['gy_supplier_code']);
        $gy_product_price_srp=words($product_row['gy_product_price_srp']);
        $my_product_price_cap=words($product_row['gy_product_price_cap']);
        $my_old_date=words($product_row['gy_product_date_restock']);

        //supplier details
        $getsupplier=$link->query("SElect `gy_supplier_name` From `gy_supplier` Where `gy_supplier_code`='$my_supplier_code'");
        $supprow=$getsupplier->fetch_array();

        $mysupplier=words($supprow['gy_supplier_name']);

        if ($count_results <= 0) {
            header("location: request_counter?note=not_found");
        }else{

            //check if the code using by another user
            $check_user_code=$link->query("SElect `gy_rqt_id` From `gy_rqt` Where `gy_rqt_code`='$my_dir_value' AND `gy_rqt_by` != '$user_id'");
            $count_user_code=$check_user_code->num_rows;

            if ($count_user_code > 0) {
                //get the latest free trans code
                $get_latest_trans=$link->query("Select `gy_rqt_code` From `gy_rqt` Order By `gy_rqt_code` DESC LIMIT 1");
                $trans_row=$get_latest_trans->fetch_array();

                if ($trans_row['gy_rqt_code'] == 0) {
                    $my_trans_code = "10000001";
                }else{
                    $my_trans_code = $trans_row['gy_rqt_code'] + 1;
                }
            }else{
                $my_trans_code = $my_dir_value;
            }
            
            //check if duplicate
            $duplicate_check=$link->query("Select * From `gy_rqt` Where `gy_product_code`='$my_product_code' AND `gy_rqt_code`='$my_trans_code' AND `gy_rqt_by`='$user_id'");
            $count_duplicate=$duplicate_check->num_rows;

            if ($count_duplicate > 0) {
                header("location: request_counter?note=duplicate");
            }else{
                //insert to database
                $insert_data=$link->query("Insert Into `gy_rqt`(`gy_rqt_code`,`gy_product_code`, `gy_product_name`, `gy_product_price_srp`, `gy_product_price_cap`, `gy_supplier_code`, `gy_supplier_name`, `gy_rqt_note`, `gy_rqt_quantity`, `gy_rqt_date`, `gy_rqt_status`, `gy_rqt_by`) Values('$my_trans_code','$my_product_code','$my_product_name','$my_product_price_srp','$my_product_price_cap','$my_supplier_code','$mysupplier','','0','$date_now', 0,'$user_id')");

                if ($insert_data) {
                    header("location: request_counter?note=nice");

                }else{
                    header("location: request_counter?note=error");
                }
            }
        } 
    }
?>