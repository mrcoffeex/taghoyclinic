<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];
    $my_dir_mode = @$_GET['mode'];
    $date_now = date("Y-m-d");

    if ($my_dir_mode == "normal") {

        $my_project_header_title = "Deleted Items";

        $my_query = "SELECT * From `gy_delete` Where date(`gy_del_date`) = '$date_now' ORDER BY `gy_del_date` ASC";

    }else if ($my_dir_mode == "dates") {

        if ($datef == $datet) {
            $my_project_header_title = "Deleted Items: ".date("M d, Y", strtotime($datef));
        }else{
            $my_project_header_title = "Deleted Items: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet));
        }

        $my_query = "SELECT * From `gy_delete` Where date(`gy_del_date`) BETWEEN '$datef' and '$datet' ORDER BY `gy_del_date` ASC";
    }else{

        $my_project_header_title = "Deleted Items";

        $my_query = "SELECT * From `gy_delete` Where date(`gy_del_date`) = '$date_now' ORDER BY `gy_del_date` ASC LIMIT 0";
    }
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
                                <span style="font-size: 20px; font-weight: bold;"><?php echo $my_project_header_title; ?></span><br>
                                <span style="font-size: 13px;">Date Printed: <?php echo date("F d, Y g:i:s A"); ?></span>
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
                                        <th><center>Date/Time</center></th>
                                        <th><center>Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>User</center></th> 
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        //vars
                                        $numrow = 0;
                                        // Select ordered items
                                        $sql_item_detail=$link->query($my_query);

                                        while ($row_item_detail=$sql_item_detail->fetch_array()) {
                                            @$numrow++;  

                                            //get user info
                                            $thisuser=$row_item_detail['gy_user_id'];
                                            $getuserinfo=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$thisuser'"); 
                                            $userinforow=$getuserinfo->fetch_array();         
                                    ?>

                                    <tr>
                                        <td style="font-size: 13px;"><center><?php echo $numrow; ?></center></td>
                                        <td style="font-size: 13px;"><center><?php echo date("M d, Y - g:i A", strtotime($row_item_detail['gy_del_date'])); ?></center></td>
                                        <td style="font-size: 13px;"><center><?php echo $row_item_detail['gy_product_code']; ?></center></td>
                                        <td style="font-size: 13px;"><center><?php echo $row_item_detail['gy_product_name']; ?></center></td>
                                        <td style="font-size: 13px;"><center><?php echo $userinforow['gy_full_name']; ?></center></td>
                                    </tr>
                                <?php  
                                    }
                                ?>
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