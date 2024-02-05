<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    $my_dir_value=$_GET['cd'];

    function cardcentmultiplier($orig,$pers){
        $getmutiplier = $pers / 100;
        $midprocess = $orig * $getmutiplier;
        $judgement = $orig + $midprocess;

        return floor($judgement);
    }
    
    //add member
    if (isset($_POST['product_search'])) {
        //elements
        $product_search = words($_POST['product_search']);

        $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$product_search' AND `gy_branch_id`='$user_branch_id'");
        $product_row=$get_product_info->fetch_array();
        $count_item=$get_product_info->num_rows;

        $my_trans=words($my_dir_value);
        $my_code=words($product_row['gy_product_id']);
        $origprice=words($product_row['gy_product_price_srp']);

        if ($pay_mode == 2) {
            $my_price=words(cardcentmultiplier($product_row['gy_product_price_srp'],$multips));
        }else{
            $my_price=words($product_row['gy_product_price_srp']);
        }

        $my_date=words(date("Y-m-d H:i:s"));

        if ($count_item < 1) {
            header("location: cashier?note=not_found&cd=$my_trans");
        }else{

            if ($product_row['gy_product_quantity'] < 1) {
                header("location: cashier?note=out_stock&cd=$my_trans");
            }else{

                //check if duplicate
                $duplicate_check=$link->query("Select * From `gy_trans_details` Where `gy_product_id`='$my_code' AND `gy_trans_code`='$my_trans'");
                $count_duplicate=$duplicate_check->num_rows;

                if ($count_duplicate > 0) {
                    header("location: cashier?note=duplicate&cd=$my_trans");
                }else{
                    //insert to database
                    $insert_data=$link->query("Insert Into `gy_trans_details`(`gy_trans_code`,`gy_transdet_date`, `gy_product_id`, `gy_product_price`, `gy_product_origprice`, `gy_product_discount`, `gy_trans_quantity`,`gy_trans_ref_rep_quantity`, `gy_transdet_type`) Values('$my_trans','$my_date','$my_code','$my_price','$origprice','0','1','1','0')");

                        if ($insert_data) {    
                            header("location: cashier?note=nice&cd=$my_trans");
                                    
                        }else{
                            header("location: cashier?note=error&cd=$my_trans");
                        }
                }  

            }
             
        }
    }
?>