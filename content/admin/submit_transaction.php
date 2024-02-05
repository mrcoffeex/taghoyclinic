<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //submit to cashier
    
    if (isset($_POST['my_trans_code'])) {
        //elements
        $my_code = words($_POST['my_trans_code']);
        $my_prepared_By = words($_POST['my_prepared_by']);
        $my_salesman = words($_POST['my_salesman']);
        $mycashier = words($_POST['mycashier']);
        $my_acc_code = words($_POST['my_acc_code']);
        $my_note = words($_POST['my_note']);
        $date_now = date("Y-m-d H:i:s");

        //get account info
        $getaccounts=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_acc_code'");
        $accountsrow=$getaccounts->fetch_array();

        $my_cust_name = words($accountsrow['gy_acc_name']);
        $mytotaldeposit = $accountsrow['gy_acc_deposit'];

        //vars
        $total="";
        $srp_total="";

        $get_trans_dets=$link->query("Select * From `gy_tra_details` LEFT JOIN `gy_products` On `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$my_code'");
        $count_items=$get_trans_dets->num_rows;

        if ($count_items == 0) {

            header("location: tra_counter?note=empty");

        }else{
            while ($trans_dets_row=$get_trans_dets->fetch_array()) {

                $detcode=words($trans_dets_row['gy_transdet_id']);
                $my_quantity = $trans_dets_row['gy_trans_quantity'];
                $my_product_code = $trans_dets_row['gy_product_code'];

                //update bodega claim quantity
                $udpate_bodega=$link->query("Update `gy_tra_details` SET `gy_trans_claim_quantity`='$my_quantity' Where `gy_transdet_id`='$detcode'");

                $my_subtotal = $trans_dets_row['gy_product_price'] * $trans_dets_row['gy_trans_quantity'];

                $total += $my_subtotal;

                $srp_total += $trans_dets_row['gy_product_price_srp'] * $trans_dets_row['gy_trans_quantity'];

                //update product quantity
                $udpate_products=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` - '$my_quantity' Where `gy_product_code`='$my_product_code'");
            }

            //values
            $total_discount = $srp_total - $total;

            //insert to database
            $update_data=$link->query("Update `gy_tra` SET `gy_acc_id`='$my_acc_code', `gy_trans_custname`='$my_cust_name', `gy_trans_date`='$date_now', `gy_trans_type`='1', `gy_trans_total`='$total', `gy_trans_discount`='$total_discount', `gy_trans_status`='1', `gy_salesman`='$my_salesman', `gy_user_id`='$user_id', `gy_tra_note`='$my_note' Where `gy_trans_code`='$my_code'");

            if ($update_data) {
                //get total deposit
                if ($mytotaldeposit > 0) {
                    //subtract deposit by the total

                    $depdiff = $mytotaldeposit - $total;

                    if ($depdiff <= 0) {
                        $finaldepdiff = 0;
                        $totaldepositpay = $mytotaldeposit;
                    }else{
                        $finaldepdiff = $depdiff;
                        $totaldepositpay = $total;
                    }

                    $newtranscode = words(latest_code("gy_transaction", "gy_trans_code", "10001"));

                    //update depositpay
                    $updatedepositpay=$link->query("UPDATE `gy_tra` SET `gy_trans_cash`=`gy_trans_cash` + '$totaldepositpay' Where `gy_trans_code`='$my_code'"); 

                    $insertdepositpay=$link->query("INSERT INTO `gy_transaction`( `gy_trans_code`, `gy_trans_pay`, `gy_trans_check_per`, `gy_trans_check_num`, `gy_trans_royal_fee`, `gy_trans_cardcent`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_depositpay`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_tra_code`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`) Values ('$newtranscode', 1, 0, '', 0, 0, '$my_cust_name', '$date_now', 1, 0, 0, 0, '$totaldepositpay', 0, '$my_salesman', '$mycashier', '$my_code', 1, 1, '$date_now')");
                    $insertdepositpaydetails=$link->query("INSERT INTO `gy_trans_details`(`gy_trans_code`, `gy_transdet_date`, `gy_product_code`, `gy_product_price`, `gy_product_origprice`, `gy_product_discount`, `gy_trans_quantity`, `gy_trans_ref_rep_quantity`, `gy_trans_claim_quantity`, `gy_transdet_type`, `gy_check_status`) VALUES('$newtranscode', '$date_now', 'TRA_FEE', 0, 0, 0, 1, 0, 1, 1, 1)");

                    $updateaccdeposit=$link->query("UPDATE `gy_accounts` SET `gy_acc_deposit`='$finaldepdiff' Where `gy_acc_id`='$my_acc_code'");

                    if ($updatedepositpay && $insertdepositpay && $insertdepositpaydetails && $updateaccdeposit) {
                        header("location: print_receipt_thermal?cd=$my_code");
                    }else{
                        header("location: tra_counter?note=error");
                    }

                }else{
                    header("location: print_receipt_thermal?cd=$my_code");
                }
                        
            }else{
                header("location: tra_counter?note=error");
            }
        }

        
    }
?>