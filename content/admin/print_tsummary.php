<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_header_tite = "STOCK TRANSFER SUMMARY";

    $my_dir_value = @$_GET['cd'];
    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];
    $my_dir_mode = @$_GET['mode'];
    $my_branch = @$_GET['branch'];
    $branch_id = @$_GET['br_id'];

    $get_branch=$link->query("Select * From `gy_branch` Where `gy_branch_id`='$my_branch'");
    $branch_row=$get_branch->fetch_array();

    if ($my_dir_mode == "entry_search") {

        $my_project_header_title = "STOCK TRANSFER REPORT SEARCH RESULTS: ".$my_dir_value;

        $my_query = "Select * From `gy_stock_transfer` Where `gy_transfer_code` LIKE '%$my_dir_value%' Order By `gy_transfer_date` ASC";
    }else if ($my_dir_mode == "date_search") {

        if($my_branch != ""){

            if ($datef == $datet) {
                $my_project_header_title = "STOCK TRANSFER REPORT: ".date("M d, Y", strtotime($datef))." / ".$branch_row['gy_branch_name'];
            }else{
                $my_project_header_title = "STOCK TRANSFER REPORT: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet))." / ".$branch_row['gy_branch_name'];
            }

            $my_query="Select * From `gy_stock_transfer` Where `gy_branch_id`='$my_branch' AND date(`gy_transfer_date`) BETWEEN '$datef' AND '$datet' Order By `gy_transfer_date` ASC";
        }else{

            if ($datef == $datet) {
                $my_project_header_title = "STOCK TRANSFER REPORT: ".date("M d, Y", strtotime($datef));
            }else{
                $my_project_header_title = "STOCK TRANSFER REPORT: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet));
            }

            $my_query="Select * From `gy_stock_transfer` Where date(`gy_transfer_date`) BETWEEN '$datef' AND '$datet' Order By `gy_transfer_date` ASC";
        }

    }else if ($my_dir_mode == "trans") {

        $my_project_header_title = "STOCK TRANSFER REPORT CODE: ".$my_dir_value;

        $my_query = "Select * From `gy_stock_transfer` Where `gy_transfer_code`='$my_dir_value' Order By `gy_transfer_date` ASC";
    }else if ($my_dir_mode == "" && $my_dir_value == "") {

        $my_project_header_title = "STOCK TRANSFER REPORT";

        $my_query = "Select * From `gy_stock_transfer` Order By `gy_transfer_date` ASC";
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
                            <?php 
                            if ($my_dir_mode == "trans") {
                                //value here ...
                            
                            ?>
                            <a href="stock_transfer_counter?br_id=<?= $branch_id; ?>&"><button type="button" class="btn btn-success" title="click to back to stock-transfer counter ..."><i class="fa fa-chevron-circle-left fa-fw"></i> Back</button></a>
                            <?php 
                                }else{
                                    //empty
                                } 
                            ?>
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

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <?php 
                                    if ($my_dir_mode == "trans") {
                                        //value here ...
                                    
                                    ?>
                                    <tr>
                                        <th colspan="7"># <?php echo $my_dir_value; ?></th>
                                    </tr>
                                    <tr>
                                        <th><center>Date</center></th>
                                        <th><center>Qty</center></th>
                                        <th><center>Unit</center></th>
                                        <th style="color: blue;"><center>Description</center></th>
                                        <th style="color: green;"><center>From</center></th>
                                        <th><center>Transfer To</center></th>
                                        <th><center>Remarks</center></th>
                                    </tr>
                                    <?php  
                                        }else{
                                    ?>
                                    <tr>
                                        <th><center>Date</center></th>
                                        <th><center>#</center></th>
                                        <th><center>Qty</center></th>
                                        <th><center>Unit</center></th>
                                        <th style="color: blue;"><center>Description</center></th>
                                        <th style="color: green;"><center>From</center></th>
                                        <th><center>Transfer To</center></th>
                                        <th><center>Remarks</center></th>
                                    </tr>
                                    <?php  
                                        }
                                    ?>
                                </thead>
                                <tbody>

                                    <?php
                                        //vars
                                        $numrow = 0;
                                        // Select ordered items
                                        $sql_item_detail=$link->query($my_query);

                                        while ($transfer_row=$sql_item_detail->fetch_array()) {
                                            @$numrow++; 

                                            //get product details
                                            $my_code = words($transfer_row['gy_product_id']);
                                            $get_details=$link->query("Select * From `gy_products` Where `gy_product_id`='$my_code'");
                                            $details_row=$get_details->fetch_array();

                                            //get user info
                                            $my_user = words($transfer_row['gy_transfer_by']);
                                            $get_my_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user'");
                                            $my_user_row=$get_my_user_info->fetch_array();

                                            //get branch
                                            $my_branch = words($transfer_row['gy_branch_id']);
                                            $get_branch=$link->query("Select * From `gy_branch` Where `gy_branch_id`='$my_branch'");
                                            $branch_row=$get_branch->fetch_array();

                                            @$total_amount += $transfer_row['gy_product_price_cap'] * $transfer_row['gy_transfer_quantity'];        
                                    ?>    


                                    <?php 
                                    if ($my_dir_mode == "trans") {
                                        //value here ...
                                    
                                    ?>              
                                    <tr>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-2"><center><?php echo date("M d g:i A", strtotime($transfer_row['gy_transfer_date'])); ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-1"><center><?php echo "<span style='color: blue;'>".$transfer_row['gy_transfer_quantity']."</span>"; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-1"><?php echo $details_row['gy_product_unit']; ?></td>
                                        <td style="font-weight: bold; color: blue; padding: 4px;" class="col-sm-2"><center><?php echo $transfer_row['gy_product_name']; ?></center></td>
                                        <td style="font-weight: bold; color: green; padding: 4px;" class="col-sm-2"><center><?php echo get_branch_name($transfer_row['gy_branch_from']); ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-1"><center><?php echo $branch_row['gy_branch_name']; ?></center></td>
                                        <td class="col-md-2"></td>
                                    </tr>
                                    <?php  
                                        }else{
                                    ?>          
                                    <tr>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-2"><center><?php echo date("M d g:i A", strtotime($transfer_row['gy_transfer_date'])); ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-1"><center><?php echo $transfer_row['gy_transfer_code']; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-1"><center><?php echo "<span style='color: blue;'>".$transfer_row['gy_transfer_quantity']."</span>"; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-1"><?php echo $details_row['gy_product_unit']; ?></td>
                                        <td style="font-weight: bold; color: blue; padding: 4px;" class="col-sm-2"><center><?php echo $transfer_row['gy_product_name']; ?></center></td>
                                        <td style="font-weight: bold; color: green; padding: 4px;" class="col-sm-2"><center><?php echo get_branch_name($transfer_row['gy_branch_from']); ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;" class="col-sm-1"><center><?php echo $branch_row['gy_branch_name']; ?></center></td>
                                        <td class="col-md-2"></td>
                                    </tr>
                                                
                                <?php 
                                        } 
                                    }
                                ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <p>Issued By: _________________________________</p>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <p>Approved By: _______________________________</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <p>DR No.: ____________________________________</p>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <p>Delivered By: _______________________________</p>
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