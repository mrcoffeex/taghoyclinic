<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];

    //update profile data
    if (isset($_POST['update_profile'])) {
    	//vars
    	$my_acc_name = words($_POST['my_acc_name']);
    	$my_prof_username = words($_POST['my_prof_username']);

    	$update_data=$link->query("Update `gy_user` SET `gy_full_name`='$my_acc_name',`gy_username`='$my_prof_username' Where `gy_user_id`='$my_dir_value'");

    	if ($update_data) {
    		$my_note_text = "User Data Updated!";
            my_notify($my_note_text,$user_info);
            header("location: ".$_SERVER["HTTP_REFERER"]);
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