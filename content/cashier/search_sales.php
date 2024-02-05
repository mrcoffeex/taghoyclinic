<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $search_text = @$_GET['search_text'];
    $returndate = @$_GET['returndate'];

    if ($search_text == "" && $returndate == "0000-00-00") {
        header("location: sales?note=empty_search");
    }else if ($search_text != "" && $returndate == "") {
        $query_one=$link->query("SELECT `gy_transaction`.`gy_trans_custname`,`gy_transaction`.`gy_trans_code`, `gy_trans_details`.`gy_transdet_id`, `gy_trans_details`.`gy_transdet_date`, `gy_trans_details`.`gy_product_id`, `gy_trans_details`.`gy_product_price`, `gy_trans_details`.`gy_product_origprice`, `gy_trans_details`.`gy_product_discount`, `gy_trans_details`.`gy_trans_quantity`, `gy_trans_details`.`gy_trans_ref_rep_quantity`, `gy_trans_details`.`gy_trans_claim_quantity` From `gy_trans_details` LEFT JOIN `gy_transaction` On `gy_trans_details`.`gy_trans_code`=`gy_transaction`.`gy_trans_code` Where `gy_transaction`.`gy_trans_type`='1' AND `gy_transaction`.`gy_trans_status`='1' AND `gy_transdet_type`='1' AND `gy_transaction`.`gy_user_id`='$user_id' AND CONCAT(`gy_transaction`.`gy_trans_code`,`gy_transaction`.`gy_trans_custname`) LIKE '%$search_text%' Order By `gy_transaction`.`gy_trans_date` DESC");

        $my_project_header_title = "Search: ".$search_text;

    }else if ($search_text == "" && $returndate != "") {
        $query_one=$link->query("SELECT `gy_transaction`.`gy_trans_custname`,`gy_transaction`.`gy_trans_code`, `gy_trans_details`.`gy_transdet_id`, `gy_trans_details`.`gy_transdet_date`, `gy_trans_details`.`gy_product_id`, `gy_trans_details`.`gy_product_price`, `gy_trans_details`.`gy_product_origprice`, `gy_trans_details`.`gy_product_discount`, `gy_trans_details`.`gy_trans_quantity`, `gy_trans_details`.`gy_trans_ref_rep_quantity`, `gy_trans_details`.`gy_trans_claim_quantity` From `gy_trans_details` LEFT JOIN `gy_transaction` On `gy_trans_details`.`gy_trans_code`=`gy_transaction`.`gy_trans_code` Where `gy_transaction`.`gy_trans_type`='1' AND `gy_transaction`.`gy_trans_status`='1' AND `gy_transdet_type`='1' AND `gy_transaction`.`gy_user_id`='$user_id' AND date(`gy_transaction`.`gy_trans_date`)='$returndate' Order By `gy_transaction`.`gy_trans_date` DESC");

        $my_project_header_title = "Search: ".date("M d, Y", strtotime($returndate));

    }else{
        $query_one=$link->query("SELECT `gy_transaction`.`gy_trans_custname`,`gy_transaction`.`gy_trans_code`, `gy_trans_details`.`gy_transdet_id`, `gy_trans_details`.`gy_transdet_date`, `gy_trans_details`.`gy_product_id`, `gy_trans_details`.`gy_product_price`, `gy_trans_details`.`gy_product_origprice`, `gy_trans_details`.`gy_product_discount`, `gy_trans_details`.`gy_trans_quantity`, `gy_trans_details`.`gy_trans_ref_rep_quantity`, `gy_trans_details`.`gy_trans_claim_quantity` From `gy_trans_details` LEFT JOIN `gy_transaction` On `gy_trans_details`.`gy_trans_code`=`gy_transaction`.`gy_trans_code` Where `gy_transaction`.`gy_trans_type`='1' AND `gy_transaction`.`gy_trans_status`='1' AND `gy_transdet_type`='1' AND `gy_transaction`.`gy_user_id`='$user_id' AND date(`gy_transaction`.`gy_trans_date`)='$returndate' AND CONCAT(`gy_transaction`.`gy_trans_code`,`gy_transaction`.`gy_trans_custname`) LIKE '%$search_text%' Order By `gy_transaction`.`gy_trans_date` DESC");

        $my_project_header_title = "Search: ".date("M d, Y", strtotime($returndate))." - ".$search_text;

    }

    $my_final_retails="";
    $my_final_discount="";
    $my_final_total="";

    $count_results=$query_one->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="redirect_manager">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search_value" style="border-radius: 0px;" placeholder="search customer name/transaction number" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="search_return_date" style="border-radius: 0px;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" name="submit_sales_report_sales" class="btn btn-info" title="click to search"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Sales Data Table - <b><?php echo $count_results; ?> result(s)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Details</center></th>
                                            <th><center>Date</center></th>
                                            <th><center>TransCode</center></th>
                                            <th><center>Customer</center></th>
                                            <th><center>Qty</center></th>
                                            <th><center>RQty</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Retail</center></th>
                                            <th style="color: blue;"><center>Discount</center></th>
                                            <th style="color: green;"><center>Total</center></th>
                                            <th><center>Refund</center></th>
                                            <th><center>Replace</center></th>
                                            <!-- <th><center>Delete</center></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($data_row=$query_one->fetch_array()) {

                                            $my_trans_code=words($data_row['gy_trans_code']);
                                            $my_prod_id=words($data_row['gy_product_id']);

                                            //get customer name
                                            $get_custname=$link->query("Select `gy_product_code`,`gy_product_name`,`gy_product_name`,`gy_product_desc`,`gy_product_unit` From `gy_products` Where `gy_product_id`='$my_prod_id'");
                                            $productrow_row=$get_custname->fetch_array();

                                            //get prepared by

                                            $get_prepared_by=$link->query("Select `gy_full_name` From `gy_transaction` LEFT JOIN `gy_user` On `gy_transaction`.`gy_prepared_by`=`gy_user`.`gy_user_id` Where `gy_trans_code`='$my_trans_code'");
                                            $prepared_by_row=$get_prepared_by->fetch_array();

                                            //get cashier
                                            
                                            $get_cashier=$link->query("Select `gy_full_name` From `gy_transaction` LEFT JOIN `gy_user` On `gy_transaction`.`gy_user_id`=`gy_user`.`gy_user_id` Where `gy_trans_code`='$my_trans_code'");
                                            $cashier_row=$get_cashier->fetch_array();

                                            @$my_final_retails += $data_row['gy_product_price'] * $data_row['gy_trans_quantity'];
                                            @$my_final_discount += $data_row['gy_product_discount'];
                                            @$my_final_total = $my_final_retails;

                                            //remain zero if the discount is negative
                                            if ($data_row['gy_product_discount'] <= 0) {
                                                $my_discount_val = 0;
                                            }else{
                                                $my_discount_val = $data_row['gy_product_discount'];
                                            }
                                    ?>

                                        <tr>
                                            <td><center><button type="button" class="btn btn-info" title="click to see details ..." data-target="#details_<?php echo $data_row['gy_transdet_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("M d, Y", strtotime($data_row['gy_transdet_date'])); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $data_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $data_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $data_row['gy_trans_quantity']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $data_row['gy_trans_ref_rep_quantity']; ?></center></td>
                                            <td><center><?php echo $productrow_row['gy_product_name']." ".$productrow_row['gy_product_desc']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo number_format(0 + $data_row['gy_product_price'],2); ?></center></td>
                                            <td style="font-weight: bold; color: blue;"><center><?php echo number_format(0 + $my_discount_val,2); ?></center></td>
                                            <td style="font-weight: bold; color: green;"><center><?php echo number_format(0 + $data_row['gy_product_price'] * $data_row['gy_trans_quantity'],2); ?></center></td>
                                            <td><center><button type="button" class="btn btn-warning" title="click to refund item ..." data-target="#refund_<?php echo $data_row['gy_transdet_id']; ?>" data-toggle="modal"><i class="fa fa-hand-o-right fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to replace item ..." data-target="#replace_<?php echo $data_row['gy_transdet_id']; ?>" data-toggle="modal"><i class="fa fa-times fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="details_<?php echo $data_row['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">More Details</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info" style="border-radius: 0px;">
                                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                                            
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p>
                                                                                Date and Time: &nbsp;&nbsp;&nbsp;<u><?php echo date("F d, Y g:i:s A", strtotime($data_row['gy_transdet_date'])); ?></u><br>
                                                                                Prepared By: &nbsp;&nbsp;&nbsp;<u><?php echo $prepared_by_row['gy_full_name']; ?></u><br>
                                                                                Cashier: &nbsp;&nbsp;&nbsp;<u><?php echo $cashier_row['gy_full_name']; ?></u><br><br/>
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

                                        <!-- Refund -->

                                        <div class="modal fade" id="refund_<?php echo $data_row['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Refund Item </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="refund_item?cd=<?php echo $data_row['gy_transdet_id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>NOTE</label>
                                                                        <textarea class="form-control" name="my_note" placeholder="Type your note/reason here ..." rows="2" autofocus required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Quantity to REFUND</label>
                                                                        <input type="number" name="my_refund_quantity" min="0" step="0.01" max="<?php echo $data_row['gy_trans_ref_rep_quantity']; ?>" value="<?php echo $data_row['gy_trans_ref_rep_quantity']; ?>" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> ADMIN PIN</label>
                                                                        <input type="password" class="form-control" name="my_secure_pin" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="submit" name="submit_refund" class="btn btn-warning"><i class="fa fa-hand-o-right fa-fw"></i> Refund</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Replace -->

                                        <div class="modal fade" id="replace_<?php echo $data_row['gy_transdet_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Replace Item </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="replace_item?cd=<?php echo $data_row['gy_transdet_id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>NOTE</label>
                                                                        <textarea class="form-control" name="my_note" placeholder="Type your note/reason here ..." rows="2" autofocus required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Quantity to Replace</label>
                                                                        <input type="number" name="my_refund_quantity" min="0" step="0.01" max="<?php echo $data_row['gy_trans_ref_rep_quantity']; ?>" value="<?php echo $data_row['gy_trans_ref_rep_quantity']; ?>" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> ADMIN PIN</label>
                                                                        <input type="password" class="form-control" name="my_secure_pin" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="submit" name="submit_replace" class="btn btn-danger"><i class="fa fa-hand-o-right fa-fw"></i> Replace</button>
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

</body>

</html>
