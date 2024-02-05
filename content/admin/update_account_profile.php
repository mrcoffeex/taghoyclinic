<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];

    $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_dir_value'");
    $accountrow=$getaccount->fetch_array();

    $accname = $accountrow['gy_acc_name'];
    $acccontact = $accountrow['gy_acc_contact'];
    $accaddress = $accountrow['gy_acc_address'];

    //update profile data
    if (isset($_POST['btn-updateprof'])) {
    	//vars
    	$myaccname = words($_POST['myaccname']);
        $myacccontact = words($_POST['myacccontact']);
        $myaccaddress = words($_POST['myaccaddress']);

    	$update_data=$link->query("Update `gy_accounts` SET `gy_acc_name`='$myaccname', `gy_acc_contact`='$myacccontact', `gy_acc_address`='$myaccaddress' Where `gy_acc_id`='$my_dir_value'");

    	if ($update_data) {
    		$my_note_text = "Account Update -> Name: ".$accname." -> ".$myaccname." Address: ".$accaddress." -> ".$myaccaddress." Contact #: ".$acccontact." -> ".$myacccontact;
            my_notify($my_note_text,$user_info);
            echo "
                <script>
                    window.alert('Account Info Successfully Updated ...');
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