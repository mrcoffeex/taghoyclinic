<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $datef=@$_GET['datef'];
    $datet=@$_GET['datet'];

    if ($datef == $datet) {
        $my_project_header_title = "Search Transaction: ".date("M d, Y", strtotime($datef));
    }else{
        $my_project_header_title = "Search Transaction: ".date("M d", strtotime($datef))." - ".date("M d, Y", strtotime($datet));
    }

    //count results
    $count_search_results=$link->query("Select * From `gy_transaction` Where `gy_trans_status`='1' AND `gy_user_id`>'0' AND `gy_trans_type`='1' AND date(`gy_trans_date`) BETWEEN '$datef' AND '$datet'");
    $search_count=$count_search_results->num_rows;

    $query_one = "Select * From `gy_transaction` Where `gy_trans_status`='1' AND `gy_user_id`>'0' AND `gy_trans_type`='1' AND date(`gy_trans_date`) BETWEEN '$datef' AND '$datet' Order By `gy_trans_date` ASC";

    $query_two = "Select COUNT(`gy_trans_id`) From `gy_transaction` Where `gy_trans_status`='1' AND `gy_user_id`>'0' AND `gy_trans_type`='1' AND date(`gy_trans_date`) BETWEEN '$datef' AND '$datet' Order By `gy_trans_date` ASC";

    $query_three = "Select * From `gy_transaction` Where `gy_trans_status`='1' AND `gy_user_id`>'0' AND `gy_trans_type`='1' AND date(`gy_trans_date`) BETWEEN '$datef' AND '$datet' Order By `gy_trans_date` ASC ";

    $my_num_rows = 25;

    include 'my_pagination_dates.php';
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
                    <h3 class="page-header"><i class="fa fa-search"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Search Engine -->
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Trasaction Code/Customer Name ..." name="sales_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>    
                        <div class="col-md-12">
                            <div class="row">
                                <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="sales_date_search_f" id="sales_date_search1" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="sales_date_search_t" id="sales_date_search2" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" name="sales_btn" class="btn btn-success" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Transaction Data Table 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Trans. Code</center></th>
                                            <th><center>Customer Name</center></th>
                                            <th><center>Date</center></th>
                                            <th><center>Details</center></th>
                                            <th><center>Branch</center></th>
                                            <th><center>Void</center></th>
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
                                            }else if ($sales_row['gy_trans_pay'] == 1) {
                                                $method = "CHEQUE";
                                                $check_num = $sales_row['gy_trans_check_num'];
                                                $royal_per = " (".$sales_row['gy_trans_check_per']."%)";

                                                $royal_fee = number_format(0 + $sales_row['gy_trans_royal_fee'],2);
                                                $my_change = $sales_row['gy_trans_change'] - $sales_row['gy_trans_royal_fee'];
                                                $my_check_amount = number_format($sales_row['gy_trans_cash'], 2);
                                            }else{
                                                $method = "CARD";
                                                $check_num = "";
                                                $royal_per = "";
                                                $royal_fee = "";
                                                $my_change = $sales_row['gy_trans_change'];
                                                $my_check_amount = "";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_limit; ?>">
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $sales_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i:s A",strtotime($sales_row['gy_trans_date'])); ?></center></td>
                                            <td><center><button type="button" class="btn btn-info" title="click to see details ..." data-target="#details_<?php echo $sales_row['gy_trans_code']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td style="font-weight: bold;"><center><?= get_branch_name($sales_row['gy_branch_id']); ?></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to void transaction..." data-target="#delete_<?php echo $sales_row['gy_trans_code']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="details_<?php echo $sales_row['gy_trans_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">Transaction <u><?php echo $sales_row['gy_trans_code']; ?></u> Info</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-yellow" style="border-radius: 0px;">
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
                                                                                    $get_items=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_id`=`gy_products`.`gy_product_id` Where `gy_trans_details`.`gy_trans_code`='$my_tcode' Order By `gy_products`.`gy_product_price_srp` DESC");
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
                                                                                            {$item_row['gy_product_code']} &nbsp;&nbsp;&nbsp; ".substr($item_row['gy_product_name'], 0, 35)." (".number_format($item_row['gy_product_price'],2)."(<i>-".$my_discount_val."</i>) x ".$item_row['gy_trans_quantity'].") &nbsp;&nbsp;&nbsp; <span style='float: right;'><b>".number_format($my_subtotal,2)."</span></b><br/>
                                                                                        ";
                                                                                    }
                                                                                ?>
                                                                                <br/>
                                                                                <span style='float: right;'>Royal Fee: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format(0 + $sales_row['gy_trans_royal_fee'],2); ?></b></u></span><br/>
                                                                                <span style='float: right;'>Total: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format($total,2); ?></b></u></span><br/>
                                                                                <span style='float: right;'>Cash: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format($sales_row['gy_trans_cash'],2); ?></b></u></span><br/>
                                                                                <span style='float: right; color: green; font-size: 25px;'>Change: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format($my_change,2); ?></b></u></span><br/><br/>
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

                                        <div class="modal fade" id="delete_<?php echo $sales_row['gy_trans_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void Transaction Code - <?php echo $sales_row['gy_trans_code']; ?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_sales?cd=<?php echo $sales_row['gy_trans_code']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> Void Secure PIN</label>
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
        $('#sales_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
