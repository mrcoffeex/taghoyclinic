<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    header("location: cashier");

    $my_project_header_title = "Order List";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Transaction was removed";
    }else if ($my_notification == "sold") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Items Sold";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $my_query=$link->query("Select * From `gy_transaction` Where `gy_trans_status`='1' AND `gy_trans_type`='1' AND `gy_user_id`='0' Order By `gy_trans_code` ASC");

    $count_results=$my_query->num_rows;
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
                <div class="col-lg-8">
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?> - <small>refresh results in <span style="color: blue;" id="countdown"></span> second(s)</small></h3>
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
                            <!-- Search Engine -->
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Trans Code/Customer Name ..." accesskey="1" name="search_trans" style="border-radius: 0px;" autocomplete="off" required>
                                </form>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <a href="cashier"><button type="button" class="btn btn-primary" title="click to open POS ..."><i class="fa fa-desktop fa-fw"></i> OPEN POS</button></a>
                        </div> -->
                        <hr>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Order Data Table <b><?php echo $count_results; ?> result(s)</b>
                            <span style="float: right;"> Press <button type="button" onclick="location.reload();" class="btn btn-primary btn-xs" title="click to relaod page ...">F5</button> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Void</center></th>
                                            <th><center>Trans. Code</center></th>
                                            <th><center>Customer Name</center></th>
                                            <th><center>Date Issued</center></th>
                                            <th><center>Process</center></th>
                                        </tr>
                                    </thead>
                                    <tbody id="autofresh">

                                    <?php

                                    $my_query=$link->query("Select * From `gy_transaction` Where `gy_trans_status`='1' AND `gy_trans_type`='1' AND `gy_user_id`='0' Order By `gy_trans_code` ASC");

                                        //get products
                                        //make pagination
                                        while ($quotation_row=$my_query->fetch_array()) {

                                    ?>

                                        <tr>
                                            <td><center><button type="button" class="btn btn-danger" title="click to delete quotation form" data-target="#delete_<?php echo $quotation_row['gy_trans_code']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $quotation_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $quotation_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i:s A",strtotime($quotation_row['gy_trans_date'])); ?></center></td>
                                            <td><center><a href="cashier?cd=<?php echo $quotation_row['gy_trans_code']; ?>"><button type="button" class="btn btn-primary" title="click to process form"><i class="fa fa-angle-right fa-fw"></i></button></a></center></td>
                                        </tr>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $quotation_row['gy_trans_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Do You want to remove Transaction ID: <span style="color: cyan;"><?php echo $quotation_row['gy_trans_code']; ?></span> on the list?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_order?cd=<?php echo $quotation_row['gy_trans_code']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> ADMIN PIN</label>
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
        (function countdown(remaining) {
            if(remaining <= 0)
                location.reload(true);
            document.getElementById('countdown').innerHTML = remaining;
            setTimeout(function(){ countdown(remaining - 1); }, 1000);
        })(20); // 30 seconds
    </script>

</body>

</html>
