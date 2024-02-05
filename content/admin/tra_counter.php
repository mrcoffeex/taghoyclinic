<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "TRA COUNTER";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Item Added";
    }else if ($my_notification == "not_found") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Product not found";
    }else if ($my_notification == "pin_error") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "empty") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty transaction cannot process";
    }else if ($my_notification == "out_stock") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Out of Stock";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "no_transcode") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Transaction Code not exist...";
    }else if ($my_notification == "discount_limit") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "The Amount of Discount is not allowed";
    }else if ($my_notification == "duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Duplicate Item";
    }else if ($my_notification == "item_remove") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Item Removed";
    }else if ($my_notification == "add_account") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Account Added";
    }else if ($my_notification == "submit") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Transaction Sent";
    }else if ($my_notification == "add_discount") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Discount Added";
    }else if ($my_notification == "item_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Item Updated";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    include 'check_transaction.php';

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
                    <h3 class="page-header"><i class="fa fa-desktop"></i> <?php echo $my_project_header_title; ?></h3>
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

                <div class="col-md-5">
                    <div class="form-group">
                        <!-- <a href="quotations"><button type="button" class="btn btn-lg btn-success" title="click to open Quotations ..." style="border-radius: 0px;" disabled><i class="fa fa-file-o fa-fw"></i> Quotation Forms</button></a>&nbsp;&nbsp; -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_account" title="click to add account ..." style="border-radius: 0px;" accesskey="a"><i class="fa fa-user fa-fw"></i> Add Customer Account (alt + a)</button>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <form method="post" enctype="multipart/form-data" action="add_item?cd=<?php echo $my_trans_code; ?>">
                            <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name ...  (alt + 1)" accesskey="1" list="myProducts" name="product_search" id="suggest" style="border-radius: 0px;" autocomplete="off" autofocus required>
                            <datalist id="myProducts"></datalist>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="add_account" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-user fa-fw"></i> Add Account</center></h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="add_account">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="my_acc_name" class="form-control" autofocus required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="my_acc_address" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Contact #</label>
                                                <input type="text" name="my_acc_contact" maxlength="11" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" name="submit_acc" class="btn btn-primary" title="click to add account ...">Add <i class="fa fa-angle-right fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php  

                    //free vars
                    $total = "";

                    //items
                    $get_items=$link->query("Select * From `gy_tra_details` LEFT JOIN `gy_products` On `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$my_trans_code' Order By `gy_tra_details`.`gy_transdet_id` DESC");

                    //count items
                    $count_items=$get_items->num_rows;
                ?>
                <div class="col-md-9">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Order Table: <b><?php echo $count_items; ?> item(s)</b>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Price (SRP)</center></th>
                                            <th><center>Discount</center></th>
                                            <th><center>Price</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Sub-Total</center></th>
                                            <th><center>Edit</center></th>
                                            <!-- <th><center>Discount</center></th> -->
                                            <th><center>Remove</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            //get items
                                            while ($item_row=$get_items->fetch_array()) {

                                                //price
                                                // if ($item_row['gy_product_discount'] == 0) {
                                                //     $my_final_price = $item_row['gy_product_price_srp'];
                                                // }else{
                                                //     $my_final_price = $item_row['gy_product_price_srp'] - $item_row['gy_product_discount'];
                                                // }

                                                //quantity status
                                                if ($item_row['gy_product_quantity'] < $item_row['gy_trans_quantity']) {
                                                    $my_quantity_status = "danger";
                                                }else{
                                                    $my_quantity_status = "info";
                                                }

                                                //do not show bodega coding
                                                if ($item_row['gy_product_cat'] == "bodega") {
                                                    $my_coding = "";
                                                }else{
                                                    $my_coding = toAlpha($item_row['gy_product_price_cap']);
                                                }

                                                //remain zero if the discount is negative
                                                if ($item_row['gy_product_discount'] <= 0) {
                                                    $my_discount_val = 0;
                                                }else{
                                                    $my_discount_val = $item_row['gy_product_discount'];
                                                }

                                                $my_subtotal = $item_row['gy_product_price'] * $item_row['gy_trans_quantity'];

                                                @$total += $my_subtotal;
                                        ?>
                                            <tr class="<?php echo $my_quantity_status; ?>">
                                                <td><center><b><span style="color: blue;"><?php echo $my_coding; ?></span> <?php echo $item_row['gy_product_code']; ?></b></center></td>
                                                <td><center><b><?php echo $item_row['gy_product_name']; ?></b></center></td>
                                                <td><center><b><?php echo number_format($item_row['gy_product_price_srp'],2); ?></b></center></td>
                                                <td><center><b><?php echo number_format($my_discount_val,2); ?></b></center></td>
                                                <td><center><b><?php echo number_format($item_row['gy_product_price'],2); ?></b></center></td>
                                                <td><center><b><?php echo $item_row['gy_trans_quantity']."</b> ".$item_row['gy_product_unit']; ?> (<span style="color: blue; font-weight: bold;"><?php echo $item_row['gy_product_quantity']; ?></span>)</center></td>
                                                <td><center><b><span style="color: blue;"><?php echo number_format($my_subtotal,2); ?></span></b></center></td>
                                                <td><center><button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_<?php echo $item_row['gy_transdet_id']; ?>" title="click to edit quantity ..."><i class="fa fa-edit fa-fw"></i></button></center></td>
                                                <!-- <td><center><button type="button" class="btn btn-warning" data-toggle="modal" data-target="#discount_<?php #echo $item_row['gy_transdet_id']; ?>" title="click to add discount ..." disabled><i class="fa fa-star fa-fw"></i></button></center></td> -->
                                                <td><center><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_<?php echo $item_row['gy_transdet_id']; ?>" title="click to remove ..."><i class="fa fa-times fa-fw"></i></button></center></td>
                                            </tr>

                                            <!-- modals -->

                                            <!-- Edit -->

                                            <div class="modal fade" id="edit_<?php echo $item_row['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit fa-fw"></i> Edit Item <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" enctype="multipart/form-data" action="edit_quantity?cd=<?php echo $item_row['gy_transdet_id']; ?>">

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Last Price -> <span style="color: blue;"><?php echo number_format($item_row['gy_product_discount_per'],2); ?></span></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Retail Price - (Php)</label>
                                                                            <input type="number" onkeyup="check_discount_for_pin<?php echo $item_row['gy_transdet_id']; ?>()" name="my_retail_price" id="my_retail_price<?php echo $item_row['gy_transdet_id']; ?>" step="0.01" value="<?php echo $item_row['gy_product_price']; ?>" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label>Quantity by <?php echo $item_row['gy_product_unit']; ?></label>
                                                                            <input type="number" name="my_quantity" min="0" step="0.01" value="<?php echo $item_row['gy_trans_quantity']; ?>" class="form-control" autofocus required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label style="color: blue;"><i class="fa fa-lock fa-fw"></i> ADMIN PIN</label>
                                                                            <input type="password" class="form-control" name="my_pin_edit" id="my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>" required="false" disabled="true">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <button type="submit" name="submit_edit" class="btn btn-warning"><i class="fa fa-angle-right fa-fw"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script type="text/javascript">
                                                function check_discount_for_pin<?php echo $item_row['gy_transdet_id']; ?>(){
                                                    var retail = <?php echo $item_row['gy_product_price_srp']; ?>;
                                                    var limit = <?php echo $item_row['gy_product_discount_per']; ?>;
                                                    var my_retail = document.getElementById('my_retail_price<?php echo $item_row['gy_transdet_id']; ?>').value;

                                                    if (my_retail < limit) {
                                                        document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').disabled = false;
                                                        document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').required = true;
                                                    }else{
                                                        document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').disabled = true;
                                                        document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').required = false;
                                                    }

                                                }
                                            </script>

                                            <!-- Delete -->

                                            <div class="modal fade" id="delete_<?php echo $item_row['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Remove Item</h4>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data" action="delete_item?cd=<?php echo $item_row['gy_transdet_id']; ?>">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p style="font-size: 25px; margin-left: 15px; margin-right: 15px;">Do You want to remove <span style="color: blue;"><?php echo $item_row['gy_product_name']; ?></span> on the list?</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="delete_item" class="btn btn-danger">Remove</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <tr class="info">
                                            <td colspan="5"></td>
                                            <td><center><b><span style="font-size: 18px;">TOTAL</span></b></center></td>
                                            <td><center><b><span style="font-size: 18px; color: blue;"><?php echo @number_format($total,2); ?></span></b></center></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            Customer Details
                        </div>

                        <div class="panel-body">
                            <form method="post" id="my_trans_form" enctype="multipart/form-data" action="submit_transaction" onsubmit="return validateForm(this);">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>TRA Code</label>
                                            <input type="text" name="my_trans_code" class="form-control" value="<?php echo $my_trans_code; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Account Name (alt + 2)</label>
                                            <select name="my_acc_code" class="form-control" accesskey="2" required>
                                                <option value=""></option>
                                                <?php  
                                                    //get salesman
                                                    $getaccounts=$link->query("Select * From `gy_accounts` Order By `gy_acc_name` ASC");
                                                    while ($accountsrow=$getaccounts->fetch_array()) {
                                                        
                                                ?>
                                                <option value="<?php echo $accountsrow['gy_acc_id']; ?>"><?php echo $accountsrow['gy_acc_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Salesman</label>
                                            <select name="my_salesman" class="form-control" required>
                                                <option value=""></option>
                                                <?php  
                                                    //get salesman
                                                    $getsalesman=$link->query("Select * From `gy_user` Where `gy_user_type`='1'");
                                                    while ($salesmanrow=$getsalesman->fetch_array()) {
                                                        
                                                ?>
                                                <option value="<?php echo $salesmanrow['gy_user_id']; ?>"><?php echo $salesmanrow['gy_full_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Note</label>
                                            <textarea name="my_note" class="form-control" rows="2" placeholder="type your note here ..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Cashier</label>
                                            <select class="form-control" name="mycashier" required>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Prepared By:</label>
                                            <input type="text" name="my_prepared_by" class="form-control" value="<?php echo $user_info; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" name="submit_trans" id="submit_trans" class="btn btn-lg btn-primary" style="width: 100%; border-radius: 0px;"><i class="fa fa-chevron-circle-right fa-fw"></i> Submit (Enter)</button><br>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" name="print_quotation" class="btn btn-success" style="width: 100%;" accesskey="p" disabled><i class="fa fa-file-text-o fa-fw"></i> Print Quotation (alt + p)</button>
                                        </div>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
      
            formObj.submit_trans.disabled = true; 
            return true;  
      
        }  
    </script>

    <script type="text/javascript">
        var timer;
        $(document).ready(function(){
            $("#suggest").keyup(function(){
                clearTimeout(timer);
                var ms = 200; // milliseconds
                $.get("live_search", {product_search: $(this).val()}, function(data){
                    timer = setTimeout(function() {
                        $("datalist").empty();
                        $("datalist").html(data);
                    }, ms);
                });
            });
        });
    </script>

    <script type="text/javascript">
        var timer;
        $(document).ready(function(){
            $("#my_cust_name").keyup(function(){
                clearTimeout(timer);
                var ms = 200;
                $.get("live_search_account_name", {my_cust_name: $(this).val()}, function(data){
                    timer = setTimeout(function() {
                        $("#res_name").empty();
                        $("#res_name").html(data);
                    }, ms);
                });
            });
        });
    </script>

</body>

</html>
