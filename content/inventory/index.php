<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Dashboard";

    $my_notification = @$_GET['cd'];

    if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Your Profile is Updated.";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here.";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    //my scripts

    $date_nows = date("Y-m-d");

    //count product numbers
    $products=$link->query("Select * From `gy_products`");
    $count_products=$products->num_rows;

    //count supplier numbers
    $supplier=$link->query("Select * From `gy_supplier`");
    $count_suppliers=$supplier->num_rows;

    //count user numbers
    $users=$link->query("Select * From `gy_user` Where `gy_user_type`!='0' AND `gy_user_status`='0' Order By `gy_user_id` ASC");
    $count_users=$users->num_rows;

    //count quotation forms numbers
    $forms=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='0' AND `gy_trans_status`='1'");
    $count_forms=$forms->num_rows;

    //count restock numbers
    $restock=$link->query("Select * From `gy_products` Where `gy_product_quantity`<=`gy_product_restock_limit`");
    $count_restock=$restock->num_rows;

    //query
    $my_sales_query = "Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_nows' AND `gy_user_id`!='0'";

    //count product numbers
    $sales_reports_today_c=$link->query($my_sales_query);
    $counte_sales=$sales_reports_today_c->num_rows;

    //count product numbers
    $total_sales="";

    $sales_reports_today=$link->query($my_sales_query);
    while ($sales_row=$sales_reports_today->fetch_array()) {

        @$total_sales += $sales_row['gy_trans_total'];
    } 

    //get the restock summary today
    $get_restock_summary=$link->query("Select * From `gy_restock` Where `gy_restock_status`='1' AND date(`gy_restock_date`)='$date_nows'");
    $restock_summ_count=$get_restock_summary->num_rows;

    //get the pullout summary today
    $get_pullout_summary=$link->query("Select * From `gy_pullout` Where `gy_pullout_status`='1' AND date(`gy_pullout_date`)='$date_nows' AND `gy_pullout_type`!='BACK_ORDER'");
    $pullout_summ_count=$get_pullout_summary->num_rows;


    //get the back-order summary today
    $get_backorder_summary=$link->query("Select * From `gy_pullout` Where `gy_pullout_status`='1' AND `gy_pullout_type`='BACK_ORDER'");
    $backorder_summ_count=$get_backorder_summary->num_rows;


    //get the pullout summary today
    $get_transfer_summary=$link->query("Select * From `gy_stock_transfer` Where `gy_transfer_status`='1' AND date(`gy_transfer_date`)='$date_nows'");
    $transfer_summ_count=$get_transfer_summary->num_rows;

    $my_query_stocks=$link->query("Select SUM(`gy_exp_amount`) As `sum_stocks` From `gy_expenses` Where date(`gy_exp_date`)='$date_nows' AND `gy_exp_type`='CASH' Order By `gy_exp_date` DESC");

    $stocks=$my_query_stocks->fetch_array();

    //cash expenses

    $my_query_cash=$link->query("Select SUM(`gy_exp_amount`) As `sum_cash` From `gy_expenses` Where date(`gy_exp_date`)='$date_nows' AND `gy_exp_type`='CASH' Order By `gy_exp_date` DESC");

    $cash=$my_query_cash->fetch_array();

    //void trans
    $voids=$link->query("Select * From `gy_void` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`>'0' AND date(`gy_trans_date`)='$date_nows' Order By `gy_trans_date` DESC");
    $count_voids=$voids->num_rows;

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
                    <h3 class="page-header" style="text-transform: uppercase;"><i class="fa fa-dashboard"></i> <?php echo $my_project_header_title; ?></h3>
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
                        <div class="col-md-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-dropbox fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo number_format(0+$count_products); ?></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" data-toggle="modal" data-target="#products_search" title="click to search ...">
                                    <div class="panel-footer">
                                        <span class="pull-left">Products</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-info-circle fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo number_format(0+$count_restock); ?></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="restock_alerts" title="click to view re-stock alerts ...">
                                    <div class="panel-footer">
                                        <span class="pull-left">Re-Stock Alert</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-plus fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo 0+$restock_summ_count; ?></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="restock_reports" title="click to open re-stock summary reports ...">
                                    <div class="panel-footer">
                                        <span class="pull-left">Re-Stock Summary</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-times fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo 0+$pullout_summ_count; ?></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="pullout_reports" title="click to open pull-out summary reports ...">
                                    <div class="panel-footer">
                                        <span class="pull-left">Pull-Out Summary</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Modal -->

                <div class="modal fade" id="products_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" tabindex="-1" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-search fa-fw"></i> Product Search <small style="color: #337ab7;">(press TAB to type/press ENTER to search)</small></h4>
                            </div>
                            <div class="modal-body">
                                <div class="panel-body">
                                    <div class="row">
                                        <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" name="product_search" class="form-control" placeholder="Search for Product Bar Code/Product Name/Category/Supplier Name ..." autofocus required>
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
    </div>

    <?php include 'footer.php'; ?>
    <?php include '_charts.php'; ?>

</body>

</html>
