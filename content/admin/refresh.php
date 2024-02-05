
    <?php

        include("../../conf/conn.php");
        include("session.php");


        $notifications=$link->query("Select * From `gy_notification` Order By `gy_notif_id` DESC LIMiT 10");

        while($note=$notifications->fetch_array()){

          echo    
                '<a class="list-group-item" style="min-height: 40px; color: #fff; background-color: rgba(85, 85, 85, 1);">
                    <span style="color: rgba(0, 183, 255, 1);">'.date("Md, Y g:i A", strtotime($note['gy_notif_date'])).'</span> -> '.$note["gy_notif_text"].'
                </a>';
        }

    ?>

                       