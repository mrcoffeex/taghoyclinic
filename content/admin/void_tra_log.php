<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=@$_GET['cd'];
    $redirect=@$_GET['dir'];
    $mycode=@$_GET['code'];
    $mymode=@$_GET['mode'];

    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'void_tra');

        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='void_tra' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            if ($mymode == "pay") {
                //get custname
                $gettrans=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
                $trarow=$gettrans->fetch_array();
                $mycustomer = words($trarow['gy_trans_custname']);
                $mycashpaid = words($trarow['gy_trans_total'] + $trarow['gy_trans_depositpay']); 
                $mydeposit = words($trarow['gy_trans_depositpay']); 
                $mytrans = words($trarow['gy_tra_code']);

                //get account
                $gettra=$link->query("Select * From `gy_tra` Where `gy_trans_code`='$mytrans'");
                $trarows=$gettra->fetch_array();

                //account code
                $account=words($trarows['gy_acc_id']);

                //update deposit
                $update_deposit=$link->query("Update `gy_accounts` SET `gy_acc_deposit`=`gy_acc_deposit`+'$mydeposit' Where `gy_acc_id`='$account'");

                //deduct the payment 
                $update_cashpaid=$link->query("Update `gy_tra` SET `gy_trans_cash`=`gy_trans_cash` - '$mycashpaid' Where `gy_trans_code`='$mytrans'");
                if ($update_cashpaid) {

                    //get items
                    $get_items=$link->query("Select * From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");
                    while ($get_items_row=$get_items->fetch_array()) {

                        $my_code = words($get_items_row['gy_trans_code']);
                        $my_date = words($get_items_row['gy_transdet_date']);
                        $my_product_code = words($get_items_row['gy_product_code']);
                        $my_product_price = words($get_items_row['gy_product_price']);
                        $my_product_discount = words($get_items_row['gy_product_discount']);
                        $my_quantity = words($get_items_row['gy_trans_quantity']);
                        $my_type = words($get_items_row['gy_transdet_type']);

                        //recover items
                        $recover_items=$link->query("Update `gy_products` SET `gy_product_quantity`=`gy_product_quantity` + '$my_quantity' Where `gy_product_code`='$my_product_code'");

                        //insert to void details table
                        $trans_void_details=$link->query("Insert Into `gy_void_details`(`gy_trans_code`, `gy_transdet_date`, `gy_product_code`, `gy_product_price`, `gy_product_discount`, `gy_trans_quantity`, `gy_transdet_type`) Values('$my_code','$my_date','$my_product_code','$my_product_price','$my_product_discount','$my_quantity','$my_type')");
                    }

                    //get transaction info
                    $get_trans=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
                    $trans_row=$get_trans->fetch_array();

                    //transaction info
                    $my_trans_code=words($trans_row['gy_trans_code']);
                    $my_trans_pay=words($trans_row['gy_trans_pay']);
                    $my_trans_check_per=words($trans_row['gy_trans_check_per']);
                    $my_trans_check_num=words($trans_row['gy_trans_code']);
                    $my_trans_custname=words($trans_row['gy_trans_custname']);
                    $my_trans_date=words($trans_row['gy_trans_date']);
                    $my_trans_type=words($trans_row['gy_trans_type']);
                    $my_trans_total=words($trans_row['gy_trans_total']);
                    $my_trans_discount=words($trans_row['gy_trans_discount']);
                    $my_trans_cash=words($trans_row['gy_trans_cash']);
                    $my_trans_change=words($trans_row['gy_trans_change']);
                    $my_prepared_by=words($trans_row['gy_prepared_by']);
                    $my_user_id=words($trans_row['gy_user_id']);
                    $my_trans_status=words($trans_row['gy_trans_status']);
                    $my_trans_check=words($trans_row['gy_trans_check']);
                    $my_trans_check_date=words($trans_row['gy_trans_check_date']);

                    //insert to void table
                    $trans_void=$link->query("Insert Into `gy_void`(`gy_trans_code`, `gy_trans_pay`, `gy_trans_check_per`, `gy_trans_check_num`, `gy_trans_custname`, `gy_trans_date`, `gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_trans_status`, `gy_trans_check`, `gy_trans_check_date`, `gy_void_by`) Values('$my_trans_code','$my_trans_pay','$my_trans_check_per','$my_trans_check_num','$my_trans_custname','$my_trans_date','$my_trans_type','$my_trans_total','$my_trans_discount','$my_trans_cash','$my_trans_change','$my_prepared_by','$my_user_id','$my_trans_status','$my_trans_check','$my_trans_check_date','$approved_by')");
                    
                    //delete the transaction
                    $delete_transaction=$link->query("Delete From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
                    $delete_transaction_details=$link->query("Delete From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");

                    if ($delete_transaction && $delete_transaction_details) {
                        $my_note_text = "VOID TRA PAYMENT IN ACCOUNT approved by ".$approved_by." -> ".$mycustomer." Account";
                        my_notify($my_note_text,$user_info);
                        header("location: tra_logs?cd=$redirect");
                    }else{
                        echo "
                            <script>
                                window.alert('System Error!');
                                window.location.href = 'tra_logs?cd=$redirect'
                            </script>
                        ";
                    }
                }else{
                    echo "
                        <script>
                            window.alert('System Error!');
                            window.location.href = 'tra_logs?cd=$redirect'
                        </script>
                    ";
                }
            }else{
                //get custname
                $gettrans=$link->query("Select * From `gy_tra` Where `gy_trans_code`='$my_dir_value'");
                $trarow=$gettrans->fetch_array();
                $mycustomer = words($trarow['gy_trans_custname']);
                //get interest info
                $getint=$link->query("Select * From `gy_interest` Where `gy_int_id`='$mycode'");
                $introw=$getint->fetch_array();
                $intvalue = words($introw['gy_int_value']);
                $transintcode = words($introw['gy_trans_code']);

                //update interest
                $udpate_interest=$link->query("Update `gy_tra` SET `gy_trans_interest`=`gy_trans_interest` - '$intvalue' Where `gy_trans_code`='$transintcode'");

                if ($udpate_interest) {
                    //remove interest
                    $delete_interest=$link->query("Delete From `gy_interest` Where `gy_int_id`='$mycode'");
                    if ($delete_interest) {
                        $my_note_text = "VOID TRA INTEREST IN ACCOUNT approved by ".$approved_by." -> ".$mycustomer." Account";
                        my_notify($my_note_text,$user_info);
                        header("location: tra_logs?cd=$redirect");
                    }else{
                        echo "
                            <script>
                                window.alert('System Error!');
                                window.location.href = 'tra_logs?cd=$redirect'
                            </script>
                        ";
                    }
                }else{
                    echo "
                        <script>
                            window.alert('System Error!');
                            window.location.href = 'tra_logs?cd=$redirect'
                        </script>
                    ";
                }
            }
    		
    	}else{
    		echo "
                    <script>
                        window.alert('Incorrect PIN!');
                        window.location.href = '{$_SERVER['HTTP_REFERER']}'
                    </script>
                ";
    	}
    }
?>