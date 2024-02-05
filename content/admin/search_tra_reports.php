<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $search_text = @$_GET['search_text'];

    if ($search_text == "mrcoffeex_only_space") {
         echo "
            <script>
                window.alert('White Spaces is not allowed!');
                window.location.href = 'tra_accounts'
            </script>
         ";
    }else if ($search_text == "mrcoffeex_only_zero") {
        echo "
            <script>
                window.alert('Only Zero is not allowed!');
                window.location.href = 'tra_accounts'
            </script>
         ";
    }else{

        $my_project_header_title = "Tra Accounts by <span style='color: blue;'>".date("F d, Y", strtotime($search_text))."</span>";

        $my_query=$link->query("Select * From `gy_tra` Where date(`gy_trans_date`)='$search_text' Order By `gy_trans_date` DESC");

        $count_results=$my_query->num_rows;

    }

    $my_notification = @$_GET['note'];

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "TRA account was removed";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "success") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Payment Successful";
    }else if ($my_notification == "int_added") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Interest Added";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <?php include 'nav.php'; ?>

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-8">
                    <h3 class="page-header"><i class="fa fa-credit-card"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
                <div class="col-lg-4">
                    <!-- notification here -->
                    <div class="alert alert-<?php echo @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?php echo @$the_note_status; ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo @$message; ?>.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Pull-Out Code ..." name="tra_entry_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                                    <input type="date" class="form-control" name="tra_date_search" id="tra_date_search" style="border-radius: 0px;" required>
                                </form>
                            </div>
                        </div>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Current Account List <b><?php echo 0+$count_results; ?></b> result(s) <span class="pull-right">Press <span style="color: blue;">F5</span> to refresh result</span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Void</center></th>
                                            <th><center>Date</center></th>
                                            <th><center>Paid</center></th>
                                            <!-- <th style="color: blue;"><center>Code</center></th> -->
                                            <th><center>Customer</center></th>
                                            <th><center>Amount</center></th>
                                            <th><center>Credit Age</center></th>
                                            <th><center>Salesman</center></th>
                                            <th><center>Proc. By</center></th>
                                            <th><center>Log</center></th>
                                            <th><center>Items</center></th>
                                            <th><center>Int.</center></th>
                                            <th><center>Pay</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($tra_row=$my_query->fetch_array()) {
                                            //get user info
                                            $my_user = words($tra_row['gy_salesman']);
                                            $get_my_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user'");
                                            $my_user_row=$get_my_user_info->fetch_array();

                                            $myuser = words($tra_row['gy_prepared_by']);
                                            $getmyuserinfo=$link->query("Select * From `gy_user` Where `gy_user_id`='$myuser'");
                                            $myuserrow=$getmyuserinfo->fetch_array();

                                            //get credit age
                                            $datetime1 = strtotime($tra_row['gy_trans_date']);
                                            $datetime2 = strtotime(date("Y-m-d H:i:s"));

                                            $creditage = get_timeage($datetime1, $datetime2);

                                            if ($tra_row['gy_trans_cash'] >= ($tra_row['gy_trans_total'] + $tra_row['gy_trans_interest'])) {
                                                $my_color = "success";
                                                $my_remarks = "<i class='fa fa-check'></>";
                                                $final_creditage = "PAID";
                                                $processbtnvalue = "disabled";
                                            }else{
                                                $my_color = "danger";
                                                $my_remarks = "<i class='fa fa-times'></>";
                                                $final_creditage = $creditage;
                                                $processbtnvalue = "";
                                            }

                                            // if ($tra_row['gy_trans_cash'] > 0) {
                                            //     $voidbtnvalue = "disabled";
                                            // }else{
                                            //     $voidbtnvalue = "";
                                            // }
                                    ?>

                                        <tr class="<?php echo $my_color; ?>">
                                            <td><center><button type="button" class="btn btn-danger" title="click to void this account ..." data-target="#void_<?php echo $tra_row['gy_trans_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                            <td><center><?php echo date("M d, Y g:i:s A",strtotime($tra_row['gy_trans_date'])); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $my_remarks; ?></center></td>
                                            <!-- <td style="font-weight: bold; color: blue;"><center><?php #echo $tra_row['gy_trans_code']; ?></center></td> -->
                                            <td style="font-weight: bold;"><center><?php echo $tra_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo @number_format(0 + $tra_row['gy_trans_total'],2); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $final_creditage; ?></center></td>
                                            <td><center><?php echo $my_user_row['gy_full_name']; ?></center></td>
                                            <td title="<?php echo 'processed by '.$myuserrow['gy_full_name']; ?>"><center><?php echo $myuserrow['gy_full_name']; ?></center></td>
                                            <td><center><a href="tra_logs?cd=<?php echo $tra_row['gy_trans_code']; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-info" title="click to view payment logs ..."><i class="fa fa-file-text-o fa-fw"></i></button></a></center></td>
                                            <td><center><button type="button" class="btn btn-warning" title="click to view info ..." data-target="#info_<?php echo $tra_row['gy_trans_id']; ?>" data-toggle="modal"><i class="fa fa-dropbox"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-primary" title="click to update interest ..." data-toggle="modal" data-target="#int_<?php echo $tra_row['gy_trans_id']; ?>"><i class="fa fa-arrow-circle-up fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-success" title="click to pay ..." data-toggle="modal" data-target="#pay_<?php echo $tra_row['gy_trans_id']; ?>"><i class="fa fa-money fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Items -->
                                        
                                        <div class="modal fade" id="info_<?php echo $tra_row['gy_trans_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-dropbox"></i> Items</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-warning" style="border-radius: 0px;">
                                                            <div class="panel-heading" style="border-radius: 0px;">
                                                            <b><i class="fa fa-credit-card"></i> <?php echo $tra_row['gy_trans_custname']; ?> Account</b><br>
                                                            </div>
                                                            <div class="panel-body">
                                                                <?php  
                                                                    //get items
                                                                    $itemtotal=0;
                                                                    $transcoder=words($tra_row['gy_trans_code']);
                                                                    $getitems=$link->query("Select * From `gy_tra_details` LEFT JOIN `gy_products` On `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$transcoder' Order By `gy_products`.`gy_product_price_srp` DESC");
                                                                    while ($itemsrow=$getitems->fetch_array()) {
                                                                        @$itemtotal += $itemsrow['gy_product_price'] * $itemsrow['gy_trans_quantity'];
                                                                ?>
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <?php echo "<b>".$itemsrow['gy_trans_quantity']."</b> ".$itemsrow['gy_product_unit']." - <b>".$itemsrow['gy_product_code']."</b><br>- ".$itemsrow['gy_product_name']; ?>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        Php <b><?php echo @number_format(0 + ($itemsrow['gy_product_price'] * $itemsrow['gy_trans_quantity']), 2); ?></b>
                                                                    </div>
                                                                    <hr style="border-style: inset; width: 95%; margin-bottom: 5px;">
                                                                </div>
                                                                <?php } ?>
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <p>&nbsp;</p>
                                                                    </div>
                                                                    <div class="col-md-4" style="color: green;">
                                                                        Php <b><?php echo @number_format(0 + $itemtotal,2); ?></b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Interest -->

                                        <div class="modal fade" id="int_<?php echo $tra_row['gy_trans_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-arrow-circle-up"></i> Interest Section</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-primary" style="border-radius: 0px;">
                                                            <div class="panel-heading" style="border-radius: 0px;">
                                                            <b><i class="fa fa-credit-card"></i> <?php echo $tra_row['gy_trans_custname']; ?> Account</b><br>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <?php  
                                                                        //get transaction info
                                                                        $transentry = words($tra_row['gy_trans_id']);
                                                                        $transpayinfo = words($tra_row['gy_trans_code']);

                                                                        $gettransinfo=$link->query("Select * From `gy_tra` Where `gy_trans_id`='$transentry'");
                                                                        $transinfo=$gettransinfo->fetch_array();

                                                                        //get paylogs
                                                                        $paytotal=0;
                                                                        $sql_item_detail=$link->query("Select * From `gy_transaction` Where `gy_tra_code`='$transpayinfo' Order By `gy_trans_date` DESC");

                                                                        while ($log_row=$sql_item_detail->fetch_array()) {

                                                                            @$paytotal += $log_row['gy_trans_total'];
                                                                        }

                                                                        if ($paytotal < ($transinfo['gy_trans_total'] + $tra_row['gy_trans_interest'])) {
                                                                            $colorlabel = "red";
                                                                        }else{
                                                                            $colorlabel = "green";
                                                                        }
                                                                    ?>
                                                                    <div class="col-md-4 col-xs-6">
                                                                        <p style="font-size: 17px;">
                                                                            Credit Age: 
                                                                            <br>
                                                                            Credit Amount: 
                                                                            <br>
                                                                            Interest: 
                                                                            <br>
                                                                            Amount Paid: 
                                                                            <br>
                                                                            Balance: 
                                                                            <br>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-8 col-xs-6">
                                                                        <p style="font-size: 17px;">
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo $final_creditage; ?></b>
                                                                            <br>
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($transinfo['gy_trans_total'],2); ?></b>
                                                                            <br>
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($tra_row['gy_trans_interest'],2); ?></b>
                                                                            <br>
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($paytotal,2); ?></b>
                                                                            <br>
                                                                            <span style="color: <?php echo $colorlabel; ?>;"><i class="fa fa-arrow-right"></i> <b><?php echo @number_format(($tra_row['gy_trans_interest'] + $transinfo['gy_trans_total']) - $paytotal,2); ?></b>
                                                                            <br></span>
                                                                        </p>
                                                                    </div>
                                                                    <hr style="border-style: inset; width: 95%; margin-bottom: 5px;">
                                                                    <div class="col-md-12">
                                                                        <form method="post" enctype="multipart/form-data" action="update_tra_interest?cd=<?php echo $tra_row['gy_trans_id']; ?>">
                                                                            <div class="row">
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Remaining Bal.</label>
                                                                                        <input type="number" name="my_balance" id="my_balance_<?php echo $tra_row['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" value="<?php echo ($tra_row['gy_trans_interest'] + $transinfo['gy_trans_total']) - $paytotal; ?>" onkeyup="calc_<?php echo $tra_row['gy_trans_id']; ?>()" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Int. %</label>
                                                                                        <select class="form-control" name="my_interestpercentage" id="my_interestpercentage_<?php echo $tra_row['gy_trans_id']; ?>" onchange="calc_<?php echo $tra_row['gy_trans_id']; ?>()" required>
                                                                                            <option value=""></option>
                                                                                            <?php 
                                                                                                for ($i=0; $i <= 50; $i++) {
                                                                                            ?>
                                                                                            <option value="<?php echo $i; ?>"><?php echo $i."%"; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Interest</label>
                                                                                        <input type="number" name="myint" id="myint_<?php echo $tra_row['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" value="0" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label style="color: blue;">New Bal.</label>
                                                                                        <input type="number" name="mynewbalance" id="mynewbalance_<?php echo $tra_row['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" value="0" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <button type="submit" class="btn btn-primary" title="click to pay ..." name="btn-update" id="process" <?php echo $processbtnvalue; ?>>Update Balance <i class="fa fa-chevron-circle-right"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pay -->

                                        <div class="modal fade" id="pay_<?php echo $tra_row['gy_trans_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-money"></i> Payment Section</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-success" style="border-radius: 0px;">
                                                            <div class="panel-heading" style="border-radius: 0px;">
                                                            <b><i class="fa fa-credit-card"></i> <?php echo $tra_row['gy_trans_custname']; ?> Account</b><br>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <?php  
                                                                        //get transaction info
                                                                        $transentry = words($tra_row['gy_trans_id']);
                                                                        $transpayinfo = words($tra_row['gy_trans_code']);

                                                                        $gettransinfo=$link->query("Select * From `gy_tra` Where `gy_trans_id`='$transentry'");
                                                                        $transinfo=$gettransinfo->fetch_array();

                                                                        //get paylogs
                                                                        $paytotal=0;
                                                                        $sql_item_detail=$link->query("Select * From `gy_transaction` Where `gy_tra_code`='$transpayinfo' Order By `gy_trans_date` DESC");

                                                                        while ($log_row=$sql_item_detail->fetch_array()) {

                                                                            @$paytotal += $log_row['gy_trans_total'];
                                                                        }

                                                                        if ($paytotal < ($transinfo['gy_trans_total'] + $tra_row['gy_trans_interest'])) {
                                                                            $colorlabel = "red";
                                                                        }else{
                                                                            $colorlabel = "green";
                                                                        }
                                                                    ?>
                                                                    <div class="col-md-4 col-xs-6">
                                                                        <p style="font-size: 17px;">
                                                                            Credit Age: 
                                                                            <br>
                                                                            Credit Amount: 
                                                                            <br>
                                                                            Interest: 
                                                                            <br>
                                                                            Amount Paid: 
                                                                            <br>
                                                                            Balance: 
                                                                            <br>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-8 col-xs-6">
                                                                        <p style="font-size: 17px;">
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo $final_creditage; ?></b>
                                                                            <br>
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($transinfo['gy_trans_total'],2); ?></b>
                                                                            <br>
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($tra_row['gy_trans_interest'],2); ?></b>
                                                                            <br>
                                                                            <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($paytotal,2); ?></b>
                                                                            <br>
                                                                            <span style="color: <?php echo $colorlabel; ?>;"><i class="fa fa-arrow-right"></i> <b><?php echo @number_format(($tra_row['gy_trans_interest'] + $transinfo['gy_trans_total']) - $paytotal,2); ?></b>
                                                                            <br></span>
                                                                        </p>
                                                                    </div>
                                                                    <hr style="border-style: inset; width: 95%; margin-bottom: 5px;">
                                                                    <div class="col-md-12">
                                                                        <form method="post" enctype="multipart/form-data" action="pay_tra?cd=<?php echo $tra_row['gy_trans_id']; ?>">
                                                                            <div class="row">
                                                                                <div class="col-md-5 col-xs-5">
                                                                                    <div class="form-group">
                                                                                        <label>Amount to Pay</label>
                                                                                        <input type="number" name="mypayment" id="mypayment_<?php echo $tra_row['gy_trans_id']; ?>" min="1" max="<?php echo ($tra_row['gy_trans_interest'] + $transinfo['gy_trans_total']) - $paytotal; ?>" step="0.01" class="form-control" onkeyup="getbalance_<?php echo $tra_row['gy_trans_id']; ?>()" autofocus required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 col-xs-4">
                                                                                    <div class="form-group">
                                                                                        <label>Method</label>
                                                                                        <select class="form-control" name="my_method" id="my_method_<?php echo $tra_row['gy_trans_id']; ?>" onchange="disable_<?php echo $tra_row['gy_trans_id']; ?>()" style="border-radius: 0px; padding: 4px;" required>
                                                                                            <option value="0">CASH</option>
                                                                                            <option value="1">CHEQUE</option>
                                                                                            <option value="2">CARD</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3 col-xs-3">
                                                                                    <div class="form-group">
                                                                                        <label>FEE %</label>
                                                                                        <select class="form-control" name="my_check_percentage" id="my_check_percentage_<?php echo $tra_row['gy_trans_id']; ?>" onchange="getbalance_<?php echo $tra_row['gy_trans_id']; ?>()" style="border-radius: 0px; padding: 4px;" disabled required>
                                                                                            <option value="10">10%</option>
                                                                                            <option value="15">15%</option>
                                                                                            <option value="14">14%</option>
                                                                                            <option value="13">13%</option>
                                                                                            <option value="12">12%</option>
                                                                                            <option value="11">11%</option>
                                                                                            <option value="10">10%</option>
                                                                                            <option value="9">9%</option>
                                                                                            <option value="8">8%</option>
                                                                                            <option value="7">7%</option>
                                                                                            <option value="6">6%</option>
                                                                                            <option value="5">5%</option>
                                                                                            <option value="0">0%</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-5 col-xs-5">
                                                                                    <div class="form-group">
                                                                                        <label>Cheque No.</label>
                                                                                        <input type="text" name="my_check_num" id="my_check_num_<?php echo $tra_row['gy_trans_id']; ?>" class="form-control" placeholder="00000000000000" style=" border-radius: 0px;" autocomplete="off" disabled required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 col-xs-4">
                                                                                    <div class="form-group">
                                                                                        <label>Cheque Amount</label>
                                                                                        <input type="number" name="mycheqamount" id="mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>" min="1" step="0.01" class="form-control" onkeyup="getbalance_<?php echo $tra_row['gy_trans_id']; ?>()" disabled required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3 col-xs-3">
                                                                                    <div class="form-group">
                                                                                        <label>R. Fee</label>
                                                                                        <input type="number" name="myroyalfee" id="myroyalfee_<?php echo $tra_row['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>New Bal.</label>
                                                                                        <input type="number" name="mytotal" id="mytotal_<?php echo $tra_row['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" value="0" readonly required>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Cashier</label>
                                                                                        <select class="form-control" name="mycashier" required>
                                                                                        <?php 
                                                                                            //get cashier
                                                                                            $getcashiers=$link->query("Select * From `gy_user` Where `gy_user_type`='2' Order By `gy_user_id` ASC");
                                                                                            while ($cashrow=$getcashiers->fetch_array()) {
                                                                                         ?>
                                                                                            <option value="<?php echo $cashrow['gy_user_id']; ?>"><?php echo $cashrow['gy_full_name']; ?></option>
                                                                                         <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 col-xs-12">
                                                                                    <button type="submit" class="btn btn-success" title="click to pay ..." name="btn-pay" id="process_<?php echo $tra_row['gy_trans_id']; ?>" <?php echo $processbtnvalue; ?>>Submit Payment <i class="fa fa-chevron-circle-right"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="void_<?php echo $tra_row['gy_trans_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void Account <?php echo $tra_row['gy_trans_code']; ?> <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="void_tra_summ?cd=<?php echo $tra_row['gy_trans_code']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> Delete Secure PIN</label>
                                                                        <input type="password" name="my_secure_pin" class="form-control" autofocus required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script type="text/javascript">

                                            function calc_<?php echo $tra_row['gy_trans_id']; ?>(){
                                                var mybalance = document.getElementById("my_balance_<?php echo $tra_row['gy_trans_id']; ?>").value;
                                                var myinterestpercentage = $("#my_interestpercentage_<?php echo $tra_row['gy_trans_id']; ?>").val();

                                                if (myinterestpercentage == "") {
                                                    var myinterestpercentage = 0;
                                                }

                                                var permultips = parseFloat(myinterestpercentage) / 100;
                                                var getmultipvalue = parseFloat(mybalance) * permultips;
                                                var intfinalvalue = Math.floor(parseFloat(mybalance) + getmultipvalue);

                                                if (!isNaN(intfinalvalue)) {
                                                    $("#mynewbalance_<?php echo $tra_row['gy_trans_id']; ?>").val(intfinalvalue);
                                                    $("#myint_<?php echo $tra_row['gy_trans_id']; ?>").val(Math.floor(getmultipvalue));
                                                }
                                            }

                                            function getbalance_<?php echo $tra_row['gy_trans_id']; ?>(){

                                                var methods = $("#my_method_<?php echo $tra_row['gy_trans_id']; ?>").val();
                                                var myroyalpercentage = $("#my_check_percentage_<?php echo $tra_row['gy_trans_id']; ?>").val();
                                                var mycheqamount = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").val();
                                                var mypayment = $("#mypayment_<?php echo $tra_row['gy_trans_id']; ?>").val();

                                                var oldbalance = <?php echo ($tra_row['gy_trans_interest'] + $transinfo['gy_trans_total']) - $paytotal; ?>;

                                                var newbalance = parseFloat(oldbalance) - parseFloat(mypayment);
                                                if (!isNaN(newbalance)) {
                                                    var newbalanceinput = $("#mytotal_<?php echo $tra_row['gy_trans_id']; ?>").val(newbalance);
                                                }

                                                if (methods == 0) {

                                                    newbalanceinput;
                                                    
                                                }else if (methods == 1) {

                                                    var getroyaltymultips = parseFloat(myroyalpercentage) / 100;
                                                    var cheqchange = parseFloat(mycheqamount) - parseFloat(mypayment);
                                                    var royalfee = Math.round(getroyaltymultips * cheqchange);

                                                    newbalanceinput;

                                                    if (!isNaN(royalfee)) {
                                                        $("#myroyalfee_<?php echo $tra_row['gy_trans_id']; ?>").val(royalfee);
                                                        $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('min', mypayment);
                                                    }
                                                }else{
                                                    newbalanceinput;
                                                }
                                            }

                                            function disable_<?php echo $tra_row['gy_trans_id']; ?>(){

                                                var my_method = document.getElementById("my_method_<?php echo $tra_row['gy_trans_id']; ?>").value;

                                                    if (my_method == "0") {
                                                        var final_method = $("#my_check_percentage_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#process_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                        var final_method = $("#myroyalfee_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                    }else if (my_method == "1") {
                                                        var final_method = $("#my_check_percentage_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', false);
                                                        var final_method = $("#my_check_num_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#process_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', false);
                                                    }else if (my_method == "2") {
                                                        var final_method = $("#my_check_percentage_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#process_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                        var final_method = $("#myroyalfee_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                    }else{
                                                        var final_method = $("#my_check_percentage_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $tra_row['gy_trans_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#process_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                        var final_method = $("#mycheqamount_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                        var final_method = $("#myroyalfee_<?php echo $tra_row['gy_trans_id']; ?>").val("");
                                                    }
                                                        
                                                    return final_method; 

                                            }
                                        </script>

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
        
    </script>

    <script type="text/javascript">
        $('#tra_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script>

</body>

</html>
