<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value=$_GET['cd'];
    $acc=$_GET['acc'];

    $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$acc'");
    $accountrow=$getaccount->fetch_array();

    //account name
    $accountname=$accountrow['gy_acc_name'];
    $deposit=words($accountrow['gy_acc_deposit']);

    $getdepositdata=$link->query("Select * From `gy_deposit` Where `gy_dep_id`='$my_dir_value'");
    $deprow=$getdepositdata->fetch_array();

    //account code
    $amount=words($deprow['gy_dep_amount']);
    $account=words($deprow['gy_acc_id']);
    
    //add member
    if (isset($_POST['my_secure_pin'])) {
    	$my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'void_tra');

    	//get secure pin
    	$get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='void_tra' AND `gy_user_id`='$approved_info'");
    	$get_values=$get_secure_pin->fetch_array();

    	if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            if ($deposit <= 0) {
                //recover deposit
                $removedep=$link->query("Update `gy_accounts` SET `gy_acc_deposit`='0' Where `gy_acc_id`='$account'");
            }else{
               //recover deposit
                $removedep=$link->query("Update `gy_accounts` SET `gy_acc_deposit`=`gy_acc_deposit` - '$amount' Where `gy_acc_id`='$account'"); 
            }

            if ($removedep) {
                //delete query
                $deletedep=$link->query("Delete From `gy_deposit` Where `gy_dep_id`='$my_dir_value'");

                if ($deletedep) {
                    $my_note_text = "VOID DEPOSIT approved by ".$approved_by." -> ".$accountname." Amount: ".@number_format(0 + $amount,2);
                    my_notify($my_note_text,$user_info);
                    echo "
                        <script>
                            window.alert('Deposit has been removed ...');
                            window.location.href = '{$_SERVER['HTTP_REFERER']}'
                        </script>
                    ";
                }else{
                    echo "
                        <script>
                            window.alert('There is something wrong!');
                            window.location.href = '{$_SERVER['HTTP_REFERER']}'
                        </script>
                    ";
                }
            }else{
                echo "
                    <script>
                        window.alert('There is something wrong!');
                        window.location.href = '{$_SERVER['HTTP_REFERER']}'
                    </script>
                ";
            }
            
        }else{
            echo "
                <script>
                    window.alert('Password Mismatch!');
                    window.location.href = '{$_SERVER['HTTP_REFERER']}'
                </script>
            ";
        }
    }
?>