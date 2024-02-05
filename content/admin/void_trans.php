<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Void Transaction List";

    $my_notification = @$_GET['note'];

    $date_now = date("Y-m-d");

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Sale Report is removed";
    }else if ($my_notification == "error") {
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
    }else if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty Date Input";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $gettransdata=$link->query("Select * From `gy_void`");
    $counttransdata=$gettransdata->num_rows;

    $query_one = "Select * From `gy_void` Order By `gy_trans_date` DESC";

    $query_two = "Select COUNT(`gy_void_id`) From `gy_void` Order By `gy_trans_date` DESC";

    $query_three = "Select * From `gy_void` Order By `gy_trans_date` DESC ";

    $my_num_rows = 50;

    include 'my_pagination.php';
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
                    <h3 class="page-header"><i class="fa fa-times-circle"></i> <?php echo $my_project_header_title; ?></h3>
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
                                    <input type="text" class="form-control" placeholder="Search for Trasaction Code/Customer Name ..." name="void_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="void_date_search_f" id="void_date_search1" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="void_date_search_t" id="void_date_search2" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="submit" name="void_btn" class="btn btn-success" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Void Transactions Data Table <b><?php echo $counttransdata; ?></b> result(s)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Type</center></th>
                                            <th><center>Trans. Code</center></th>
                                            <th><center>Customer Name</center></th>
                                            <th><center>Date</center></th>
                                            <th><center>Details</center></th>
                                            <th><center>Branch</center></th>
                                            <th><center><i class="fa fa-unlock fa-fw"></i> Approved By</center></th>
                                            <!-- <th><center>Print Receipt (Thermal)</center></th>
                                            <th><center>Print Receipt</center></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($sales_row=$query->fetch_array()) {

                                            //get payment method
                                            if ($sales_row['gy_trans_pay'] == 0) {
                                                $method = "CASH";
                                                $check_num = "";
                                                $royal_per = "";
                                                $royal_fee = "";
                                                $my_change = $sales_row['gy_trans_change'];
                                                $my_check_amount = "";
                                            }else{
                                                $method = "CHEQUE";
                                                $check_num = $sales_row['gy_trans_check_num'];
                                                $royal_per = " (".$sales_row['gy_trans_check_per']."%)";

                                                $royal_fee = number_format(0 + $sales_row['gy_trans_royal_fee'],2);
                                                $my_change = $sales_row['gy_trans_change'] - $sales_row['gy_trans_royal_fee'];
                                                $my_check_amount = number_format(0 + $sales_row['gy_trans_cash'], 2);
                                            }

                                            if ($sales_row['gy_user_id'] != 0) {
                                                $my_panel_color = "yellow";
                                                $my_row_color = "warning";
                                                $my_void_Type = "TRANSACTION";
                                            }else{
                                                $my_panel_color = "primary";
                                                $my_row_color = "info";
                                                $my_void_Type = "ORDER";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_row_color; ?>">
                                            <td style="font-weight: bold;"><center><?php echo $my_void_Type; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i:s A",strtotime($sales_row['gy_trans_date'])); ?></center></td>
                                            <td><center><button type="button" class="btn btn-info" title="click to see details ..." data-target="#details_<?php echo $sales_row['gy_void_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td style="font-weight: bold;"><center><?php echo get_branch_name($sales_row['gy_branch_id']); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_void_by']; ?></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="details_<?php echo $sales_row['gy_void_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><?php echo $my_void_Type; ?> <u><?php echo $sales_row['gy_trans_code']; ?></u> Info</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-<?php echo $my_panel_color; ?>" style="border-radius: 0px;">
                                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                                            Transaction Info

                                                                            <?php  
                                                                                //get cashier info
                                                                                $cashier_identifier=$sales_row['gy_user_id'];
                                                                                $get_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$cashier_identifier'");
                                                                                $user_info_row=$get_user_info->fetch_array();


                                                                                $sales_identifier=$sales_row['gy_prepared_by'];
                                                                                $get_salesman=$link->query("Select * From `gy_user` Where `gy_user_id`='$sales_identifier'");
                                                                                $salesman_info_row=$get_salesman->fetch_array();
                                                                            ?>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p>
                                                                                ID: &nbsp;&nbsp;&nbsp;<u><?php echo $sales_row['gy_trans_code']; ?></u><br>
                                                                                Customer: &nbsp;&nbsp;&nbsp;<u><?php echo $sales_row['gy_trans_custname']; ?></u><br>
                                                                                Date: &nbsp;&nbsp;&nbsp;<u><?php echo date("F d, Y g:i:s A", strtotime($sales_row['gy_trans_date'])); ?></u><br>
                                                                                Prepared By: &nbsp;&nbsp;&nbsp;<u><?php echo $salesman_info_row['gy_full_name']; ?></u><br>
                                                                                Cashier: &nbsp;&nbsp;&nbsp;<u><?php echo $user_info_row['gy_full_name']; ?></u><br>
                                                                                Payment Method: &nbsp;&nbsp;&nbsp;<u><?php echo $method; ?></u><br>
                                                                                Chueqe #: &nbsp;&nbsp;&nbsp;<u><?php echo $check_num; ?></u><br>
                                                                                Royal Fee: &nbsp;&nbsp;&nbsp;<u><?php echo $royal_fee."".$royal_per; ?></u><br><br>

                                                                                Items: &nbsp;&nbsp;&nbsp;<br/>

                                                                                <?php  
                                                                                    //get items
                                                                                    //trans code
                                                                                    $total = "";
                                                                                    $my_tcode=$sales_row['gy_trans_code'];
                                                                                    $get_items=$link->query("Select * From `gy_void_details` LEFT JOIN `gy_products` On `gy_void_details`.`gy_product_id`=`gy_products`.`gy_product_id` Where `gy_void_details`.`gy_trans_code`='$my_tcode' Order By `gy_products`.`gy_product_price_srp` DESC");
                                                                                    while ($item_row=$get_items->fetch_array()) {
                                                                                        //remain zero if the discount is negative
                                                                                        if ($item_row['gy_product_discount'] <= 0) {
                                                                                            $my_discount_val = 0;
                                                                                        }else{
                                                                                            $my_discount_val = $item_row['gy_product_discount'];
                                                                                        }

                                                                                        $my_subtotal = $item_row['gy_product_price'] * $item_row['gy_trans_quantity'];

                                                                                        @$total += $my_subtotal;

                                                                                        echo "
                                                                                            {$item_row['gy_product_code']} &nbsp;&nbsp;&nbsp; ".substr($item_row['gy_product_name'], 0, 35)." (".number_format(0 + $item_row['gy_product_price'],2)."(<i>-".$my_discount_val."</i>) x ".$item_row['gy_trans_quantity'].") &nbsp;&nbsp;&nbsp; <span style='float: right;'><b>".number_format(0 + $my_subtotal,2)."</span></b><br/>
                                                                                        ";
                                                                                    }
                                                                                ?>
                                                                                <br/>
                                                                                <span style='float: right;'>Royal Fee: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format(0 + $sales_row['gy_trans_royal_fee'],2); ?></b></u></span><br/>
                                                                                <span style='float: right;'>Total: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format(0 + $total,2); ?></b></u></span><br/>
                                                                                <span style='float: right;'>Cash: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format(0 + $sales_row['gy_trans_cash'],2); ?></b></u></span><br/>
                                                                                <span style='float: right; color: green; font-size: 25px;'>Change: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format(0 + $my_change,2); ?></b></u></span><br/><br/>
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

    <!-- <script type="text/javascript">
        $('#void_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
