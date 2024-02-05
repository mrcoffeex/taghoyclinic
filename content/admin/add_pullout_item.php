<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = $_GET['cd'];
    $branch_id = @$_GET['br_id'];

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

        if ($count_results <= 0) {
            header("location: pullout_counter?br_id=$branch_id&note=not_found");
        }else{

            //check if the code using by another user
            $check_user_code=$link->query("SElect * From `gy_pullout` Where `gy_pullout_code`='$my_dir_value' AND `gy_pullout_by` != '$user_id'");
            $count_user_code=$check_user_code->num_rows;

            if ($count_user_code > 0) {
                //get the latest free trans code
                $get_latest_trans=$link->query("Select * From `gy_pullout` Order By `gy_pullout_code` DESC LIMIT 1");
                $trans_row=$get_latest_trans->fetch_array();

                if ($trans_row['gy_pullout_code'] == 0) {
                    $my_trans_code = "1001";
                }else{
                    $my_trans_code = $trans_row['gy_pullout_code'] + 1;
                }
            }else{
                $my_trans_code = $my_dir_value;
            }

            //check if duplicate
            $duplicate_check=$link->query("Select * From `gy_pullout` Where `gy_product_id`='$my_product_id' AND `gy_pullout_code`='$my_trans_code' AND `gy_pullout_by`='$user_id'");
            $count_duplicate=$duplicate_check->num_rows;

            if ($count_duplicate > 0) {
                header("location: pullout_counter?br_id=$branch_id&note=duplicate");
            }else{
                //insert to database
                $insert_data=$link->query("Insert Into `gy_pullout`(`gy_pullout_code`,`gy_product_id`, `gy_product_name`, `gy_pullout_type`, `gy_pullout_note`, `gy_pullout_quantity`, `gy_pullout_date`, `gy_pullout_status`, `gy_backorder_status`, `gy_pullout_by`, `gy_branch_id`) Values('$my_trans_code','$my_product_id','$my_product_name','','', 0,'$date_now', 0, 0,'$user_id','$branch_id')");

                if ($insert_data) {
                    header("location: pullout_counter?br_id=$branch_id&note=nice");

                }else{
                    header("location: pullout_counter?br_id=$branch_id&note=error");
                }
            }
        } 
    }
?>