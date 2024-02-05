<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Expenses Today";

    $date_now = words(date("Y-m-d"));

    //stock expenses

    $my_query_stocks=$link->query("Select SUM(`gy_exp_amount`) As `sum_stocks` From `gy_expenses` Where date(`gy_exp_date`)='$date_now' AND `gy_exp_type`!='CASH' Order By `gy_exp_date` DESC");

    $stocks=$my_query_stocks->fetch_array();

    //cash expenses

    $my_query_cash=$link->query("Select SUM(`gy_exp_amount`) As `sum_cash` From `gy_expenses` Where date(`gy_exp_date`)='$date_now' AND `gy_exp_type`='CASH' Order By `gy_exp_date` DESC");

    $cash=$my_query_cash->fetch_array();
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
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-credit-card"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-dropbox fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo number_format(0+$stocks['sum_stocks'],2); ?></div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                            <a href="expenses" title="click to show details ...">
                                <div class="panel-footer">
                                    <span class="pull-left">Overheads</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-credit-card fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo number_format(0+$cash['sum_cash'],2); ?></div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                            <a href="expenses_cash" title="not available for now ...">
                                <div class="panel-footer">
                                    <span class="pull-left">Cost of Sales</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
