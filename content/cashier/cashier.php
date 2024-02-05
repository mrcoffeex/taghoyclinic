<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Cashier";

    $my_dir_value = @$_GET['cd'];

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Item Added";
    }else if ($my_notification == "not_found") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Product not found";
    }else if ($my_notification == "update_cash") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Begenning Cash Updated";
    }else if ($my_notification == "acct_added") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Salesman Account Added";
    }else if ($my_notification == "pin_error") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "no_code") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Invalid Account Code";
    }else if ($my_notification == "empty") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty transaction cannot process";
    }else if ($my_notification == "out_stock") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Out of Stock";
    }else if ($my_notification == "discount_limit") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "The Amount of Discount is not allowed";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Duplicate Item";
    }else if ($my_notification == "invalid_act") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Invalid Action.";
    }else if ($my_notification == "item_remove") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Item Removed";
    }else if ($my_notification == "sold") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Items Sold";
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

    if ($my_dir_value != "") {
        $my_trans_code = $my_dir_value;

        //check the prepared_by if zero to insert salesman id

        //get the info
        $get_new_info=$link->query("Select * From `gy_transaction` LEFT JOIN `gy_user` On `gy_transaction`.`gy_prepared_by`=`gy_user`.`gy_user_id` Where `gy_transaction`.`gy_trans_code`='$my_trans_code'");
        $new_info_row=$get_new_info->fetch_array();

        if ($new_info_row['gy_user_type'] == 0) {
            $my_salesman_val = "<option value='".$new_info_row['gy_user_id']."'>".$new_info_row['gy_full_name']."</option>";
        }else if ($new_info_row['gy_user_type'] == 2) {
            $my_salesman_val = "<option value='".$user_id."'>Me</option>";
        }else{
            $my_salesman_val = "<option value=''></option>";
        }

        
    }else{
        //my scripts
        $check_transaction=$link->query("Select * From `gy_transaction` Where `gy_prepared_by`='$user_id' AND `gy_trans_status`='0'");
        $count_trans=$check_transaction->num_rows;

        $my_salesman_val = "<option value='".$user_id."'>Me</option>";

        if ($count_trans > 0) {
            $get_trans=$link->query("Select * From `gy_transaction` Where `gy_prepared_by`='$user_id' AND `gy_trans_status`='0' Order By `gy_trans_code` DESC");
            $get_trans_row=$get_trans->fetch_array();

            $my_trans_code = $get_trans_row['gy_trans_code'];
        }else{
            $get_latest_trans=$link->query("Select * From `gy_transaction` Order By `gy_trans_code` DESC LIMIT 1");
            $trans_row=$get_latest_trans->fetch_array();

            if ($trans_row['gy_trans_code'] == 0) {
                $create_trans_code = "100001";
            }else{
                $create_trans_code = $trans_row['gy_trans_code'] + 1;
            }

            //add this trans code to database
            $add_this_trans_code=$link->query("Insert Into `gy_transaction`(`gy_trans_code`,`gy_trans_type`, `gy_trans_total`, `gy_trans_discount`, `gy_trans_cash`, `gy_trans_change`, `gy_prepared_by`, `gy_user_id`, `gy_trans_status`, `gy_branch_id`) Values('$create_trans_code','0','0','0','0','0','$user_id','0','0','$user_branch_id')");

            if (!$add_this_trans_code) {
                echo "
                    window.alert('Transfer Error!');
                ";
            }else{
                $get_trans=$link->query("Select * From `gy_transaction` Where `gy_prepared_by`='$user_id' AND `gy_trans_status`='0' AND `gy_trans_code`='$create_trans_code'");
                $get_trans_row=$get_trans->fetch_array();

                $my_trans_code = $get_trans_row['gy_trans_code'];
            }
        }
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
                    <h3 class="page-header"><i class="fa fa-desktop"></i> <?php echo $my_project_header_title; ?> <span style="color: blue;"><?= get_branch_name($user_branch_id) ?></span></h3>
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
                    <div class="form-group">
                        <form method="post" enctype="multipart/form-data" action="add_item_c?cd=<?php echo $my_trans_code; ?>">
                            <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name ...  (alt + 1)" accesskey="1" list="myProducts" name="product_search" id="suggest" style="border-radius: 0px;" autocomplete="off" autofocus required>
                            <datalist id="myProducts"></datalist>
                        </form>
                    </div>
                </div>

                <?php  

                    //free vars
                    $total = "";

                    //items
                    $get_items=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_id`=`gy_products`.`gy_product_id` Where `gy_trans_details`.`gy_trans_code`='$my_trans_code' AND `gy_products`.`gy_branch_id`='$user_branch_id' Order By `gy_trans_details`.`gy_transdet_id` DESC");

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
                                            <th><center>SRP</center></th>
                                            <th><center>Discount</center></th>
                                            <th><center>Price</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Sub-Total</center></th>
                                            <th><center>Edit</center></th>
                                            <th><center>Remove</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            //get items
                                            while ($item_row=$get_items->fetch_array()) {

                                                //quantity status
                                                if ($item_row['gy_product_quantity'] < $item_row['gy_trans_quantity']) {
                                                    $my_quantity_status = "danger";
                                                }else{
                                                    $my_quantity_status = "info";
                                                }
                                                
                                                $my_coding = toAlpha($item_row['gy_product_price_cap']);

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
                                                <td style="padding: 1px;"><center><b><?php echo $item_row['gy_product_code']; ?></b></center></td>
                                                <td style="padding: 1px;"><center><b><a href="previewImage?productId=<?= $item_row['gy_product_id'] ?>" onclick="window.open(this.href, 'mywin', 'left=20, top=20, width=1280, height=720, toolbar=1, resizable=0'); return false;"><?php echo $item_row['gy_product_name']." ".$item_row['gy_product_desc']; ?></a></b></center></td>
                                                <td style="padding: 1px;"><center><b><?php echo number_format($item_row['gy_product_price_srp'],2); ?></b></center></td>
                                                <td style="padding: 1px;"><center><b><?php echo number_format($my_discount_val,2); ?></b></center></td>
                                                <td style="padding: 1px;"><center><b><?php echo number_format($item_row['gy_product_price'],2); ?></b></center></td>
                                                <td style="padding: 1px;"><center><b><?php echo $item_row['gy_trans_quantity']."</b> ".$item_row['gy_product_unit']; ?> (<span style="color: blue; font-weight: bold;"><?php echo $item_row['gy_product_quantity']; ?></span>)</center></td>
                                                <td style="padding: 1px;"><center><b><span style="color: blue;" id="pure_item"><?php echo number_format($my_subtotal,2); ?></span></b></center></td>
                                                <td style="padding: 1px;"><center><button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_<?php echo $item_row['gy_transdet_id']; ?>" title="click to edit quantity ..."><i class="fa fa-edit fa-fw"></i></button></center></td>
                                                <td style="padding: 1px;"><center><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_<?php echo $item_row['gy_transdet_id']; ?>" title="click to remove ..."><i class="fa fa-times fa-fw"></i></button></center></td>
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
                                                            <form method="post" enctype="multipart/form-data" action="edit_quantity_c?cd=<?php echo $item_row['gy_transdet_id']; ?>&dir=<?php echo $my_trans_code; ?>">

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
                                                                            <button type="submit" name="submit_edit" id="submit_edit_<?php echo $item_row['gy_transdet_id']; ?>" class="btn btn-warning"><i class="fa fa-angle-right fa-fw"></i></button>
                                                                            &nbsp;&nbsp;&nbsp;<span style="color: red; font-weight: bold;" id="warnings_<?php echo $item_row['gy_transdet_id']; ?>"></span>
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
                                                    var caps = <?php echo $item_row['gy_product_price_cap']; ?>;
                                                    var my_retail = document.getElementById('my_retail_price<?php echo $item_row['gy_transdet_id']; ?>').value;

                                                    if (caps == 0) {
                                                        var rytcaps = 0;
                                                    }else{
                                                        var rytcaps = parseFloat(caps) * 1.05;
                                                    }

                                                    if (my_retail < limit) {
                                                        if (my_retail < rytcaps) {
                                                            //price too low
                                                            document.getElementById('warnings_<?php echo $item_row['gy_transdet_id']; ?>').innerHTML = "Your price is too low";
                                                            //disable button
                                                            $("#submit_edit_<?php echo $item_row['gy_transdet_id']; ?>").attr('disabled', true);

                                                            document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').disabled = false;
                                                            document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').required = true;
                                                        }else{
                                                            //remove warning
                                                            document.getElementById('warnings_<?php echo $item_row['gy_transdet_id']; ?>').innerHTML = "";
                                                            //enable button
                                                            $("#submit_edit_<?php echo $item_row['gy_transdet_id']; ?>").prop('disabled', false);

                                                            document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').disabled = false;
                                                            document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').required = true;
                                                        }   
                                                    }else{
                                                        //remove warning
                                                        document.getElementById('warnings_<?php echo $item_row['gy_transdet_id']; ?>').innerHTML = "";
                                                        //enable button
                                                        $("#submit_edit_<?php echo $item_row['gy_transdet_id']; ?>").prop('disabled', false);

                                                        document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').disabled = true;
                                                        document.getElementById('my_pin_edit<?php echo $item_row['gy_transdet_id']; ?>').required = false;
                                                    }

                                                }
                                            </script>
                                            
                                            <!-- Delete -->

                                            <div class="modal fade" id="delete_<?php echo $item_row['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Remove Item</h4>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data" action="delete_item_c?cd=<?php echo $item_row['gy_transdet_id']; ?>&dir=<?php echo $my_trans_code; ?>">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p style="font-size: 18px;">Do You want to remove <span style="color: blue;"><?php echo $item_row['gy_product_name']; ?></span> on the list?</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="delete_item" class="btn btn-danger btn-block">Remove</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                        <tr class="info">
                                            <td colspan="5"></td>
                                            <td><center><b><span style="font-size: 18px;">TOTAL</span></b></center></td>
                                            <td><center><b><span id="pure_total" style="font-size: 18px; color: blue;"><?php echo @number_format($total,2); ?></span></b></center></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Customer Details
                        </div>

                        <div class="panel-body">
                            <form method="post" id="my_trans_form" enctype="multipart/form-data" action="process_transaction" onsubmit="return validateForm(this);">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Transaction</span>
                                            <input type="text" name="my_trans_code" class="form-control" value="<?php echo $my_trans_code; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Salesman</span>
                                            <select class="form-control" name="my_prepared_by" required>
                                                <?php echo $my_salesman_val; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <!-- <label>Customer Name (alt + 2)</label> -->
                                            <input type="text" name="my_cust_name" list="res_name" id="my_cust_name" value="<?php echo @$new_info_row['gy_trans_custname']; ?>" accesskey="2" autocomplete="off" class="form-control" placeholder="Customer Name (alt + 2)" required>
                                            <datalist id="res_name"></datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <!-- <label>Cash (alt + 3)</label> -->
                                            <input type="number" name="my_cash" id="my_cash" min="<?php echo $total; ?>" onkeyup="get_the_change()" step="0.01" autocomplete="off" accesskey="3" class="form-control" placeholder="CASH (alt + 3)" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <!-- <label>Change</label> -->
                                            <input type="number" name="my_change" id="my_change" step="0.01" autocomplete="off" placeholder="CHANGE" class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" name="process" id="process" class="btn btn-info" style="width: 100%; border-radius: 0px;">Submit Transaction</button>
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

    <?php include 'footer.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
      
            formObj.process.disabled = true; 
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

    <script type="text/javascript">
        function get_the_change(){
            var cash = document.getElementById('my_cash').value;
            var total = <?php echo 0 + $total; ?>;

            var change = parseFloat(cash) - parseFloat(total);

            var true_change = Math.round(change * 100) / 100;

            if (!isNaN(true_change)) {
                document.getElementById('my_change').value = true_change;
            }
        }
    </script>

</body>

</html>
