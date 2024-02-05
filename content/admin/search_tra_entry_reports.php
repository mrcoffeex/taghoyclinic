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

        $my_project_header_title = "TRA Account Search: <span style='color: blue;'>".$search_text."</span>";

        $query_one = "Select * From `gy_accounts` Where `gy_acc_name` LIKE '%$search_text%' Order By `gy_acc_name` ASC";

        $query_two = "Select COUNT(`gy_acc_id`) From `gy_accounts` Where `gy_acc_name` LIKE '%$search_text%' Order By `gy_acc_name` ASC";

        $query_three = "Select * From `gy_accounts` Where `gy_acc_name` LIKE '%$search_text%' Order By `gy_acc_name` ASC ";

        $my_num_rows = 50;

        include 'my_pagination_search.php';

        $count_results=$link->query($query_one)->num_rows;

    }

    $my_notification = @$_GET['note'];

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "TRA account has been removed";
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
                                    <input type="text" class="form-control" placeholder="Search for Account Name ..." name="tra_entry_search" style="border-radius: 0px;" autofocus required>
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
                                            <th><center>Delete</center></th>
                                            <th><center>Edit</center></th>
                                            <th><center>Name</center></th>
                                            <th><center>Balance</center></th>
                                            <th><center>Log</center></th>
                                            <th><center>Transactions</center></th>
                                            <th><center>Deposit</center></th>
                                            <th><center>Pay</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        while ($acc_row=$query->fetch_array()) {

                                            $thiscode=words($acc_row['gy_acc_id']);

                                            //get balance
                                            $total_balance=0;
                                            $getfullbalance=$link->query("Select * From `gy_tra` Where `gy_acc_id`='$thiscode'");
                                            while($fullbalancerow=$getfullbalance->fetch_array()){

                                                @$total_balance += ($fullbalancerow['gy_trans_interest'] + $fullbalancerow['gy_trans_total']) - $fullbalancerow['gy_trans_cash'];
                                            }

                                            if ($total_balance > 0) {
                                                $my_color = "danger";
                                            }else{
                                                $my_color = "success";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_color; ?>">
                                            <td><center><button type="button" class="btn btn-danger" title="click to void this account ..." data-target="#void_<?php echo $acc_row['gy_acc_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-info" title="click to edit account ..." data-toggle="modal" data-target="#accprofile_<?php echo $acc_row['gy_acc_id']; ?>"><i class="fa fa-edit fa-fw"></i></button></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $acc_row['gy_acc_name']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo @number_format($total_balance,2); ?></center></td>
                                            <td><center><a href="tra_logs?cd=<?php echo $acc_row['gy_acc_id']; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-info" title="click to view payment logs ..."><i class="fa fa-file-text-o fa-fw"></i></button></a></center></td>
                                            <td><center><a href="tra_trans?cd=<?php echo $acc_row['gy_acc_id']; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-warning" title="click to view info ..."><i class="fa fa-dropbox"></i></button></a></center></td>
                                            <td><center><button type="button" class="btn btn-primary" title="click to add deposit ..." data-toggle="modal" data-target="#dep_<?php echo $acc_row['gy_acc_id']; ?>"><i class="fa fa-money fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-success" title="click to pay ..." data-toggle="modal" data-target="#pay_<?php echo $acc_row['gy_acc_id']; ?>"><i class="fa fa-dollar fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Profile -->

                                        <div class="modal fade" id="accprofile_<?php echo $acc_row['gy_acc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-user"></i> Account Profile</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-info" style="border-radius: 0px;">
                                                            <div class="panel-heading" style="border-radius: 0px;">
                                                            <b><i class="fa fa-user"></i> <?php echo $acc_row['gy_acc_name']; ?> Account</b>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form method="post" enctype="multipart/form-data" action="update_account_profile?cd=<?php echo $acc_row['gy_acc_id']; ?>">
                                                                            <div class="row">
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Name</label>
                                                                                        <input type="text" maxlength="50" name="myaccname" class="form-control" value="<?php echo $acc_row['gy_acc_name']; ?>" autofocus required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Contact #</label>
                                                                                        <input type="text" maxlength="11" name="myacccontact" class="form-control" value="<?php echo $acc_row['gy_acc_contact']; ?>" autofocus>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 col-xs-12">
                                                                                    <div class="form-group">
                                                                                        <label>Address</label>
                                                                                        <textarea name="myaccaddress" class="form-control" rows="3"><?php echo $acc_row['gy_acc_address']; ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <button type="submit" class="btn btn-info" title="click to update account ..." name="btn-updateprof">Update Account Profile <i class="fa fa-chevron-circle-right"></i></button>
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

                                        <!-- Deposit -->

                                        <div class="modal fade" id="dep_<?php echo $acc_row['gy_acc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-dollar"></i> Deposit Section</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-primary" style="border-radius: 0px;">
                                                            <div class="panel-heading" style="border-radius: 0px;">
                                                            <b><i class="fa fa-user"></i> <?php echo $acc_row['gy_acc_name']; ?> Account</b>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-4 col-xs-4">
                                                                        <div class="form-group">
                                                                            <p style="font-size: 17px;">
                                                                                Current Deposit: 
                                                                                <br>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-4">
                                                                        <div class="form-group">
                                                                            <p style="font-size: 17px;">
                                                                                <i class="fa fa-arrow-right"></i> <b><?php echo @number_format($acc_row['gy_acc_deposit'],2); ?></b>
                                                                                <br>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-xs-4">
                                                                        <div class="form-group">
                                                                            <a href="tra_deposit_log?cd=<?php echo $acc_row['gy_acc_id']; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-info" title="click to show deposit logs ..."><i class="fa fa-file-text-o"></i> Deposit Logs</button></a>
                                                                        </div>
                                                                    </div>
                                                                    <hr style="border-style: inset; width: 95%; margin-bottom: 5px;">
                                                                    <div class="col-md-12">
                                                                        <form method="post" enctype="multipart/form-data" action="update_tra_deposit?cd=<?php echo $acc_row['gy_acc_id']; ?>" onsubmit="return validateFormDep_<?php echo $acc_row['gy_acc_id']; ?>(this);">
                                                                            <div class="row">
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Method</label>
                                                                                        <select class="form-control" name="mydepmethod" style="border-radius: 0px; padding: 4px;" required>
                                                                                            <option></option>
                                                                                            <option value="0">CASH</option>
                                                                                            <option value="1">CHEQUE</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Amount</label>
                                                                                        <input type="number" name="mydep" id="mydeps_<?php echo $acc_row['gy_acc_id']; ?>" min="0" step="0.01" class="form-control" onkeyup="deposittotal_<?php echo $acc_row['gy_acc_id']; ?>()" autofocus required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Total Deposit</label>
                                                                                        <input type="number" name="mydeptotal" id="mydeptotal_<?php echo $acc_row['gy_acc_id']; ?>" min="0" step="0.01" class="form-control" value="<?php echo $acc_row['gy_acc_deposit']; ?>" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Cashier</label>
                                                                                        <select class="form-control" name="mydepcashier" style=" border-radius: 0px;" required>
                                                                                        <?php 
                                                                                            //get cashier
                                                                                            $getdepcashiers=$link->query("Select * From `gy_user` Where `gy_user_type`='2' Order By `gy_user_id` ASC");
                                                                                            while ($cashdeprow=$getdepcashiers->fetch_array()) {
                                                                                         ?>
                                                                                            <option value="<?php echo $cashdeprow['gy_user_id']; ?>"><?php echo $cashdeprow['gy_full_name']; ?></option>
                                                                                         <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <button type="submit" class="btn btn-primary" title="click to submit deposit ..." name="btn-deposit" id="depositbtn_<?php echo $acc_row['gy_acc_id']; ?>">Add Money <i class="fa fa-chevron-circle-right"></i></button>
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

                                            function validateFormDep_<?php echo $acc_row['gy_acc_id']; ?>(formObj) {
                                          
                                                formObj.depositbtn_<?php echo $acc_row['gy_acc_id']; ?>.disabled = true; 
                                                return true;  
                                          
                                            }

                                            function deposittotal_<?php echo $acc_row['gy_acc_id']; ?>(){
                                                var mydep = document.getElementById('mydeps_<?php echo $acc_row['gy_acc_id']; ?>').value;
                                                var currdep = <?php echo $acc_row['gy_acc_deposit']; ?>

                                                if (mydep == "") {
                                                    var mydep = 0;
                                                }

                                                var totaldep = parseFloat(currdep) + parseFloat(mydep);

                                                if (!isNaN(totaldep)) {
                                                    document.getElementById('mydeptotal_<?php echo $acc_row['gy_acc_id']; ?>').value = totaldep;
                                                }                                            
                                            }
                                        </script>

                                        <!-- Delete -->

                                        <div class="modal fade" id="void_<?php echo $acc_row['gy_acc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete Account <?php echo $acc_row['gy_acc_name']; ?> <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="void_tra_summ?cd=<?php echo $acc_row['gy_acc_id']; ?>">
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

                                        <!-- Pay -->

                                        <div class="modal fade" id="pay_<?php echo $acc_row['gy_acc_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-money"></i> Payment Section</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel panel-success" style="border-radius: 0px;">
                                                            <div class="panel-heading" style="border-radius: 0px;">
                                                            <b><i class="fa fa-user"></i> <?php echo $acc_row['gy_acc_name']; ?> Account</b><br>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form method="post" enctype="multipart/form-data" action="pay_tra?cd=<?php echo $acc_row['gy_acc_id']; ?>" onsubmit="return validateFormPay_<?php echo $acc_row['gy_acc_id']; ?>(this);">
                                                                            <div class="row">
                                                                                <div class="col-md-5 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Balance</label>
                                                                                        <input type="number" name="mytotal" id="mybalance_<?php echo $acc_row['gy_acc_id']; ?>" min="0" step="0.01" class="form-control" value="<?php echo 0+$total_balance; ?>" style=" border-radius: 0px;" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Method</label>
                                                                                        <select class="form-control" name="my_method" id="my_method_<?php echo $acc_row['gy_acc_id']; ?>" onchange="disable_<?php echo $acc_row['gy_acc_id']; ?>()" style="border-radius: 0px; padding: 4px;" required>
                                                                                            <option value="0">CASH</option>
                                                                                            <!-- <option value="1">CHEQUE</option> -->
                                                                                            <option value="2">CARD</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-3 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>FEE %</label>
                                                                                        <select class="form-control" name="my_check_percentage" id="my_check_percentage_<?php echo $acc_row['gy_acc_id']; ?>" onchange="getbalance_<?php echo $acc_row['gy_acc_id']; ?>()" style="border-radius: 0px; padding: 4px;" disabled required>
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
                                                                                <hr style="border-style: inset; width: 95%; margin-bottom: 5px;">
                                                                                <div class="col-md-12 col-xs-12">
                                                                                    <div class="form-group">
                                                                                        <label>Amount to Pay</label>
                                                                                        <input type="number" name="mypayment" id="mypayment_<?php echo $acc_row['gy_acc_id']; ?>" min="0" step="0.01" class="form-control" onkeyup="getbalance_<?php echo $acc_row['gy_acc_id']; ?>()" style=" border-radius: 0px;" autofocus required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Cheque Amount</label>
                                                                                        <input type="number" name="mycheqamount" id="mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>" min="1" step="0.01" class="form-control" onkeyup="getbalance_<?php echo $acc_row['gy_acc_id']; ?>()" style=" border-radius: 0px;" disabled required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Cheque No.</label>
                                                                                        <input type="text" name="my_check_num" id="my_check_num_<?php echo $acc_row['gy_acc_id']; ?>" class="form-control" placeholder="00000000000000" style=" border-radius: 0px;" autocomplete="off" disabled required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>R. Fee</label>
                                                                                        <input type="number" name="myroyalfee" id="myroyalfee_<?php echo $acc_row['gy_acc_id']; ?>" min="0" step="0.01" class="form-control" style=" border-radius: 0px;" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Cashier</label>
                                                                                        <select class="form-control" name="mycashier" style=" border-radius: 0px;" required>
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
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>New Bal.</label>
                                                                                        <input type="number" name="mytotal" id="mytotal_<?php echo $acc_row['gy_acc_id']; ?>" min="0" step="0.01" class="form-control" value="0" style=" border-radius: 0px;" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6 col-xs-6">
                                                                                    <div class="form-group">
                                                                                        <label>Change</label>
                                                                                        <input type="number" name="mychange" id="mychange_<?php echo $acc_row['gy_acc_id']; ?>" min="0" step="0.01" class="form-control" value="0" style=" border-radius: 0px;" readonly required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 col-xs-12">
                                                                                    <button type="submit" class="btn btn-success" title="click to pay ..." name="btn-pay" id="process_<?php echo $acc_row['gy_acc_id']; ?>">Submit Payment <i class="fa fa-chevron-circle-right"></i></button>
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

                                        <script src="../../bower_components/jquery/dist/jquery.min.js"></script>

                                        <script type="text/javascript">

                                            function validateFormPay_<?php echo $acc_row['gy_acc_id']; ?>(formObj) {
                                          
                                                formObj.process_<?php echo $acc_row['gy_acc_id']; ?>.disabled = true; 
                                                return true;  
                                          
                                            }

                                            function getbalance_<?php echo $acc_row['gy_acc_id']; ?>(){

                                                var methods = $("#my_method_<?php echo $acc_row['gy_acc_id']; ?>").val();
                                                var myroyalpercentage = $("#my_check_percentage_<?php echo $acc_row['gy_acc_id']; ?>").val();
                                                var mycheqamount = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").val();
                                                var mypayment = $("#mypayment_<?php echo $acc_row['gy_acc_id']; ?>").val();
                                                var mydeposit = $("#mydep_<?php echo $acc_row['gy_acc_id']; ?>").val();

                                                var oldbalance = $("#mybalance_<?php echo $acc_row['gy_acc_id']; ?>").val();

                                                var newbalance = parseFloat(oldbalance) - (parseFloat(mypayment) + parseFloat(mydeposit));

                                                

                                                if (methods == 0) {

                                                    if (newbalance > 0) {
                                                        var newbal = 0;
                                                        var newbals = newbalance;
                                                    }else{
                                                        var newbal = newbalance * -1;
                                                        var newbals = 0;
                                                    }

                                                    $("#mychange_<?php echo $acc_row['gy_acc_id']; ?>").val(newbal); 

                                                    if (!isNaN(newbalance)) {
                                                        $("#mytotal_<?php echo $acc_row['gy_acc_id']; ?>").val(newbals);
                                                    }   
                                                    
                                                }else if (methods == 1) {

                                                    var getroyaltymultips = parseFloat(myroyalpercentage) / 100;
                                                    var cheqchange = parseFloat(mycheqamount) - parseFloat(mypayment);
                                                    var royalfee = Math.round(getroyaltymultips * cheqchange);

                                                    if (newbalance > 0) {
                                                        var newbals = newbalance;
                                                    }else{
                                                        var newbals = 0;
                                                    }

                                                    if (!isNaN(newbalance)) {
                                                        $("#myroyalfee_<?php echo $acc_row['gy_acc_id']; ?>").val(royalfee);
                                                        $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('min', mypayment);
                                                        $("#mychange_<?php echo $acc_row['gy_acc_id']; ?>").val(cheqchange - royalfee);
                                                        $("#mytotal_<?php echo $acc_row['gy_acc_id']; ?>").val(newbals);
                                                    }
                                                }else{

                                                    if (newbalance > 0) {
                                                        var newbals = newbalance;
                                                    }else{
                                                        var newbals = 0;
                                                    }

                                                    if (!isNaN(newbalance)) {
                                                        $("#mytotal_<?php echo $acc_row['gy_acc_id']; ?>").val(newbals); 
                                                    }

                                                     
                                                }
                                            }

                                            function disable_<?php echo $acc_row['gy_acc_id']; ?>(){

                                                var my_method = document.getElementById("my_method_<?php echo $acc_row['gy_acc_id']; ?>").value;

                                                    if (my_method == "0") {
                                                        var final_method = $("#my_check_percentage_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#process_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                        var final_method = $("#myroyalfee_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                    }else if (my_method == "1") {
                                                        var final_method = $("#my_check_percentage_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', false);
                                                        var final_method = $("#my_check_num_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#process_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#mypayment_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                    }else if (my_method == "2") {
                                                        var final_method = $("#my_check_percentage_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#process_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', false);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                        var final_method = $("#myroyalfee_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                    }else{
                                                        var final_method = $("#my_check_percentage_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $acc_row['gy_acc_id']; ?>").prop('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#process_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").attr('disabled', true);
                                                        var final_method = $("#my_check_num_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                        var final_method = $("#mycheqamount_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                        var final_method = $("#myroyalfee_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                    }
                                                        
                                                    return final_method; 

                                            }

                                            var timer;
                                            $(document).ready(function(){
                                                $("#mytrans_<?php echo $acc_row['gy_acc_id']; ?>").change(function(){
                                                    clearTimeout(timer);
                                                    var ms = 100; // milliseconds
                                                    $.get("live_search_balance", {trans_id: $(this).val()}, function(data){
                                                        timer = setTimeout(function() {
                                                            $("#mypayment_<?php echo $acc_row['gy_acc_id']; ?>").val("");
                                                            document.getElementById("mypayment_<?php echo $acc_row['gy_acc_id']; ?>").max = data;
                                                            $("#mybalance_<?php echo $acc_row['gy_acc_id']; ?>").empty();
                                                            $("#mybalance_<?php echo $acc_row['gy_acc_id']; ?>").val(data);
                                                        }, ms);
                                                    });
                                                });
                                            });
                                        </script>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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

    <script type="text/javascript">
        
    </script>

    <!-- <script type="text/javascript">
        $('#tra_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>