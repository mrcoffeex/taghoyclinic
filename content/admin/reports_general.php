<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Reports Today";

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

    //get the pullout summary today
    $get_transfer_summary=$link->query("Select * From `gy_stock_transfer` Where `gy_transfer_status`='1' AND date(`gy_transfer_date`)='$date_nows'");
    $transfer_summ_count=$get_transfer_summary->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></h3>
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
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-file-text-o fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo 0+$counte_sales; ?></div>
                                            <div><?php echo @number_format(0+$total_sales,2); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="sales_report" title="click to open sales ...">
                                    <div class="panel-footer">
                                        <span class="pull-left">Sales Reports</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
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

                        <!-- <div class="col-md-3">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-truck fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php #echo 0+$transfer_summ_count; ?></div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                                <a href="transfer_reports" title="click to open stock-transfer summary reports ...">
                                    <div class="panel-footer">
                                        <span class="pull-left">Stock-Transfer Summary</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
