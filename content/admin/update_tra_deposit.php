<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];

    $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_dir_value'");
    $accountrow=$getaccount->fetch_array();

    $curr_deposit = $accountrow['gy_acc_deposit'];
    $accountcode = words($accountrow['gy_acc_id']);
    $date_now = words(date("Y-m-d H:i:s"));

    //update profile data
    if (isset($_POST['mydep'])) {
    	//vars
    	$mydep = words($_POST['mydep']);
        $mydepmethod = words($_POST['mydepmethod']);
        $mydepcashier = words($_POST['mydepcashier']);

        $total_balance=0;
        $getfullbalance=$link->query("Select * From `gy_tra` Where `gy_acc_id`='$my_dir_value'");
        while($fullbalancerow=$getfullbalance->fetch_array()){

            @$total_balance += ($fullbalancerow['gy_trans_interest'] + $fullbalancerow['gy_trans_total']) - $fullbalancerow['gy_trans_cash'];
        }

        if ($total_balance != 0) {

            echo "
                <script>
                    window.alert('WARNING! Balance must be ZERO to add Deposit.');
                    window.location.href = '{$_SERVER['HTTP_REFERER']}'
                </script>
            ";

        }else{

        	$update_data=$link->query("Update `gy_accounts` SET `gy_acc_deposit`=`gy_acc_deposit` + '$mydep' Where `gy_acc_id`='$my_dir_value'");

        	if ($update_data) {

                $insert_data=$link->query("Insert Into `gy_deposit`(`gy_dep_method`, `gy_acc_id`, `gy_dep_amount`, `gy_dep_date`, `gy_dep_by`, `gy_user_id`) Values('$mydepmethod','$accountcode','$mydep','$date_now','$user_id','$mydepcashier')");

                if (!$insert_data) {
                    echo "
                        <script>
                            window.alert('There is something wrong! Call the Programmer.');
                            window.location.href = '{$_SERVER['HTTP_REFERER']}'
                        </script>
                    ";
                }

        		$my_note_text = "Deposit Update -> ".@number_format($curr_deposit,2)." + ".@number_format($mydep,2)." = ".@number_format($curr_deposit + $mydep,2);
                my_notify($my_note_text,$user_info);
                echo "
                    <script>
                        window.alert('Deposit Added ...');
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
    }
?>