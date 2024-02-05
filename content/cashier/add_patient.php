<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    if (isset($_POST['fname'])) {
    	$fname = words($_POST['fname']);
    	$lname = words($_POST['lname']);
    	$mname = words($_POST['mname']);
    	$birthdate = words($_POST['birthdate']);
    	$gender = words($_POST['gender']);
    	$address = words($_POST['address']);
    	$contact = words($_POST['contact']);

    	$fullname = $fname." ".$lname;
    	$date_now = words(date("Y-m-d H:i:s"));

    	$patcode = latest_code("gy_patient", "gy_pat_code", "1001");

    	//insert here ...
    	$insert_data=$link->query("INSERT INTO `gy_patient`(`gy_pat_code`, `gy_pat_datereg`, `gy_pat_fname`, `gy_pat_mname`, `gy_pat_lname`, `gy_pat_fullname`, `gy_pat_birthdate`, `gy_pat_gender`, `gy_pat_address`, `gy_pat_contact`) values('$patcode', '$date_now', '$fname', '$mname', '$lname', '$fullname', '$birthdate', '$gender', '$address', '$contact')");

    	if ($insert_data) {
    		$my_note_text = $fullname." - added as new patient";
            my_notify($my_note_text,$user_info);

    		header("location: patient?note=nice");
    	}else{
    		header("location: patient?note=error");
    	}
    }
?>