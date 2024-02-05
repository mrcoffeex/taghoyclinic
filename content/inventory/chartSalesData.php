<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    $selected = $_POST['selected'];

    header('Content-Type: application/json');

    if ($selected == 30) {
        $getSales=getSalesStats(date("Y-m-d", strtotime(getLatestDate() . "-30 days")), getLatestDate());
    } else if ($selected == 21) {
        $getSales=getSalesStats(date("Y-m-d", strtotime(getLatestDate() . "-21 days")), getLatestDate());
    } else if ($selected == 14) {
        $getSales=getSalesStats(date("Y-m-d", strtotime(getLatestDate() . "-14 days")), getLatestDate());
    } else if ($selected == 7) {
        $getSales=getSalesStats(date("Y-m-d", strtotime(getLatestDate() . "-7 days")), getLatestDate());
    } else {
        $getSales=getSalesStats(date("Y-m-d", strtotime(getLatestDate() . "-30 days")), getLatestDate());
    }

    $data = array();
    foreach ($getSales as $row) {
        $data[] = $row;
    }

    echo json_encode($data);
?>