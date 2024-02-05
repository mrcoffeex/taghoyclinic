<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_dir_value = @$_GET['cd'];
    $encoder = @$_GET['encoder'];

    $my_project_header_title="REQUEST ORDER # ".$my_dir_value;
    $my_query="Select * From `gy_rqt` Where `gy_rqt_code`='$my_dir_value' Order By `gy_product_name` ASC";

    $getnote=$link->query("Select `gy_rqt_note`,`gy_rqt_branch` From `gy_rqt` Where `gy_rqt_code`='$my_dir_value' Order By `gy_product_name` ASC");
    $noterow=$getnote->fetch_array();
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
                            <a href="request_counter"><button type="button" class="btn btn-success" title="click to back to re-stock counter ..."><i class="fa fa-chevron-circle-left fa-fw"></i> Back</button></a>
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
                                        <th colspan="7"># <?php echo $my_dir_value; ?> - Request To: <?php echo $noterow['gy_rqt_branch']; ?> / <i><small>Note: <?php echo $noterow['gy_rqt_note'];; ?></small></i></th>
                                    </tr>
                                    <tr>
                                        <th><center>No.</center></th>              
                                        <th><center>Prod. Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Quantity</center></th>
                                        <th><center>P. release</center></th>
                                        <th><center>T. release</center></th>
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

                                            //get product info
                                            $my_product_code=$row_item_detail['gy_product_code'];
                                            $get_product_info=$link->query("Select `gy_product_unit` From `gy_products` Where `gy_product_code`='$my_product_code'");
                                            $product_row=$get_product_info->fetch_array();           
                                    ?>                 
                                    <tr>
                                        <td class="pla" style="padding: 4px;"><?php echo $numrow; ?></td>
                                        <td class="pla" style="text-transform: uppercase; padding: 4px;"><?php echo $row_item_detail['gy_product_code']; ?></td>
                                        <td class="pla" style="text-transform: uppercase; font-weight: bold; font-size: 12px; padding: 4px;"><?php echo $row_item_detail['gy_product_name']; ?></td>
                                        <td class="pla" style="padding: 4px; font-weight: bold; font-size: 15px;"><?php echo $row_item_detail['gy_rqt_quantity']." <span style='color: black; font-weight: normal;'>".$product_row['gy_product_unit']."</span>"; ?></td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;">&nbsp;</td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;">&nbsp;</td>
                                    </tr>
                                <?php  
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-4">
                            <p>Encoded By: <?php echo $encoder; ?></p>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <p>Checked By: _______________</p>
                        </div>
                        <div class="col-md-4 col-xs-4">
                            <p>Approved By: _______________</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>