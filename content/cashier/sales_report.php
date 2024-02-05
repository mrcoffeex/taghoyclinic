<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Sales Report";

    $date_now = date("Y-m-d");

    //get number of transactions
    $get_trans_quantity=$link->query("SELECT * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_user_id`='$user_id'");
    $total_trans_num=$get_trans_quantity->num_rows;

    $total_ref_rep="";
    $get_ref_summ=$link->query("SELECT * From `gy_refund` Where date(`gy_refund_date`)='$date_now' AND `gy_user_id`='$user_id'");
    while ($ref_summ_row=$get_ref_summ->fetch_array()) {

        @$total_ref_rep += $ref_summ_row['gy_product_price'] * $ref_summ_row['gy_product_quantity'];
    }

    //total expenses
    $totalExpenses = getTotalExpenses($date_now, $date_now, $user_id);

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
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?> <a href="print_sales_report" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-success" title="click to print sales report ..."><i class="fa fa-print fa-fw"></i> Print</button></a></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                    <?php  
                        //get salesman
                        $get_salesman_info=$link->query("Select DISTINCT(`gy_prepared_by`) As `my_salesman` From `gy_transaction` Where `gy_transaction`.`gy_user_id`='$user_id' AND date(`gy_transaction`.`gy_trans_date`)='$date_now' Order By `gy_transaction`.`gy_prepared_by` ASC");
                        $count_salesman=$get_salesman_info->num_rows;
                        while ($salesman_info_row=$get_salesman_info->fetch_array()) {
                            
                            $mysalesmandataguide=words($salesman_info_row['my_salesman']);
                            $getsalesmandata=$link->query("Select * From `gy_user` Where `gy_user_id`='$mysalesmandataguide'");
                            $salesmandatarow=$getsalesmandata->fetch_array();

                            //id values
                            $salesman_identity=words($salesman_info_row['my_salesman']);

                            //get sales reports by salesman
                            $query_one=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' AND `gy_user_id`='$user_id' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_code` ASC");

                            $order_no=0;
                            $my_final_total=0;

                            $count_results=$query_one->num_rows;

                    ?>
                    <div class="col-md-4">
                        <div class="panel panel-default" style="border-radius: 0px;">
                            <div class="panel-heading" style="border-radius: 0px;">
                              <b><span style="color: blue;"><?php echo $salesmandatarow['gy_full_name']; ?></span></b> Sales Data Table - <b><?php echo 0+$count_results; ?> result(s)</b>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><center>No.</center></th>
                                                <th><center>TransCode</center></th>
                                                <th><center>Total</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <?php  
                                            //get products
                                            //make pagination
                                            while ($data_row=$query_one->fetch_array()) {

                                                @$order_no += 1;
                                                @$my_final_total += $data_row['gy_trans_total'];

                                                //get check trans
                                                if ($data_row['gy_trans_pay'] == 1) {
                                                    $data_row_color = "#de9100";
                                                }else if ($data_row['gy_trans_pay'] == 2) {
                                                    $data_row_color = "#0000ff";
                                                }else{
                                                    $data_row_color = "#000";
                                                }
                                        ?>

                                            <tr>
                                                <td style="font-weight: bold; color: <?php echo $data_row_color; ?>;"><center><?php echo $order_no; ?></center></td>
                                                <td style="font-weight: bold; color: <?php echo $data_row_color; ?>;"><center><?php echo $data_row['gy_trans_code']; ?></center></td>
                                                <td style="font-weight: bold; color: <?php echo $data_row_color; ?>;"><center><?php echo number_format($data_row['gy_trans_total'],2); ?></center></td>
                                            </tr>

                                        <?php } ?>

                                        <tr>
                                            <td style="font-weight: bold; color: blue;"><center><?php echo $order_no; ?></center></td>
                                            <td style="font-weight: bold; color: blue;"><center>Total</center></td>
                                            <td style="font-weight: bold; color: blue;"><center><?php echo @number_format(0+$my_final_total,2); ?></center></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        //total sales
                        $grand_total=0;
                        $get_total_sales=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id'");
                        while ($total_sales_row=$get_total_sales->fetch_array()) {
                            @$grand_total += $total_sales_row['gy_trans_total'];
                        }
                    ?>
                    <?php } ?>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="2" style="font-weight: bold; color: green;"><center>GROSS SALES</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                        //get salesman
                                        $get_salesman_info=$link->query("Select DISTINCT(`gy_prepared_by`) As `my_salesman` From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_code` ASC");
                                        $count_salesman=$get_salesman_info->num_rows;
                                        while ($salesman_info_row=$get_salesman_info->fetch_array()) {

                                            //id values
                                            $salesman_identity=words($salesman_info_row['my_salesman']);

                                            ///get salesman info
                                            $getsalesmanfullname=$link->query("Select * From `gy_user` Where `gy_user_id`='$salesman_identity'");
                                            $salesnamerow=$getsalesmanfullname->fetch_array();

                                            //get sales reports by salesman
                                            $query_one=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' AND date(`gy_trans_date`)='$date_now' AND `gy_user_id`='$user_id' Order By `gy_trans_code` ASC");

                                            $order_no=0;
                                            $my_final_total=0;

                                            $count_results=$query_one->num_rows;
                                    ?>
                                            
                                            
                                            <?php 
                                                //total total sales and salesman
                                                $salesman_total=0;
                                                while ($final_row=$query_one->fetch_array()) {
                                                    @$salesman_total += $final_row['gy_trans_total'];
                                                }
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold; color: green;"><center><?php echo $salesnamerow['gy_full_name']; ?></center></td>
                                                <td style="font-weight: bold; color: green;"><center><?php echo @number_format(0+$salesman_total,2); ?></center></td>
                                            </tr>
                                    <?php } ?>
                                        <tr>
                                            <td style="font-weight: bold; color: green;"><center>TOTAL GROSS SALES</center></td>
                                            <td style="font-weight: bold; color: green; "><center><?php echo @number_format(0+$grand_total,2); ?></center></td>
                                        </tr>
                                    </tbody>
                            </table>                                     
                        </div>

                        <div class="col-md-4">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="3" style="font-weight: bold; color: red;"><center>Expenses</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                        //get salesman
                                        $totalExpenses=0;
                                        $getExpenses=selectExpenses($date_now, $date_now, $user_id);
                                        $countExpenses=$getExpenses->num_rows;
                                        while ($exp=$getExpenses->fetch_array()) {
                                            $totalExpenses += $exp['gy_exp_amount'];
                                    ?>
                                        <tr>
                                            <td style="font-weight: bold; color: red;"><center><?php echo $exp['gy_exp_date']; ?></center></td>
                                            <td style="font-weight: bold; color: red;"><center><?php echo @number_format(0 + $exp['gy_exp_amount'],2); ?></center></td>
                                            <td style="font-weight: bold; color: red;"><center><?php echo $exp['gy_exp_note']; ?></center></td>
                                        </tr>
                                    <?php } ?>
                                        <tr>
                                            <td style="font-weight: bold; color: red;"><center>TOTAL EXPENSES</center></td>
                                            <td style="font-weight: bold; color: red; "><center><?php echo @number_format(0+$totalExpenses,2); ?></center></td>
                                            <td style="font-weight: bold; color: red;">&nbsp;</td>
                                        </tr>
                                    </tbody>
                            </table>                                     
                        </div>

                        <div class="col-md-4">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="2" style="font-weight: bold;"><center>SUMMARY</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-weight: bold; color: blue;"><center>No. of Transactions</center></td>
                                        <td style="font-weight: bold; color: blue;"><center><?php echo 0+$total_trans_num; ?></center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: green;"><center>TOTAL GROSS SALES</center></td>
                                        <td style="font-weight: bold; color: green; "><center><?php echo @number_format(0 + $grand_total,2); ?></center></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold; color: red;"><center>EXPENSES</center></td>
                                        <td style="font-weight: bold; color: red;"><center><?php echo @number_format(0 + $totalExpenses,2); ?></center></td>
                                    </tr> 
                                    <tr>
                                        <td style="font-weight: bold; color: red;"><center>REPLACE/REFUND</center></td>
                                        <td style="font-weight: bold; color: red;"><center><?php echo @number_format(0 + $total_ref_rep,2); ?></center></td>
                                    </tr>   
                                    <tr>
                                        <td style="font-weight: bold; color: blue;"><center>NET SALES</center></td>
                                        <td style="font-weight: bold; color: blue;"><center><?php echo @number_format((0 + $grand_total) - ($totalExpenses + $total_ref_rep),2); ?></center></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
