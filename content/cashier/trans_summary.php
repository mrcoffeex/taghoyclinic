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
    $get_trans_details=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
    $trans_row=$get_trans_details->fetch_array();
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
                            <p style="font-size: 20px;">Transaction Code: <b><?php echo $trans_row['gy_trans_code']; ?></b></p>
                        </div>

                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="background-color: rgba(0,0,0,0.8); color: white; font-size: 20px;">Customer: &nbsp;&nbsp;<span style="text-transform: uppercase;"><?php echo $trans_row['gy_trans_custname']; ?></span></th>
                                        </tr>
                                    </thead>
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
                                            $get_items=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_id`=`gy_products`.`gy_product_id` Where `gy_trans_details`.`gy_trans_code`='$my_tcode' AND `gy_products`.`gy_branch_id`='$user_branch_id' Order By `gy_products`.`gy_product_price_srp` DESC");
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
                                            <td><center><?php echo $item_row['gy_product_name']." ".$item_row['gy_product_desc']; ?></center></td>
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

                                        <tr>
                                            <td colspan="4"></td>
                                            <td style="font-weight: bold; font-size: 18px; background-color: rgba(0,0,0,0.8); color: white;"><center>Cash</center></td>
                                            <td style="font-weight: bold; font-size: 18px; background-color: rgba(0,0,0,0.8); color: white;"><center><?php echo number_format(0 + $trans_row['gy_trans_cash'],2); ?></center></td>
                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                            <td style="font-weight: bold; font-size: 25px; background-color: rgba(0,0,0,0.8); color: rgb(0,255,64);"><center>Change</center></td>
                                            <td style="font-weight: bold; font-size: 25px; background-color: rgba(0,0,0,0.8); color: rgb(0,255,64);"><center><?php echo number_format(0 + $trans_row['gy_trans_change'], 2); ?></center></td>
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
                                <a href="cashier"><button type="button" class="btn btn-primary btn-lg" style="border-radius: 0px;" title="click to go to cashier ..."><i class="fa fa-desktop fa-fw" accesskey="1"></i> Cashier (alt + 1)</button></a>
                            </div>
                        </div>  
                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                 <a href="index"><button type="button" class="btn btn-danger btn-lg" style="border-radius: 0px;" title="click to go to order list ..." accesskey="2"><i class="fa fa-list fa-fw"></i> Order List (alt + 2)</button></a>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- ADMIN PIN -->

                <div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-money fa-fw"></i> Print Receipt Permission </h4>
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
