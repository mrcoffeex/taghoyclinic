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

        $my_project_header_title = "PULL-OUT REPORT SEARCH RESULTS: ".$my_dir_value;

        $my_query = "Select * From `gy_pullout` Where `gy_pullout_code` LIKE '%$my_dir_value%' AND `gy_pullout_type`!='BACK_ORDER' AND `gy_pullout_status`='1' Order By `gy_pullout_date` ASC";
    }else if ($my_dir_mode == "date_search") {

        if ($datef == $datet) {
            $my_project_header_title = "PULL-OUT REPORT: ".date("M d, Y", strtotime($datef));
        }else{
            $my_project_header_title = "PULL-OUT REPORT: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet));
        }

        $my_query = "Select * From `gy_pullout` Where `gy_pullout_status`='1' AND `gy_pullout_type`!='BACK_ORDER' AND date(`gy_pullout_date`) BETWEEN '$datef' AND '$datet' Order By `gy_pullout_date` ASC";
    }else if ($my_dir_mode == "trans") {

        $my_project_header_title = "PULL-OUT REPORT CODE: ".$my_dir_value;

        $my_query = "Select * From `gy_pullout` Where `gy_pullout_status`='1' AND `gy_pullout_code`='$my_dir_value' Order By `gy_pullout_date` ASC";
    }else if ($my_dir_mode == "" && $my_dir_value == "") {

        $my_project_header_title = "PULL-OUT REPORT";

        $my_query = "Select * From `gy_pullout` Where `gy_pullout_type`!='BACK_ORDER' AND `gy_pullout_status`='1' Order By `gy_pullout_date` ASC";
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
                            <a href="pullout_counter?br_id=<?= $branch_id; ?>"><button type="button" class="btn btn-success" title="click to back to pull-out counter ..."><i class="fa fa-chevron-circle-left fa-fw"></i> Back</button></a>
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
                                        <th colspan="9"># <?php echo $my_dir_value; ?></th>
                                    </tr>
                                    <tr>
                                        <th><center>No.</center></th>                     
                                        <th><center>Date</center></th>                  
                                        <th><center>Type</center></th>                  
                                        <th><center>Prod. Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Quantity</center></th>
                                        <th><center>Note</center></th>
                                        <th><center>User</center></th> 
                                        <th><center>Branch</center></th>
                                    </tr>
                                    <?php  
                                        }else{
                                    ?>
                                    <tr>
                                        <th><center>No.</center></th>                     
                                        <th><center>Date</center></th>                   
                                        <th><center>W#</center></th>                    
                                        <th><center>Type</center></th>                  
                                        <th><center>Prod. Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Quantity</center></th>
                                        <th><center>Note</center></th>
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

                                        while ($pull_row=$sql_item_detail->fetch_array()) {
                                            @$numrow++; 

                                            //get product details
                                            $my_code = words($pull_row['gy_product_id']);
                                            $get_details=$link->query("Select * From `gy_products` Where `gy_product_id`='$my_code'");
                                            $details_row=$get_details->fetch_array();

                                            //get user info
                                            $my_user = words($pull_row['gy_pullout_by']);
                                            $get_my_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user'");
                                            $my_user_row=$get_my_user_info->fetch_array();

                                            if ($pull_row['gy_pullout_type'] == "FOR_USE") {
                                                $my_type_pull = "FOR USE";
                                                $my_color = "success";
                                            }else if ($pull_row['gy_pullout_type'] == "SOLD") {
                                                $my_type_pull = "SOLD";
                                                $my_color = "info";
                                            }else if ($pull_row['gy_pullout_type'] == "DAMAGE") {
                                                $my_type_pull = "DAMAGE ITEM";
                                                $my_color = "danger";
                                            }else if ($pull_row['gy_pullout_type'] == "TRA") {
                                                $my_type_pull = "TRA";
                                                $my_color = "warning";
                                            }else if ($pull_row['gy_pullout_type'] == "EXPIRED") {
                                                $my_type_pull = "EXPIRED";
                                                $my_color = "danger";
                                            }else{
                                                $my_type_pull = "UNKNOWN";
                                                $my_color = "default";
                                            }          
                                    ?> 

                                    <?php  
                                        if ($my_dir_mode == "trans") {
                                    ?>                 
                                    <tr>
                                        <td class="pla" style="padding: 4px;"><?php echo $numrow; ?></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo date("M d g:i A",strtotime($pull_row['gy_pullout_date'])); ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo $my_type_pull; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo $details_row['gy_product_code']; ?></center></td>
                                        <td style="text-transform: uppercase; font-size: 9px; padding: 4px;"><center><?php echo $pull_row['gy_product_name']; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo "<span style='color: blue;'>".$pull_row['gy_pullout_quantity']."</span> ".$details_row['gy_product_unit']; ?></center></td>
                                        <td style="text-transform: uppercase; font-size: 9px; padding: 4px;"><center><?php echo $pull_row['gy_pullout_note']; ?></center></td>
                                        <td style="padding: 4px;"><center><?php echo $my_user_row['gy_full_name']; ?></center></td>
                                        <td style="padding: 4px;"><center><?php echo get_branch_name($pull_row['gy_branch_id']); ?></center></td>
                                    </tr>

                                    <?php  
                                        }else{
                                    ?>

                                    <tr>
                                        <td class="pla" style="padding: 4px;"><?php echo $numrow; ?></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo date("M d g:i A",strtotime($pull_row['gy_pullout_date'])); ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo $pull_row['gy_pullout_code']; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo $my_type_pull; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo $details_row['gy_product_code']; ?></center></td>
                                        <td style="text-transform: uppercase; font-size: 9px; padding: 4px;"><center><?php echo $pull_row['gy_product_name']; ?></center></td>
                                        <td style="font-weight: bold; padding: 4px;"><center><?php echo "<span style='color: blue;'>".$pull_row['gy_pullout_quantity']."</span> ".$details_row['gy_product_unit']; ?></center></td>
                                        <td style="text-transform: uppercase; font-size: 9px; padding: 4px;"><center><?php echo $pull_row['gy_pullout_note']; ?></center></td>
                                        <td style="padding: 4px;"><center><?php echo $my_user_row['gy_full_name']; ?></center></td>
                                        <td style="padding: 4px;"><center><?php echo get_branch_name($pull_row['gy_branch_id']); ?></center></td>
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
                        <p>Approved By: _____________________________</p>
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