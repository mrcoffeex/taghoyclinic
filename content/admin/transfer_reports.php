<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Stock-Transfer Report Today";

    $my_notification = @$_GET['note'];

    $date_now = date("Y-m-d");

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Stock-Transfer Report is removed";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $my_query=$link->query("Select * From `gy_stock_transfer` Where date(`gy_transfer_date`)='$date_now' Order By `gy_transfer_date` DESC");

    $count_results=$my_query->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-truck"></i> <?php echo $my_project_header_title; ?></h3>
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
                                    <input type="text" class="form-control" placeholder="Search for Stock-Transfer Code ..." name="transfer_entry_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <form method="post" id="my_form" enctype="multipart/form-data" action="redirect_manager">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control" name="transfer_branch_search" id="transfer_branch_search" required>
                                                <option></option>
                                                <option value="">All Branch</option>
                                                <?php  
                                                    //get branches
                                                    $get_branch=$link->query("Select * From `gy_branch`");
                                                    while ($branch_row=$get_branch->fetch_array()) {
                                                ?>
                                                <option value="<?php echo $branch_row['gy_branch_id']; ?>"><?php echo $branch_row['gy_branch_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="transfer_date_search_f" id="transfer_date_search1" style="border-radius: 0px;" required>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="transfer_date_search_t" id="transfer_date_search2" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" name="transfer_btn" class="btn btn-success" title="click to search stock transfer data by date ..."><i class="fa fa-search"></i> Search</button>
                                    </div>  
                                </form> 
                            </div>
                        </div>                  
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Stock-Transfer Data Table <b><?php echo 0+$count_results; ?></b> result(s) <a href="print_tsummary?datef=<?php echo date('Y-m-d'); ?>&datet=<?php echo date('Y-m-d'); ?>&mode=date_search" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-success" title="click to print result ..."><i class="fa fa-print"></i> Print</button></a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Date</center></th>
                                            <th style="color: blue;"><center>Code</center></th>
                                            <th><center>Product Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Amount</center></th>
                                            <th><center>Note</center></th>
                                            <th><center>User</center></th>
                                            <th style="color: green;"><center>Branch</center></th>
                                            <th><center>Transfer To</center></th>
                                            <th><center>Void</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        $total_amount="";
                                        while ($transfer_row=$my_query->fetch_array()) {
                                            //get product details
                                            $my_code = words($transfer_row['gy_product_id']);
                                            $get_details=$link->query("Select * From `gy_products` Where `gy_product_id`='$my_code'");
                                            $details_row=$get_details->fetch_array();

                                            //get user info
                                            $my_user = words($transfer_row['gy_transfer_by']);
                                            $get_my_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user'");
                                            $my_user_row=$get_my_user_info->fetch_array();

                                            //get branch
                                            $my_branch = words($transfer_row['gy_branch_id']);
                                            $get_branch=$link->query("Select * From `gy_branch` Where `gy_branch_id`='$my_branch'");
                                            $branch_row=$get_branch->fetch_array();

                                            @$total_amount += $transfer_row['gy_product_price_cap'] * $transfer_row['gy_transfer_quantity'];
                                    ?>

                                        <tr class="warning">
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i A",strtotime($transfer_row['gy_transfer_date'])); ?></center></td>
                                            <td style="font-weight: bold; color: blue;"><center><?php echo $transfer_row['gy_transfer_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $details_row['gy_product_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $transfer_row['gy_product_name']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo "<span style='color: blue;'>".$transfer_row['gy_transfer_quantity']."</span> ".$details_row['gy_product_unit']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo "<span style='color: green;'>".number_format($transfer_row['gy_transfer_quantity'] * $transfer_row['gy_product_price_cap'],2)."</span> "; ?></center></td>
                                            <td><center><button type="button" class="btn btn-warning" title="click to see notes ..." data-target="#note_<?php echo $transfer_row['gy_transfer_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td><center><?php echo $my_user_row['gy_full_name']; ?></center></td>
                                            <td style="font-weight: bold; color: green;"><center><?php echo get_branch_name($transfer_row['gy_branch_from']); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $branch_row['gy_branch_name']; ?></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to void the Stock-Transfer summary ..." data-target="#void_<?php echo $transfer_row['gy_transfer_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="note_<?php echo $transfer_row['gy_transfer_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">Note</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-warning" style="border-radius: 0px;">
                                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                                            Transfer By: <b><?php echo $my_user_row['gy_full_name']; ?></b>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p style="text-align: justify;">
                                                                                <?php echo $transfer_row['gy_transfer_note']; ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="void_<?php echo $transfer_row['gy_transfer_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void Stock-Transfer Data <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="void_transfer_summ?cd=<?php echo $transfer_row['gy_transfer_id']; ?>">
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
                                    <tr>
                                        <td colspan="5"><b><center>Total Amount</center></b></td>
                                        <td><b><center><?php echo @number_format($total_amount,2); ?></center></b></td>
                                        <td colspan="5"></td>
                                    </tr>
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

    <!-- <script type="text/javascript">
        $('#transfer_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
