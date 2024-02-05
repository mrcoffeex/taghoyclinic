<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_dir_value = @$_GET['cd'];
    $custom_var = $my_dir_value;

    $getitemdetails=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_dir_value'");
    $itemdetailsrow=$getitemdetails->fetch_array();

    $itemname = $itemdetailsrow['gy_product_name'];
    $itemunit = $itemdetailsrow['gy_product_unit'];

    $my_project_header_title = $itemname." - Sold History";

    $query_one = "SELECT * From `gy_trans_details` Where `gy_product_code`='$my_dir_value' Order By `gy_transdet_date` DESC";

    $query_two = "SELECT COUNT(`gy_transdet_id`) FROM `gy_trans_details` Where `gy_product_code`='$my_dir_value' Order By `gy_transdet_date` DESC";

    $query_three = "SELECT * From `gy_trans_details` Where `gy_product_code`='$my_dir_value' Order By `gy_transdet_date` DESC ";

    $my_num_rows = 25;

    include 'my_pagination_custom.php';

    $countres=$link->query($query_one)->num_rows;
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
            font-size: 14px;
        }
    </style>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <p style="font-size: 17px;">
                               <center>
                                <span style="font-size: 20px; font-weight: bold;"><i class="fa fa-dropbox"></i> <?php echo $itemname; ?><br>
                                <small><?php echo $countres; ?> result(s)</small></span><br>
                                </center>
                            </p>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th><center>Date</center></th>
                                        <th><center>Qty</center></th>
                                        <th style="color: green;"><center>Customer</center></th>
                                        <th><center>Salesman</center></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        //vars  
                                        // Select ordered items

                                        while ($log_row=$query->fetch_array()) {
                                            //get transaction info
                                            $mytranscode=words($log_row['gy_trans_code']);
                                            $gettransinfo=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$mytranscode'");
                                            $transinforow=$gettransinfo->fetch_array();

                                            //get salesman
                                            $mysalesmandata=words($transinforow['gy_prepared_by']);
                                            $getsalesman=$link->query("Select * From `gy_user` Where `gy_user_id`='$mysalesmandata'");
                                            $salesmanrow=$getsalesman->fetch_array();
                                    ?>                  
                                    <tr class="<?php echo $rowcolor; ?>">
                                        <td style="font-weight: bold;"><center><?php echo date("F d, Y - g:i:s A", strtotime($log_row['gy_transdet_date'])); ?></center></td>
                                        <td style="font-weight: bold;"><center><?php echo $log_row['gy_trans_quantity']." ".$itemunit; ?></center></td>
                                        <td style="font-weight: bold; color: blue;"><center><?php echo $transinforow['gy_trans_custname']; ?></center></td>
                                        <td style="font-weight: bold;"><center><?php echo $salesmanrow['gy_full_name']; ?></center></td>
                                    </tr>
                                                
                                <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center"> 
                         <ul class="pagination">
                            <?php echo $paginationCtrls; ?>
                         </ul>
                    </div>
                 </div>
            </div>

        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>