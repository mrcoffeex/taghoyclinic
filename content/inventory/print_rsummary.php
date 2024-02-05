<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_dir_value = @$_GET['cd'];
    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];
    $my_dir_mode = @$_GET['mode'];
    $branch_id = @$_GET['br_id'];

    if ($my_dir_mode == "entry_search") {

        $my_project_header_title = "STOCK RECEIVE REPORT SEARCH RESULTS: ".$my_dir_value;

        $my_query = "Select * From `gy_restock` Where `gy_restock_status`='1' AND CONCAT(`gy_supplier_code`,`gy_restock_code`) Like '%$my_dir_value%' Order By `gy_restock_date` ASC";
    }else if ($my_dir_mode == "date_search") {

        if ($datef == $datet) {
            $my_project_header_title = "Stock Receive Reports: ".date("M d, Y", strtotime($datef));
        }else{
            $my_project_header_title = "Stock Receive Reports: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet));
        }

        $my_query = "Select * From `gy_restock` Where `gy_restock_status`='1' AND date(`gy_restock_date`) BETWEEN '$datef' AND '$datet' Order By `gy_restock_date` ASC";
    }else if ($my_dir_mode == "trans") {

        $my_project_header_title = "STOCK RECEIVE REPORT CODE: ".$my_dir_value;

        $my_query = "Select * From `gy_restock` Where `gy_restock_status`='1' AND `gy_restock_code`='$my_dir_value' Order By `gy_restock_date` ASC";
    }else if ($my_dir_mode == "" && $my_dir_value == "") {

        $my_project_header_title = "STOCK RECEIVE REPORT";

        $my_query = "Select * From `gy_restock` Where `gy_restock_status`='1' Order By `gy_restock_date` ASC";
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
                            <a href="restock_counter?br_id=<?= $branch_id; ?>"><button type="button" class="btn btn-success" title="click to back to re-stock counter ..."><i class="fa fa-chevron-circle-left fa-fw"></i> Back</button></a>
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
                                <span style="font-size: 13px;">Date Printed: <?php echo date("F d, Y g:i:s A"); ?></span>
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
                                    ?>
                                    <tr>
                                        <th colspan="8"># <?php echo $my_dir_value; ?></th>
                                    </tr>
                                    <tr>
                                        <th><center>No.</center></th>                     
                                        <th><center>Date</center></th>               
                                        <th><center>Prod. Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Supplier</center></th>
                                        <th><center>Quantity</center></th>
                                        <th><center>Price Change</center></th>
                                        <th><center>User</center></th>
                                        <th><center>Branch</center></th> 
                                    </tr>
                                    <?php  
                                        }else{
                                    ?>
                                    <tr>
                                        <th><center>No.</center></th>                     
                                        <th><center>Date</center></th>                   
                                        <th><center>Code</center></th>                   
                                        <th><center>Prod. Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Supplier</center></th>
                                        <th><center>Quantity</center></th>
                                        <th><center>Price Change</center></th>
                                        <th><center>User</center></th> 
                                        <th><center>Branch</center></th> 
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

                                        while ($row_item_detail=$sql_item_detail->fetch_array()) {
                                            @$numrow++; 

                                            //get user info
                                            $cashier_identifier=$row_item_detail['gy_restock_by'];
                                            $get_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$cashier_identifier'");
                                            $user_info_row=$get_user_info->fetch_array();

                                            //get product info
                                            $my_product_id=$row_item_detail['gy_product_id'];
                                            $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_id`='$my_product_id'");
                                            $product_row=$get_product_info->fetch_array();

                                            if ($row_item_detail['gy_supplier_code'] == 0) {
                                                $my_supplier_data = "NO DATA";
                                                $my_supplier_color = "red";
                                            }else{
                                                $my_supplier_data = $row_item_detail['gy_supplier_name'];
                                                $my_supplier_color = "green";
                                            }          

                                            if ($row_item_detail['gy_product_price_cap'] <= $row_item_detail['gy_product_old_price']) {
                                                $my_price_color = "green";
                                            }else{
                                                $my_price_color = "red";
                                            }  

                                            if ($row_item_detail['gy_product_price_srp'] <= $row_item_detail['gy_product_old_srp']) {
                                                $my_srp_color = "green";
                                            }else{
                                                $my_srp_color = "red";
                                            }            
                                    ?>


                                    <?php  
                                        if ($my_dir_mode == "trans") {
                                    ?>                  
                                    <tr>
                                        <td class="pla" style="padding: 4px;"><?php echo $numrow; ?></td>
                                        <td class="pla" style="padding: 4px;"><?php echo date("M d, Y g:i A", strtotime($row_item_detail['gy_restock_date'])); ?></td>
                                        <td class="pla" style="text-transform: uppercase; padding: 4px;"><?php echo $product_row['gy_product_code']; ?></td>
                                        <td class="pla" style="text-transform: uppercase; font-size: 9px; padding: 4px;"><?php echo $row_item_detail['gy_product_name']; ?></td>
                                        <td class="pla" style="color: <?php echo $my_supplier_color; ?>; font-size: 9px; padding: 4px;"><?php echo $my_supplier_data; ?></td>
                                        <td class="pla" style="padding: 4px;"><?php echo $row_item_detail['gy_restock_quantity']." <span style='color: black;'>".$product_row['gy_product_unit']."</span>"; ?></td>
                                        <td class="pla" style="padding: 4px;"><?php echo "SRP <br><b>".number_format($row_item_detail['gy_product_old_srp'],2)." - <span style='color: $my_price_color;'>".number_format($row_item_detail['gy_product_price_srp'],2)."</span></b>"; ?></td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;"><?php echo $user_info_row['gy_full_name']; ?></td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;"><?php echo get_branch_name($row_item_detail['gy_branch_id']); ?></td>
                                    </tr>
                                    <?php  
                                        }else{
                                    ?>             
                                    <tr>
                                        <td class="pla" style="padding: 4px;"><?php echo $numrow; ?></td>
                                        <td class="pla" style="padding: 4px;"><?php echo date("M d, Y g:i A", strtotime($row_item_detail['gy_restock_date'])); ?></td>
                                        <td class="pla" style="padding: 4px;"><?php echo $row_item_detail['gy_restock_code']; ?></td>
                                        <td class="pla" style="text-transform: uppercase; padding: 4px;"><?php echo $product_row['gy_product_code']; ?></td>
                                        <td class="pla" style="text-transform: uppercase; font-size: 9px; padding: 4px;"><?php echo $row_item_detail['gy_product_name']; ?></td>
                                        <td class="pla" style="color: <?php echo $my_supplier_color; ?>; font-size: 9px; padding: 4px;"><?php echo $my_supplier_data; ?></td>
                                        <td class="pla" style="padding: 4px;"><?php echo $row_item_detail['gy_restock_quantity']." <span style='color: black;'>".$product_row['gy_product_unit']."</span>"; ?></td>
                                        <td class="pla" style="padding: 4px;"><?php echo "SRP <br><b>".number_format($row_item_detail['gy_product_old_srp'],2)." - <span style='color: $my_price_color;'>".number_format($row_item_detail['gy_product_price_srp'],2)."</span></b>"; ?></td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;"><?php echo $user_info_row['gy_full_name']; ?></td>
                                        <td class="pla" style="font-weight: bold; padding: 4px;"><?php echo get_branch_name($row_item_detail['gy_branch_id']); ?></td>
                                    </tr>
                                <?php  
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php 
                        if ($my_dir_mode == "trans") {
                            //value here ...
                        
                     ?>
                    <div class="col-md-6">
                        <!-- <p>Approved By: _____________________________</p> -->
                    </div>
                    <?php 
                        }else{
                            //empty
                        } 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>