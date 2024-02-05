<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_project_header_title = "Duplicate Product Descriptions";
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
                                <span style="font-size: 13px;">Date Printed: <?php echo date("F d, Y g:i:s A"); ?></span><br><br>
                                </center>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped table-bordered table-hover">
                                <?php  
                                    $getsame=$link->query("SELECT `gy_product_name`, COUNT(*) c FROM `gy_products` GROUP BY `gy_product_name` HAVING c > 1");
                                    $counts=$getsame->num_rows;

                                    /* SELECT a.gy_product_code,a.gy_product_name,a.gy_product_price_cap,a.gy_product_price_srp,a.gy_product_quantity,a.gy_product_unit FROM gy_products a INNER JOIN gy_products b On a.gy_product_name = b.gy_product_name Where a.gy_product_id <> b.gy_product_id Order By a.gy_product_name ASC*/
                                ?>
                                <thead>
                                    <tr>
                                        <th colspan="3" style="color: red;"><center>DUPLICATE NAMES -> <?php echo 0+$counts; ?></center></th>
                                    </tr>
                                    <tr>
                                        <th><center>No.</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Duplicates</center></th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php  
                                        //view all duplicate product names
                                        $num=0;
                                        while ($samerow=$getsame->fetch_array()) {
                                            $num++;
                                    ?>
                                    <tr>
                                        <td style="padding: 1px;"><center><?php echo $num; ?></center></td>
                                        <td style="padding: 1px;"><center><?php echo $samerow['gy_product_name']; ?></center></td>
                                        <td style="padding: 1px;"><b><center><?php echo $samerow['c']; ?></center></b></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-striped table-bordered table-hover">
                                <?php  
                                    $getupdates=$link->query("SELECT `gy_product_code`,`gy_product_name`,`gy_update_code`, COUNT(*) c FROM `gy_products` GROUP BY `gy_update_code` HAVING c > 1");
                                    $upcounts=$getupdates->num_rows;

                                    /*SELECT a.gy_product_code,a.gy_product_name,a.gy_product_price_cap,a.gy_product_price_srp,a.gy_product_quantity,a.gy_product_unit,a.gy_update_code FROM gy_products a INNER JOIN gy_products b On a.gy_update_code = b.gy_update_code Where a.gy_product_id <> b.gy_product_id Order By a.gy_product_name ASC*/
                                ?>
                                <thead>

                                    <tr>
                                        <th colspan="4" style="color: red;"><center>DUPLICATE UPDATE CODES -> <?php echo 0+$upcounts; ?></center></th>
                                    <tr>
                                        <th><center>Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Update Code</center></th>
                                        <th><center>Duplicates</center></th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php  
                                        //view all duplicate product names
                                        while ($updaterow=$getupdates->fetch_array()) {
                                    ?>
                                    <tr>
                                        <td style="padding: 1px;"><center><?php echo $updaterow['gy_product_code']; ?></center></td>
                                        <td style="padding: 1px;"><center><?php echo $updaterow['gy_product_name']; ?></center></td>
                                        <td style="padding: 1px;"><center><?php echo $updaterow['gy_update_code']; ?></center></td>
                                        <td style="padding: 1px;"><b><center><?php echo $updaterow['c']; ?></center></b></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <p>Issued By: __________________________________</p>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <p>Approved By: ___________________________________</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <p>DR No.: ____________________________________</p>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <p>Delivered By: ___________________________________</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>