<?php 

	session_start([
        'cookie_lifetime' => 86400,
    ]);

	if(!isset($_SESSION['fus_user_id'])){
        header("location: ../../index");
    }else if ($_SESSION['fus_user_type'] != 0) {
        header("location: ../../index");
    }

    $user_id = $_SESSION['fus_user_id'];
    $user_type = $_SESSION['fus_user_type'];

    //find user
    $identify_user=$link->query("Select * From `gy_user` Where `gy_user_id`='$user_id'");
    $row=$identify_user->fetch_array();

    $user_info = $row['gy_full_name'];
    $user_id = $row['gy_user_id'];

    $dateNow = date("Y-m-d");

?>