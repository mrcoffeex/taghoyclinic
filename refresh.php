
    <?php

        include("conf/conn.php");

        $notifications=$link->query("Select * From `gy_notification` Order By `gy_notif_id` DESC LIMiT 7");

        while($note=$notifications->fetch_array()){

            echo    
                '<a class="list-group-item" style="min-height: 60px;">
                    <span style="color: #5a422d;">'.date("Md, Y g:i A", strtotime($note['gy_notif_date'])).'</span> -> '.$note["gy_notif_text"].'
                </a>';
        }

    ?>

                       