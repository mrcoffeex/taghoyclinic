<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    $redirect = @$_GET['cd'];
    $getpatient=$link->query("Select * From `gy_patient` Where `gy_pat_id`='$redirect'");
    $pat_row=$getpatient->fetch_array();


    $delete_data=$link->query("DELETE FROM `gy_patient` Where `gy_pat_id`='$redirect'");

    if ($delete_data) {
        $my_note_text = $pat_row['gy_pat_fullname']." - removed from patient list";
        my_notify($my_note_text,$user_info);

        echo "
            <script>
                window.close();
            </script>
        ";
    }else{
        header("location: view_patient?note=error");
    }
?>