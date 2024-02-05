<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Transaction Summary";

    $my_notification = @$_GET['note'];

    $my_dir_value = @$_GET['cd'];  

    if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "error_printer") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "No POS Printer Detected";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    //get trans details
    $get_trans_details=$link->query("Select * From `gy_tra` LEFT JOIN `gy_accounts` ON `gy_tra`.`gy_trans_custname`=`gy_accounts`.`gy_acc_name` Where `gy_tra`.`gy_trans_code`='$my_dir_value'");
    $trans_row=$get_trans_details->fetch_array();

    //get payment method
    if ($trans_row['gy_trans_pay'] == 0) {
        $method = "TRA";
        $check_num = "";
        $royal_per = "";
        $royal_fee = "";
        $my_change = $trans_row['gy_trans_change'];
        $my_check_amount = "";
    }else{
        $method = "CHEQUE";
        $check_num = $trans_row['gy_trans_check_num'];
        $royal_per = " (".$trans_row['gy_trans_check_per']."%)";

        $royal_fee = number_format(0 + $trans_row['gy_trans_royal_fee'],2);
        $my_change = $trans_row['gy_trans_change'] - $trans_row['gy_trans_royal_fee'];
        $my_check_amount = number_format($trans_row['gy_trans_cash'], 2);
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
                <div class="col-md-9">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <p style="font-size: 20px;">TRA Code: <b><?php echo $trans_row['gy_trans_code']; ?></b></p>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr style="background-color: rgba(0,0,0,0.8); color: white;">
                                            <th><center>Account</center></th>
                                            <th><center>Payment Method</center></th>
                                            <th><center>Cheque #</center></th>
                                            <th><center>Cheque Amount</center></th>
                                            <th><center>Royal Fee</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-weight: bold; text-transform: uppercase;"><center><?php echo $trans_row['gy_trans_custname']; ?></center></td>
                                            <td><center><?php echo $method; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $check_num; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $my_check_amount; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $royal_fee."".$royal_per; ?></center></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr style="background-color: rgba(0,0,0,0.8); color: white;">
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Price</center></th>
                                            <th><center>Discount</center></th>
                                            <th><center>Qty</center></th>
                                            <th><center>SubTotal</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php  
                                            $total = "";
                                            $my_tcode=$trans_row['gy_trans_code'];
                                            $get_items=$link->query("Select * From `gy_tra_details` LEFT JOIN `gy_products` On `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$my_tcode' Order By `gy_products`.`gy_product_price_srp` DESC");
                                            while ($item_row=$get_items->fetch_array()) {

                                                //remain zero if the discount is negative
                                                if ($item_row['gy_product_discount'] <= 0) {
                                                    $my_discount_val = 0;
                                                }else{
                                                    $my_discount_val = $item_row['gy_product_discount'];
                                                }

                                                $my_subtotal = $item_row['gy_product_price'] * $item_row['gy_trans_quantity'];

                                                @$total += $my_subtotal;
                                        ?>

                                        <tr>
                                            <td><center><?php echo $item_row['gy_product_code']; ?></center></td>
                                            <td><center><?php echo $item_row['gy_product_name']; ?></center></td>
                                            <td><center><?php echo number_format(0 + $item_row['gy_product_price'], 2); ?></center></td>
                                            <td><center><?php echo number_format(0 + $my_discount_val, 2); ?></center></td>
                                            <td><center><?php echo $item_row['gy_trans_quantity']." ".$item_row['gy_product_unit']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo number_format(0 + $my_subtotal, 2); ?></center></td>
                                        </tr>

                                        <?php } ?>

                                        <tr>
                                            <td colspan="4"></td>
                                            <td style="font-weight: bold; font-size: 18px; background-color: rgba(0,0,0,0.8); color: white;"><center>Total</center></td>
                                            <td style="font-weight: bold; font-size: 18px; background-color: rgba(0,0,0,0.8); color: white;"><center><?php echo number_format(0 + $total, 2); ?></center></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                 <button type="button" data-toggle="modal" data-target="#print" class="btn btn-success" style="border-radius: 0px;" title="click to print ..." accesskey="p"><i class="fa fa-print fa-fw"></i> Print Receipt - Thermal</button>
                            </div>
                        </div>
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                 <a href="print_tra_receipt?cd=<?php #echo $my_dir_value; ?> " onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;" id="print_pop"><button type="button" class="btn btn-success" style="border-radius: 0px;" title="click to print ..." accesskey="p"><i class="fa fa-print fa-fw"></i> Print Receipt (alt + p)</button></a>
                            </div>
                        </div> -->
                         <div class="col-md-12">
                            <div class="form-group">
                                <a href="tra_counter"><button type="button" class="btn btn-primary" style="border-radius: 0px;" title="click to go to cashier ..."><i class="fa fa-desktop fa-fw" accesskey="1"></i> Counter (alt + 1)</button></a>
                            </div>
                        </div>  
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                 <button type="button" class="btn btn-info" style="border-radius: 0px;" title="Input Partial Payment ..." accesskey="2"><i class="fa fa-list fa-fw"></i> Patrial Payment (alt + 2)</button></a>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- ADMIN PIN -->

                <div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-money fa-fw"></i> Print Receipt Permission <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="print_receipt_thermal_trans?cd=<?php echo $my_dir_value; ?>">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label style="color: blue;">ADMIN PIN</label>
                                                <input type="password" class="form-control" name="my_secure_pin" placeholder="number here ..." autofocus required>
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
        //for hyperkink
        var lnk = document.getElementById('print_thermal');

        if (window.addEventListener) {
            document.addEventListener('click', function (e) {
                if (e.target.id === lnk.id) {
                    e.preventDefault();         
                }
            });
        }

        //for submit button
        //$("#print_thermal").attr("disabled", true);
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#suggest").keyup(function(){
                $.get("live_search", {product_search: $(this).val()}, function(data){
                    $("datalist").empty();
                    $("datalist").html(data);
                });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#my_account_code").keyup(function(){
                $.get("live_search_account_code", {my_account_code: $(this).val()}, function(data){
                    $("#my_cust_name").empty();
                    $("#my_cust_name").val(data);
                });
            });
        });
    </script>

    <script type="text/javascript">
        function get_the_change(){
            var cash = document.getElementById('my_cash').value;
            var total = <?php echo $total; ?>;

            var change = parseFloat(cash) - parseFloat(total);

            if (!isNaN(change)) {
                document.getElementById('my_change').value=change;
            }
        }
    </script>

</body>

</html>
