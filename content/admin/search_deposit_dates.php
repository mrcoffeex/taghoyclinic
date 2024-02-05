<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];

    if ($datef == $datet) {
        $my_project_header_title = "Search Deposit Items: ".date("M d, Y", strtotime($datef));
    }else{
        $my_project_header_title = "Search Deposit Items: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet));
    }

    $date_now = words(date("Y-m-d"));

    $my_query=$link->query("Select `gy_trans_details`.`gy_product_code`,`gy_trans_details`.`gy_trans_quantity`,`gy_transaction`.`gy_trans_code`,`gy_transaction`.`gy_trans_date`,`gy_transaction`.`gy_trans_custname` From `gy_trans_details` LEFT JOIN `gy_transaction` On `gy_trans_details`.`gy_trans_code`=`gy_transaction`.`gy_trans_code` Where `gy_transaction`.`gy_trans_check`='0' AND `gy_transaction`.`gy_user_id`!='0' AND `gy_transaction`.`gy_trans_status`='1' AND `gy_transaction`.`gy_trans_type`='1' AND `gy_transaction`.`gy_trans_date` BETWEEN '$datef' AND '$datet'");

    $count_results=$my_query->num_rows;

    $query_one=$link->query("Select `gy_trans_details`.`gy_product_code`,`gy_trans_details`.`gy_trans_quantity`,`gy_transaction`.`gy_trans_code`,`gy_transaction`.`gy_trans_date`,`gy_transaction`.`gy_trans_custname` From `gy_trans_details` LEFT JOIN `gy_transaction` On `gy_trans_details`.`gy_trans_code`=`gy_transaction`.`gy_trans_code` Where `gy_transaction`.`gy_trans_check`='0' AND `gy_transaction`.`gy_user_id`!='0' AND `gy_transaction`.`gy_trans_status`='1' AND `gy_transaction`.`gy_trans_type`='1' AND `gy_transaction`.`gy_trans_date` BETWEEN '$datef' AND '$datet' Order By `gy_transaction`.`gy_trans_date` DESC");

    $tra_query=$link->query("Select `gy_tra_details`.`gy_product_code`,`gy_tra_details`.`gy_trans_quantity`,`gy_tra`.`gy_trans_code`,`gy_tra`.`gy_trans_date`,`gy_tra`.`gy_trans_custname` From `gy_tra_details` LEFT JOIN `gy_tra` On `gy_tra_details`.`gy_trans_code`=`gy_tra`.`gy_trans_code` Where `gy_tra`.`gy_trans_check`='0' AND `gy_tra`.`gy_user_id`!='0' AND `gy_tra`.`gy_trans_status`='1' AND `gy_tra`.`gy_trans_type`='1' AND `gy_tra`.`gy_trans_date` BETWEEN '$datef' AND '$datet' Order By `gy_tra`.`gy_trans_date` DESC");

    $tra_results=$tra_query->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <?php include('nav.php');?>

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?><!--  - <small>refresh results in <span style="color: blue;" id="countdown"></span> second(s)</small> --></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="redirect_manager">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="dep_from" class="form-control" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="dep_to" class="form-control" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="dep_submit" class="btn btn-success" title="click to search by date ..."><i class="fa fa-search"></i> Search</button>
                            </div>
                        </form>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Item Data Table <b><?php echo $count_results + $tra_results; ?> result(s)</b>
                            <span style="float: right;"> Press <button type="button" onclick="location.reload();" class="btn btn-primary btn-xs" title="click to relaod page ...">F5</button> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>(Dep + Act) Qty</center></th>
                                            <th><center>TransCode</center></th>
                                            <th><center>Date Purchased</center></th>
                                            <th><center>Customer</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php

                                        //make pagination
                                        while ($item_row=$tra_query->fetch_array()) {

                                            //get item info
                                            $mypcode = words($item_row['gy_product_code']);
                                            $get_itemInfo=$link->query("Select * From `gy_products` Where `gy_product_code`='$mypcode'");
                                            $itemInfo_row=$get_itemInfo->fetch_array();

                                            $act_qty = $item_row['gy_trans_quantity'] + $itemInfo_row['gy_product_quantity'];

                                    ?>

                                        <tr>
                                            <td style="font-weight: bold; color: #078477; font-size: 18px;"><center><?php echo $item_row['gy_product_code']; ?></center></td>
                                            <td><center><?php echo $itemInfo_row['gy_product_name']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo "<span style='color: blue;'>".$item_row['gy_trans_quantity']."</span> + ".$itemInfo_row['gy_product_quantity']." = ".$act_qty." ".$itemInfo_row['gy_product_unit']; ?></center></td>
                                            <td style="font-weight: bold; color: #078477; font-size: 18px;"><center><?php echo $item_row['gy_trans_code']; ?></center></td>
                                            <td><center><?php echo date("F d, Y g:i:s A",strtotime($item_row['gy_trans_date'])); ?></center></td>
                                            <td><center><?php echo $item_row['gy_trans_custname']; ?></center></td>
                                        </tr>

                                    <?php } ?>

                                    <?php

                                        //make pagination
                                        while ($item_row=$query_one->fetch_array()) {

                                            //get item info
                                            $mypcode = words($item_row['gy_product_code']);
                                            $get_itemInfo=$link->query("Select * From `gy_products` Where `gy_product_code`='$mypcode'");
                                            $itemInfo_row=$get_itemInfo->fetch_array();

                                            $act_qty = $item_row['gy_trans_quantity'] + $itemInfo_row['gy_product_quantity'];

                                    ?>

                                        <tr>
                                            <td style="font-weight: bold; color: #078477; font-size: 18px;"><center><?php echo $item_row['gy_product_code']; ?></center></td>
                                            <td><center><?php echo $itemInfo_row['gy_product_name']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo "<span style='color: blue;'>".$item_row['gy_trans_quantity']."</span> + ".$itemInfo_row['gy_product_quantity']." = ".$act_qty." ".$itemInfo_row['gy_product_unit']; ?></center></td>
                                            <td style="font-weight: bold; color: #078477; font-size: 18px;"><center><?php echo $item_row['gy_trans_code']; ?></center></td>
                                            <td><center><?php echo date("F d, Y g:i:s A",strtotime($item_row['gy_trans_date'])); ?></center></td>
                                            <td><center><?php echo $item_row['gy_trans_custname']; ?></center></td>
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
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">
        // (function countdown(remaining) {
        //     if(remaining <= 0)
        //         location.reload(true);
        //     document.getElementById('countdown').innerHTML = remaining;
        //     setTimeout(function(){ countdown(remaining - 1); }, 1000);
        // })(60); // 60 seconds
    </script>

</body>

</html>
