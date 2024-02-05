<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];

    //get the transaction info
    $gettrans=$link->query("Select * From `gy_tra` Where `gy_trans_id`='$my_dir_value'");
    $transrow=$gettrans->fetch_array();

    $itemtotal = $transrow['gy_trans_total'];
    $oldinterest = $transrow['gy_trans_interest'];
    $oldbalance = floor(($transrow['gy_trans_total'] + $transrow['gy_trans_interest']) - $transrow['gy_trans_cash']); 

    $transidcode = words($transrow['gy_trans_code']);
    $date_now = words(date("Y-m-d H:i:s"));

    //update profile data
    if (isset($_POST['my_balance'])) {
    	//vars
    	$my_balance = words($_POST['my_balance']);
    	$my_interestpercentage = words($_POST['my_interestpercentage']);
        $myint = words($_POST['myint']);
        $mynewbalance = words($_POST['mynewbalance']);

        $initialinterest = $mynewbalance - $oldbalance;
        $updatedinterest = $myint + $oldinterest;

    	$update_data=$link->query("Update `gy_tra` SET `gy_trans_interest`='$updatedinterest' Where `gy_trans_id`='$my_dir_value'");

    	if ($update_data) {
            //insert interest to database
            $insert_data=$link->query("Insert Into `gy_interest`(`gy_int_date`,`gy_trans_code`,`gy_int_value`,`gy_user_id`) Values('$date_now','$transidcode','$myint','$user_id')");
            if (!$insert_data) {
                echo "
                    <script>
                        window.alert('Interest entry error ...');
                        window.location.href = '{$_SERVER['HTTP_REFERER']}'
                    </script>
                ";
            }

    		$my_note_text = "Interest Update -> ".$my_interestpercentage."% of ".$transrow['gy_trans_custname']." Credit Balance update from - ".@number_format($oldbalance,2)." to ".@number_format($mynewbalance,2);
            my_notify($my_note_text,$user_info);
            echo "
                <script>
                    window.alert('Interest Added ...');
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