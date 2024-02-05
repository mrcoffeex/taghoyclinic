
    <?php

        include("../../conf/conn.php");
        include("session.php");


        $notifications=$link->query("Select * From `gy_notification` Order By `gy_notif_id` DESC LIMiT 10");

        while($note=$notifications->fetch_array()){

          echo    
          '<a class="list-group-item">
                <i class="fa fa-comment fa-fw btn-success"></i>&nbsp;'.$note["gy_notif_text"].' at <span style="color: green;">'.date("g:i:s A", strtotime($note['gy_notif_date'])).'</span>
                <span class="pull-right text-muted small"><em>'.date("M. d, Y", strtotime($note['gy_notif_date'])).'</em>
                </span>
            </a>';
        }

    ?>

                       