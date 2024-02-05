<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_dir_value = @$_GET['cd'];

    $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_dir_value'");
    $accountrow=$getaccount->fetch_array();

    //account code
    $account=words($accountrow['gy_acc_id']);

    $my_project_header_title = $accountrow['gy_acc_name']." Transactions";

    $gettransactions=$link->query("Select * From `gy_tra` Where `gy_tra`.`gy_acc_id`='$account' Order By `gy_trans_date` DESC");
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

        .fieldfader{
            background-color: rgba(0,0,0,.3);
        }
    </style>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">   
                <div class="col-888-12">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <p style="font-size: 20px;">
                               <center>
                                <span style="font-size: 20px; font-weight: bold;"><i class="fa fa-dropbox"></i> <?php echo $my_project_header_title; ?></span><br>
                                <button type="button" data-target="#customprint" data-toggle="modal" class="btn btn-success" title="click to print custom statement ..."><i class="fa fa-print"></i> Print Statement</button>
                                </center>
                            </p>
                        </div>
                    </div>

                    <div class="modal fade" id="customprint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-arrow-circle-up"></i> Print Statement</h4></center>
                                </div>
                                <div class="modal-body">
                                    <div class="panel panel-success" style="border-radius: 0px;">
                                        <div class="panel-heading" style="border-radius: 0px;">
                                        <b><i class="fa fa-credit-card"></i> <?php echo $accountrow['gy_acc_name']; ?> Account</b><br>
                                        </div>
                                        <div class="panel-body">
                                            <form method="post" enctype="multipart/form-data" action="print_statement_custom.php?dir=<?php echo $my_dir_value; ?>">
                                                <div class="row">
                                                    <?php  
                                                        //get transactions
                                                        $getbox=$link->query("Select * From `gy_tra` Where `gy_acc_id`='$my_dir_value'");
                                                        while ($boxes=$getbox->fetch_array()) {
                                                    ?>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="trans[]" id="<?php echo $boxes['gy_trans_code']; ?>" value="<?php echo $boxes['gy_trans_code']; ?>"> <?php echo $boxes['gy_trans_code']; ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>

                                                        <div class="form-group">
                                                            <button type="submit" name="print" class="btn btn-success" title="click to submit ...">Print Selected</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php  
                        //count transactions
                        $count=$gettransactions->num_rows;
                        if ($count == 0) {
                            echo "<center><p style='color: red; font-size: 20px;'><i class='fa fa-warning'></i> No Transactions</p></center>";
                        }

                        //show transactions
                        while ($transrow=$gettransactions->fetch_array()) {
                            //get salesman data
                            $salesmandata=words($transrow['gy_prepared_by']);
                            $getsalesmandata=$link->query("Select * From `gy_user` Where `gy_user_id`='$salesmandata'");
                            $srow=$getsalesmandata->fetch_array();

                            //transaction code
                            $transcode=words($transrow['gy_trans_code']);

                            $datetime1 = strtotime($transrow['gy_trans_date']);
                            $datetime2 = strtotime(date("Y-m-d H:i:s"));

                            $creditage = get_timeage($datetime1, $datetime2);

                            if ($transrow['gy_trans_cash'] >= ($transrow['gy_trans_total'] + $transrow['gy_trans_interest'])) {
                                $final_creditage = "PAID";
                                $ftable = "fieldfader";
                                $frow = "fieldfader";
                                $fsubtotal = "fieldfader";
                                $finterest = "fieldfader";
                                $ftotal = "fieldfader";
                            }else{
                                $final_creditage = $creditage;
                                $ftable = "warning";
                                $frow = "default";
                                $fsubtotal = "info";
                                $finterest = "danger";
                                $ftotal = "success";
                            }
                    ?>
                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr class="<?php echo $ftable; ?>">
                                        <th>TRA Code: <span style="color: blue;"><?php echo $transrow['gy_trans_code']; ?></span></th>
                                        <th colspan="2">Prepared By: <span style="color: blue;"><?php echo $srow['gy_full_name']; ?></span></th>
                                        <th colspan="2"><?php echo date("M d, Y - g:i:s A", strtotime($transrow['gy_trans_date'])); ?></th>
                                        <th colspan="2"><center><a href="print_statement?cd=<?php echo $transcode; ?>&dir=<?php echo $my_dir_value; ?>"><button type="button" class="btn btn-success" title="click to print account statement ..."><i class="fa fa-print"></i> Print</button></a></center></th>
                                    </tr>
                                    <tr class="<?php echo $frow; ?>">
                                        <th class="col-sm-2"><center>Qty</center></th>
                                        <th class="col-sm-1"><center>Unit</center></th>
                                        <th class="col-sm-5"><center>Description</center></th>
                                        <th class="col-sm-2"><center>Price</center></th>
                                        <th class="col-sm-2"><center>Amount</center></th>
                                        <th class="col-sm-2"><center>Edit</center></th>
                                        <th class="col-sm-2"><center>Void</center></th>
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php 
                                        //get items
                                        $itemtotal=0;
                                        $getitems=$link->query("Select * From `gy_tra_details` LEFT JOIN `gy_products` On `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$transcode' Order By `gy_products`.`gy_product_price_srp` DESC");
                                        while ($itemsrow=$getitems->fetch_array()) {
                                            @$itemtotal += $itemsrow['gy_product_price'] * $itemsrow['gy_trans_quantity'];

                                            $mytotal = $transrow['gy_trans_total'];
                                            $myinterest = $transrow['gy_trans_interest'];
                                            $mycash = $transrow['gy_trans_cash'];

                                            if ($mycash > 0) {
                                                $buttonprop = "disabled";
                                                $title = "Item Void is not allowed ...";
                                            }else{
                                                $buttonprop = "";
                                                $title = "click to void item ...";
                                            }

                                            if ($mycash >= ($mytotal + $myinterest)) {
                                                $buttonpropedit = "disabled";
                                                $titleedit = "Item Update is not allowed ...";
                                            }else{
                                                $buttonpropedit = "";
                                                $titleedit = "click to edit pricing ...";
                                            }
                                    ?>              
                                    <tr class="<?php echo $frow; ?>">
                                        <td class="col-sm-2"><center><b><?php echo $itemsrow['gy_trans_quantity']; ?></b></center></td>
                                        <td class="col-sm-1"><center><?php echo $itemsrow['gy_product_unit']; ?></center></td>
                                        <td class="col-sm-5"><center><?php echo $itemsrow['gy_product_name']; ?></center></td>
                                        <td class="col-sm-2"><center><b><?php echo @number_format($itemsrow['gy_product_price'],2); ?></b></center></td>
                                        <td class="col-sm-2"><center><b><?php echo @number_format($itemsrow['gy_product_price'] * $itemsrow['gy_trans_quantity'],2); ?></b></center></td>
                                        <th><center><button type="button" data-toggle="modal" data-target="#edit_<?php echo $itemsrow['gy_transdet_id']; ?>" class="btn btn-info" title="<?php echo $titleedit; ?>" <?php echo $buttonpropedit; ?> ><i class="fa fa-edit"></i></button></center></th>
                                        <th><center><button type="button" data-toggle="modal" data-target="#void_<?php echo $itemsrow['gy_transdet_id']; ?>" class="btn btn-danger" title="<?php echo $title; ?>" <?php echo $buttonprop; ?> ><i class="fa fa-trash-o"></i></button></center></th>
                                    </tr>



                                    <!-- Edit -->

                                    <div class="modal fade" id="edit_<?php echo $itemsrow['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-dollar"></i> Price Section</h4></center>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="edit_traitems?cd=<?php echo $itemsrow['gy_transdet_id']; ?>&code=<?php echo $itemsrow['gy_trans_code']; ?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Last Price -> <span style="color: blue;"><?php echo @number_format($itemsrow['gy_product_discount_per'],2); ?></span></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Retail Price - (Php)</label>
                                                                    <input type="number" onkeyup="calprice_<?php echo $itemsrow['gy_transdet_id']; ?>()" name="my_retail_price" id="my_retail_price<?php echo $itemsrow['gy_transdet_id']; ?>" step="0.01" value="<?php echo $itemsrow['gy_product_price']; ?>" class="form-control" autofocus required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Quantity by <?php echo $itemsrow['gy_product_unit']; ?></label>
                                                                    <input type="number" name="my_quantity" id="my_quantity_<?php echo $itemsrow['gy_transdet_id']; ?>" min="0" step="0.01" value="<?php echo $itemsrow['gy_trans_quantity']; ?>" class="form-control" readonly required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label style="color: blue;">TOTAL</label>
                                                                    <input type="number" class="form-control" step="0.01" min="0" name="myitemtotal" id="myitemtotal_<?php echo $itemsrow['gy_transdet_id']; ?>" readonly >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <button type="submit" name="btn-priceupdate" class="btn btn-info"><i class="fa fa-angle-right fa-fw"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        function calprice_<?php echo $itemsrow['gy_transdet_id']; ?>(){
                                            var rprice = document.getElementById('my_retail_price<?php echo $itemsrow['gy_transdet_id']; ?>').value;
                                            var rqty = document.getElementById('my_quantity_<?php echo $itemsrow['gy_transdet_id']; ?>').value;

                                            var rtotal = parseFloat(rprice) * parseFloat(rqty);

                                            if (!isNaN(rtotal)) {
                                                document.getElementById('myitemtotal_<?php echo $itemsrow['gy_transdet_id']; ?>').value = Math.floor(rtotal);
                                            }
                                        }
                                    </script>

                                    <!-- Void -->

                                    <div class="modal fade" id="void_<?php echo $itemsrow['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void Item</small></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="void_tra_item?cd=<?php echo $itemsrow['gy_transdet_id']; ?>&dir=<?php echo $my_dir_value; ?>">
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

                                    <?php } ?>
                                    <tr class="<?php echo $fsubtotal; ?>">
                                        <td colspan="2" style="color: blue; font-weight: bold;">SubTotal</td>
                                        <td style="color: blue; font-weight: bold;"><?php echo @number_format($itemtotal,2); ?></td>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    <tr class="<?php echo $finterest; ?>">
                                        <td colspan="2" style="color: red; font-weight: bold;">Interest</td>
                                        <td style="color: red; font-weight: bold;"><?php echo @number_format($transrow['gy_trans_interest'],2); ?></td>
                                        <td colspan="4"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#int_<?php echo $transrow['gy_trans_id']; ?>" title="click to add interest ..."><i class="fa fa-edit"></i> Add Interest</button></td>
                                    </tr>
                                    <tr class="<?php echo $ftotal; ?>">
                                        <td colspan="2" style="color: green; font-weight: bold; text-align: right;">Total</td>
                                        <td style="color: green; font-weight: bold;"><?php echo @number_format($itemtotal + $transrow['gy_trans_interest'],2); ?></td>
                                        <td colspan="4"><a href="print_receipt_thermal_tra?cd=<?php echo $transrow['gy_trans_code']; ?>&dir=<?php echo $my_dir_value; ?>"><button type="button" class="btn btn-success" title="click to print slip ..."><i class="fa fa-print"></i> Print Slip</button></a></td>
                                    </tr>
                                    <tr class="<?php echo $ftable; ?>">
                                        <td colspan="2" style="font-weight: bold; text-align: right;">Paid Amount</td>
                                        <td style="font-weight: bold;"><?php echo @number_format($transrow['gy_trans_cash'],2); ?></td>
                                        <td colspan="4"><i>Note</i>: <?php echo $transrow['gy_tra_note']; ?></td>
                                    </tr>
                                    <tr class="<?php echo $ftable; ?>">
                                        <td colspan="2" style="font-weight: bold; text-align: right;">Balance</td>
                                        <td style="font-weight: bold;"><?php echo @number_format(($itemtotal + $transrow['gy_trans_interest']) - $transrow['gy_trans_cash'],2); ?></td>
                                        <td colspan="4">&nbsp;</td>                                    
                                    </tr>

                                    <!-- Price Change -->

                                    <div class="modal fade" id="int_<?php echo $transrow['gy_trans_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-arrow-circle-up"></i> Interest Section</h4></center>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="panel panel-danger" style="border-radius: 0px;">
                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                        <b><i class="fa fa-credit-card"></i> <?php echo $transrow['gy_trans_custname']; ?> Account</b><br>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <?php  
                                                                    //get transaction info
                                                                    $transentry = words($transrow['gy_trans_id']);
                                                                    $transpayinfo = words($transrow['gy_trans_code']);

                                                                    $gettransinfo=$link->query("Select * From `gy_tra` Where `gy_trans_id`='$transentry'");
                                                                    $transinfo=$gettransinfo->fetch_array();

                                                                    //get paylogs
                                                                    $paytotal=0;
                                                                    $sql_item_detail=$link->query("Select * From `gy_transaction` Where `gy_tra_code`='$transpayinfo' Order By `gy_trans_date` DESC");

                                                                    while ($log_row=$sql_item_detail->fetch_array()) {

                                                                        @$paytotal += $log_row['gy_trans_total'] + $log_row['gy_trans_depositpay'];
                                                                    }

                                                                    if ($paytotal < ($transinfo['gy_trans_total'] + $transrow['gy_trans_interest'])) {
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
                                                                        <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($transrow['gy_trans_interest'],2); ?></b>
                                                                        <br>
                                                                        <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($paytotal,2); ?></b>
                                                                        <br>
                                                                        <span style="color: <?php echo $colorlabel; ?>;"><i class="fa fa-arrow-right"></i> <b><?php echo @number_format(($transrow['gy_trans_interest'] + $transinfo['gy_trans_total']) - $paytotal,2); ?></b>
                                                                        <br></span>
                                                                    </p>
                                                                </div>
                                                                <hr style="border-style: inset; width: 95%; margin-bottom: 5px;">
                                                                <div class="col-md-12">
                                                                    <form method="post" enctype="multipart/form-data" action="update_tra_interest?cd=<?php echo $transrow['gy_trans_id']; ?>" onsubmit="return validateFormInt_<?php echo $transrow['gy_trans_id']; ?>(this);">
                                                                        <div class="row">
                                                                            <div class="col-md-6 col-xs-6">
                                                                                <div class="form-group">
                                                                                    <label>Remaining Bal.</label>
                                                                                    <input type="number" name="my_balance" id="my_balance_<?php echo $transrow['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" value="<?php echo ($transrow['gy_trans_interest'] + $transinfo['gy_trans_total']) - $paytotal; ?>" onkeyup="calc_<?php echo $transrow['gy_trans_id']; ?>()" readonly required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-xs-6">
                                                                                <div class="form-group">
                                                                                    <label>Int. %</label>
                                                                                    <select class="form-control" name="my_interestpercentage" id="my_interestpercentage_<?php echo $transrow['gy_trans_id']; ?>" onchange="calc_<?php echo $transrow['gy_trans_id']; ?>()" required>
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
                                                                                    <input type="number" name="myint" id="myint_<?php echo $transrow['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" value="0" readonly required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-xs-6">
                                                                                <div class="form-group">
                                                                                    <label style="color: blue;">New Bal.</label>
                                                                                    <input type="number" name="mynewbalance" id="mynewbalance_<?php echo $transrow['gy_trans_id']; ?>" min="0" step="0.01" class="form-control" value="0" readonly required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-xs-6">
                                                                                <button type="submit" class="btn btn-primary" title="click to pay ..." name="btn-update" id="intbtn_<?php echo $transrow['gy_trans_id']; ?>">Update Balance <i class="fa fa-chevron-circle-right"></i></button>
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
                                    
                                    <script type="text/javascript">

                                        function validateFormInt_<?php echo $transrow['gy_trans_id']; ?>(formObj) {
                                          
                                            formObj.intbtn_<?php echo $transrow['gy_trans_id']; ?>.disabled = true; 
                                            return true;  
                                      
                                        } 

                                        function calc_<?php echo $transrow['gy_trans_id']; ?>(){
                                            var mybalance = document.getElementById("my_balance_<?php echo $transrow['gy_trans_id']; ?>").value;
                                            var myinterestpercentage = $("#my_interestpercentage_<?php echo $transrow['gy_trans_id']; ?>").val();

                                            if (myinterestpercentage == "") {
                                                var myinterestpercentage = 0;
                                            }

                                            var permultips = parseFloat(myinterestpercentage) / 100;
                                            var getmultipvalue = parseFloat(mybalance) * permultips;
                                            var intfinalvalue = Math.floor(parseFloat(mybalance) + getmultipvalue);

                                            if (!isNaN(intfinalvalue)) {
                                                $("#mynewbalance_<?php echo $transrow['gy_trans_id']; ?>").val(intfinalvalue);
                                                $("#myint_<?php echo $transrow['gy_trans_id']; ?>").val(Math.floor(getmultipvalue));
                                            }
                                        }
                                    </script>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <?php } ?>
                    <br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>