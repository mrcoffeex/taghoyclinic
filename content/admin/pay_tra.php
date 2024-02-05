<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];

    $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_dir_value'");
    $accountrow=$getaccount->fetch_array();

    $account=words($accountrow['gy_acc_code']);

    //get the transaction info

    //update profile data
    if (isset($_POST['mypayment'])) {
        //vars
        $payment = words($_POST['mypayment']);
        $my_method = words($_POST['my_method']);
        @$my_check_percentage = words($_POST['my_check_percentage']);
        @$my_check_num = words($_POST['my_check_num']);
        @$mycheqamount = words($_POST['mycheqamount']);
        $myroyalfee = words($_POST['myroyalfee']);
        $mytotal = words($_POST['mytotal']);
        $mytranschange = words($_POST['mychange']);
        $mycashier = words($_POST['mycashier']);

        //get cash payment

        @$mychange = $mycheqamount - $payment;

        //insert interest to database
        if ($my_method == 0) {
            //cash
            while ($payment > 0) {

                $getinfo=$link->query("SELECT `gy_trans_id`,`gy_trans_code`,`gy_trans_total`,`gy_trans_cash`,`gy_trans_interest`,`gy_salesman`,`gy_trans_custname` From `gy_tra` Where `gy_trans_total`+`gy_trans_interest` > `gy_trans_cash` AND `gy_acc_id`='$my_dir_value' ORDER BY `gy_trans_code` ASC");
                $inforow=$getinfo->fetch_array();

                $myid = words($inforow['gy_trans_id']);
                $mytracode = words($inforow['gy_trans_code']);
                $mysalesman = words($inforow['gy_salesman']);
                $custname = words($inforow['gy_trans_custname']);
                $date_now = words(date("Y-m-d H:i:s"));

                $totals = $inforow['gy_trans_total'] + $inforow['gy_trans_interest'];
                $mybal = $totals - $inforow['gy_trans_cash'];

                //get latest transcode
                $get_latest_trans=$link->query("Select * From `gy_transaction` Order By `gy_trans_code` DESC LIMIT 1");
                $trans_row=$get_latest_trans->fetch_array();

                if ($trans_row['gy_trans_code'] == 0) {
                    $mytranscode = "1000001";
                }else{
                    $mytranscode = $trans_row['gy_trans_code'] + 1;
                }

                if ($payment > $mybal) {
                    $inserttotalpay = $mybal;
                    $insertpay = $mybal;
                }else{
                    $inserttotalpay = $payment;
                    $insertpay = $payment;
                }

                $insert_data=$link->query("INSERT INTO `gy_transaction`(`gy_trans_code`, `gy_trans_pay`, `gy_trans_check_per`, `gy_trans_check_num`, `gy_trans_royal_fee`, `gy_trans_cardcent`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_depositpay`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_tra_code`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`) Values('$mytranscode','$my_method','0','','0','0','$custname','$date_now','1','$insertpay','0','$insertpay','0','0','$mysalesman','$mycashier','$mytracode','1','1','$date_now')");

                //insert trans details
                $insert_data_details=$link->query("INSERT INTO `gy_trans_details`(`gy_trans_code`, `gy_transdet_date`, `gy_product_code`, `gy_product_price`, `gy_product_origprice`, `gy_product_discount`, `gy_trans_quantity`, `gy_trans_ref_rep_quantity`, `gy_trans_claim_quantity`, `gy_transdet_type`, `gy_check_status`) Values('$mytranscode','$date_now','TRA_FEE','$insertpay','$insertpay','0','1','0','0','1','1')");

                $update_data=$link->query("Update `gy_tra` SET `gy_trans_cash`=`gy_trans_cash`+'$inserttotalpay' Where `gy_trans_id`='$myid'");

                //check if balance is zero
                $total_balance=0;
                $getfullbalance=$link->query("Select `gy_trans_interest`,`gy_trans_total`,`gy_trans_cash` From `gy_tra` Where `gy_acc_id`='$my_dir_value'");
                while($fullbalancerow=$getfullbalance->fetch_array()){

                    @$total_balance += ($fullbalancerow['gy_trans_interest'] + $fullbalancerow['gy_trans_total']) - $fullbalancerow['gy_trans_cash'];
                }

                if ($total_balance > 0) {

                    $payment = $payment - $insertpay;

                }else{

                    $payment = 0;

                }
                
            }

                // $update_deposit=$link->query("Update `gy_accounts` SET `gy_acc_deposit`=`gy_acc_deposit`+'$mytranschange' Where `gy_acc_id`='$my_dir_value'");

                $my_note_text = "Cash Transaction ID ".$mytranscode." is sold";

                $dir_value = $payment;

            
        }else if ($my_method == 1) {
            //cheque
            while ($payment > 0) {

                $getinfo=$link->query("SELECT `gy_trans_id`,`gy_trans_code`,`gy_trans_total`,`gy_trans_cash`,`gy_trans_interest`,`gy_salesman`,`gy_trans_custname` From `gy_tra` Where `gy_trans_total`+`gy_trans_interest` > `gy_trans_cash` AND `gy_acc_id`='$my_dir_value' ORDER BY `gy_trans_code` ASC");
                $inforow=$getinfo->fetch_array();

                    $myid = words($inforow['gy_trans_id']);
                    $mytracode = words($inforow['gy_trans_code']);
                    $mysalesman = words($inforow['gy_salesman']);
                    $custname = words($inforow['gy_trans_custname']);
                    $date_now = words(date("Y-m-d H:i:s"));

                    $totals = $inforow['gy_trans_total'] + $inforow['gy_trans_interest'];
                    $mybal = $totals - $inforow['gy_trans_cash'];

                    //get latest transcode
                    $get_latest_trans=$link->query("Select * From `gy_transaction` Order By `gy_trans_code` DESC LIMIT 1");
                    $trans_row=$get_latest_trans->fetch_array();

                    if ($trans_row['gy_trans_code'] == 0) {
                        $mytranscode = "1000001";
                    }else{
                        $mytranscode = $trans_row['gy_trans_code'] + 1;
                    }

                    
                    if ($payment > $mybal) {
                        $inserttotalpay = $mybal;
                        $insertpay = $mybal;
                    }else{
                        $inserttotalpay = $payment;
                        $insertpay = $payment;
                    }

                $insert_data=$link->query("INSERT INTO `gy_transaction`(`gy_trans_code`, `gy_trans_pay`, `gy_trans_check_per`, `gy_trans_check_num`, `gy_trans_royal_fee`, `gy_trans_cardcent`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_depositpay`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_tra_code`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`) Values('$mytranscode','$my_method','$my_check_percentage','$my_check_num','$myroyalfee','0','$custname','$date_now','1','$insertpay','0','$mycheqamount','0','$mychange','$mysalesman','$mycashier','$mytracode','1','1','$date_now')");

                //insert trans details
                $insert_data_details=$link->query("INSERT INTO `gy_trans_details`(`gy_trans_code`, `gy_transdet_date`, `gy_product_code`, `gy_product_price`, `gy_product_origprice`, `gy_product_discount`, `gy_trans_quantity`, `gy_trans_ref_rep_quantity`, `gy_trans_claim_quantity`, `gy_transdet_type`, `gy_check_status`) Values('$mytranscode','$date_now','TRA_FEE','$insertpay','$insertpay','0','1','0','0','1','1')");

                $update_data=$link->query("Update `gy_tra` SET `gy_trans_cash`=`gy_trans_cash`+'$inserttotalpay' Where `gy_trans_id`='$myid'");

                //check if balance is zero
                $total_balance=0;
                $getfullbalance=$link->query("Select `gy_trans_interest`,`gy_trans_total`,`gy_trans_cash` From `gy_tra` Where `gy_acc_id`='$my_dir_value'");
                while($fullbalancerow=$getfullbalance->fetch_array()){

                    @$total_balance += ($fullbalancerow['gy_trans_interest'] + $fullbalancerow['gy_trans_total']) - $fullbalancerow['gy_trans_cash'];
                }

                if ($total_balance > 0) {

                    $payment = $payment - $insertpay;

                }else{

                    $payment = 0;

                }

            }

            // $update_deposit=$link->query("Update `gy_accounts` SET `gy_acc_deposit`=`gy_acc_deposit`+'$mytranschange' Where `gy_acc_id`='$my_dir_value'");

            $my_note_text = "Cheque Transaction ID ".$mytranscode." is sold";

            $dir_value = $payment;

        }else{
            //card
            while ($payment > 0) {

                $getinfo=$link->query("SELECT `gy_trans_id`,`gy_trans_code`,`gy_trans_total`,`gy_trans_cash`,`gy_trans_interest`,`gy_salesman`,`gy_trans_custname` From `gy_tra` Where `gy_trans_total`+`gy_trans_interest` > `gy_trans_cash` AND `gy_acc_id`='$my_dir_value' ORDER BY `gy_trans_code` ASC");
                $inforow=$getinfo->fetch_array();

                $myid = words($inforow['gy_trans_id']);
                $mytracode = words($inforow['gy_trans_code']);
                $mysalesman = words($inforow['gy_salesman']);
                $custname = words($inforow['gy_trans_custname']);
                $date_now = words(date("Y-m-d H:i:s"));

                $totals = $inforow['gy_trans_total'] + $inforow['gy_trans_interest'];
                $mybal = $totals - $inforow['gy_trans_cash'];

                //get latest transcode
                $get_latest_trans=$link->query("Select * From `gy_transaction` Order By `gy_trans_code` DESC LIMIT 1");
                $trans_row=$get_latest_trans->fetch_array();

                if ($trans_row['gy_trans_code'] == 0) {
                    $mytranscode = "1000001";
                }else{
                    $mytranscode = $trans_row['gy_trans_code'] + 1;
                }

                if ($payment > $mybal) {
                    $inserttotalpay = $mybal;
                    $insertpay = $mybal;
                }else{
                    $inserttotalpay = $payment;
                    $insertpay = $payment;
                }

                $insert_data=$link->query("INSERT INTO `gy_transaction`(`gy_trans_code`, `gy_trans_pay`, `gy_trans_check_per`, `gy_trans_check_num`, `gy_trans_royal_fee`, `gy_trans_cardcent`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_depositpay`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_tra_code`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`) Values('$mytranscode','$my_method','0','','0','0','$custname','$date_now','1','$insertpay','0','$insertpay','0','0','$mysalesman','$mycashier','$mytracode','1','1','$date_now')");

                //insert trans details
                $insert_data_details=$link->query("INSERT INTO `gy_trans_details`(`gy_trans_code`, `gy_transdet_date`, `gy_product_code`, `gy_product_price`, `gy_product_origprice`, `gy_product_discount`, `gy_trans_quantity`, `gy_trans_ref_rep_quantity`, `gy_trans_claim_quantity`, `gy_transdet_type`, `gy_check_status`) Values('$mytranscode','$date_now','TRA_FEE','$insertpay','$insertpay','0','1','0','0','1','1')");

                $update_data=$link->query("Update `gy_tra` SET `gy_trans_cash`=`gy_trans_cash`+'$inserttotalpay' Where `gy_trans_id`='$myid'");

                //check if balance is zero
                $total_balance=0;
                $getfullbalance=$link->query("Select `gy_trans_interest`,`gy_trans_total`,`gy_trans_cash` From `gy_tra` Where `gy_acc_id`='$my_dir_value'");
                while($fullbalancerow=$getfullbalance->fetch_array()){

                    @$total_balance += ($fullbalancerow['gy_trans_interest'] + $fullbalancerow['gy_trans_total']) - $fullbalancerow['gy_trans_cash'];
                }

                if ($total_balance > 0) {

                    $payment = $payment - $insertpay;

                }else{

                    $payment = 0;

                }
            }

            // $update_deposit=$link->query("Update `gy_accounts` SET `gy_acc_deposit`=`gy_acc_deposit`+'$mytranschange' Where `gy_acc_id`='$my_dir_value'");

            $my_note_text = "Card Transaction ID ".$mytranscode." is sold";

            $dir_value = $payment;

        }

        if ($insert_data && $insert_data_details && $update_data) {
            my_notify($my_note_text,$user_info);
            echo "
                <script>
                    window.alert('Payment Added!');
                    window.location.href = 'print_receipt_thermal_pay?cd=$my_dir_value&dir=$dir_value'
                </script>
            ";
        }else{
            echo "
                <script>
                    window.alert('Inserting Error! The Program need a repair ...');
                    window.location.href = '{$_SERVER['HTTP_REFERER']}'
                </script>
            ";
        }
    }
?>