<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    $redirect = @$_GET['cd'];
    $getpatient=$link->query("Select * From `gy_patient` Where `gy_pat_id`='$redirect'");
    $pat_row=$getpatient->fetch_array();

    if (isset($_POST['fname'])) {
    	$fname = words($_POST['fname']);
    	$lname = words($_POST['lname']);
    	$mname = words($_POST['mname']);
    	$birthdate = words($_POST['birthdate']);
    	$gender = words($_POST['gender']);
    	$address = words($_POST['address']);
    	$contact = words($_POST['contact']);

    	$fullname = $fname." ".$lname;

    	//insert here ...
    	$update_data=$link->query("UPDATE `gy_patient` SET `gy_pat_fname`='$fname', `gy_pat_lname`='$lname', `gy_pat_mname`='$mname', `gy_pat_fullname`='$fullname', `gy_pat_birthdate`='$birthdate', `gy_pat_gender`='$gender', `gy_pat_address`='$address', `gy_pat_contact`='$contact' Where `gy_pat_id`='$redirect'");

    	if ($update_data) {

            $my_a = compare_update($pat_row['gy_pat_fullname'] , $fullname , "Name");
            $my_b = compare_update($pat_row['gy_pat_birthdate'] , $birthdate , "Birthdate");
            $my_c = compare_update($pat_row['gy_pat_gender'] , $gender , "Gender");
            $my_d = compare_update($pat_row['gy_pat_address'] , $address , "Address");
            $my_e = compare_update($pat_row['gy_pat_contact'] , $contact , "Contact #");

            $note_text = $pat_row['gy_pat_fullname']." Patient Update -> ".$my_a."".$my_b."".$my_c."".$my_d."".$my_e;
            my_notify($note_text,$user_info);

            header("location: view_patient?cd=$redirect&note=nice_update");
    	}else{
    		header("location: patient?cd=$redirect&note=error");
    	}
    }
?>