<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $condition = @$_GET['condition'];
    $my_date_from = @$_GET['date_from'];
    $my_date_to = @$_GET['date_to'];

    if ($condition != "") {

        if ($my_date_from == $my_date_to) {
            $date_format = date("F d, Y", strtotime($my_date_from));
        }else{
            $date_format = date("F d - ", strtotime($my_date_from))." ".date("F d, Y", strtotime($my_date_to));
        }

        //title
        $my_title = "Filter - ".$condition." -> Day of ".$date_format;

        //query
        $query_one=$link->query("Select * From `gy_notification` Where `gy_notif_text` LIKE '%$condition%' AND date(`gy_notif_date`) BETWEEN '$my_date_from' AND '$my_date_to' Order By `gy_notif_id` ASC");

    }else{

        //title
        if ($my_date_from == $my_date_to) {
            $date_format = date("F d, Y", strtotime($my_date_from));
        }else{
            $date_format = date("F d - ", strtotime($my_date_from))." ".date("F d, Y", strtotime($my_date_to));
        }

        $my_title = "Day of ".$date_format;

        //query
        $query_one=$link->query("Select * From `gy_notification` Where date(`gy_notif_date`) BETWEEN '$my_date_from' AND '$my_date_to' Order By `gy_notif_id` ASC");

    }

    $count_results=$query_one->num_rows;

    $my_project_header_title = "Notification Search: ".$my_title;

?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>

    <style type="text/css">
        img{
            max-width:180px;
        }

        input[type=file]{
            padding:0px;
        }

        @media print{
            .no-print{
                display: none !important;
            }

            .my_hr{
                height: 5px;
                color: #000;
                background-color: #000;
                border: none;
            }

            td{
                background-color: rgba(255,255,255, 0.1);
            }
        }

        .my_hr{
            height: 5px;
            color: #000;
            background-color: #000;
            border: none;
        }

        td{
            background-color: rgba(255,255,255, 0.1);
            font-size: 12px;
        }
    </style>

    <script type="text/javascript">
        window.print();
    </script>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 no-print">
                            <button type="button" onclick="window.print();" class="btn btn-primary" title="click to print ..."><i class="fa fa-print fa-fw"></i> Print Result</button>
                        </div>
                        <div class="col-md-12">
                            <h4 style="font-weight: bold; margin-bottom: -10px; margin-top: 30px;"><center><?php echo $my_project_name; ?></center>
                            <p style="font-size: 20px;">
                               <center>
                                <span style="font-size: 20px; font-weight: bold;">NOTIFICATION RESULT</span><br>
                                <span style="font-size: 13px;">Date printed: <?php echo date("F d, Y"); ?></span><br><br>
                                </center>
                            </p>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th><center>No.</center></th>
                                        <th><center>Date</center></th>
                                        <th><center>Notification</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //vars
                                        $numrow = 0;
                                        while ($row_item_detail=$query_one->fetch_array()) {
                                            $numrow++;                        
                                    ?>                  
                                    <tr>
                                        <td style="font-weight: bold; padding: 1px;"><center><?php echo $numrow; ?></center></td>
                                        <td style="padding: 1px;"><center><?php echo date("M/d/Y", strtotime($row_item_detail['gy_notif_date'])); ?></center></td>
                                        <td style="padding: 1px;"><?php echo date("g:i A", strtotime($row_item_detail['gy_notif_date'])); ?> - <?php echo $row_item_detail['gy_notif_text']; ?></td>
                                    </tr>
                                                
                                <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>