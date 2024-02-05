<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $getSales=getSalesStats(date("Y-m-d", strtotime(getLatestDate() . "-30 days")), getLatestDate());

    $data = array();
    foreach ($getSales as $row) {
        $data[] = $row;
    }

    echo json_encode($data);

?>