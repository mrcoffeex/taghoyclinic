<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = $_GET['cd'];
    $branch_id = $_GET['br_id'];

    //add member
    if (isset($_POST['product_search'])) {
        //elements
        $product_search = words($_POST['product_search']);
        $date_now = date("Y-m-d H:i:s");

        $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$product_search' AND `gy_branch_id`='$branch_id'");
        $product_row=$get_product_info->fetch_array();
        $count_results=$get_product_info->num_rows;

        $my_product_id=words($product_row['gy_product_id']);
        $my_product_code=words($product_row['gy_product_code']);
        $my_product_name=words($product_row['gy_product_name']);
        $my_product_price_cap=words($product_row['gy_product_price_cap']);
        $my_product_price_srp=words($product_row['gy_product_price_srp']);
        $my_old_date=words($product_row['gy_product_date_restock']);

        if ($count_results <= 0) {
            header("location: restock_counter?br_id=$branch_id&note=not_found");
        }else{

            //check if the code using by another user
            $check_user_code=$link->query("SElect * From `gy_restock` Where `gy_restock_code`='$my_dir_value' AND `gy_restock_by` != '$user_id'");
            $count_user_code=$check_user_code->num_rows;

            if ($count_user_code > 0) {
                //get the latest free trans code
                $get_latest_trans=$link->query("Select * From `gy_restock` Order By `gy_restock_code` DESC LIMIT 1");
                $trans_row=$get_latest_trans->fetch_array();

                if ($trans_row['gy_restock_code'] == 0) {
                    $my_trans_code = "1001";
                }else{
                    $my_trans_code = $trans_row['gy_restock_code'] + 1;
                }
            }else{
                $my_trans_code = $my_dir_value;
            }
            
            //check if duplicate
            $duplicate_check=$link->query("Select * From `gy_restock` Where `gy_product_id`='$my_product_id' AND `gy_restock_code`='$my_trans_code' AND `gy_restock_by`='$user_id'");
            $count_duplicate=$duplicate_check->num_rows;

            if ($count_duplicate > 0) {
                header("location: restock_counter?br_id=$branch_id&note=duplicate");
            }else{
                //insert to database
                $insert_data=$link->query("INSERT Into `gy_restock`(`gy_restock_code`,`gy_product_id`, `gy_product_name`, `gy_product_old_price`, `gy_product_price_cap`, `gy_product_old_srp`, `gy_product_price_srp`, `gy_old_price_date`, `gy_supplier_code`, `gy_restock_note`, `gy_restock_quantity`, `gy_restock_date`, `gy_restock_status`, `gy_restock_by`, `gy_branch_id`) Values('$my_trans_code','$my_product_id','$my_product_name','$my_product_price_cap','$my_product_price_cap','$my_product_price_srp','$my_product_price_srp','$my_old_date','0','','0','$date_now', 0,'$user_id','$branch_id')");

                if ($insert_data) {
                    header("location: restock_counter?br_id=$branch_id&note=nice");

                }else{
                    header("location: restock_counter?br_id=$branch_id&note=error&this");
                }
            }
        } 
    }
?>