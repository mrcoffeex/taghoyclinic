<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Refund/Replace Report";

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
    }else if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Empty Date Input";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_refund` Order By `gy_refund_id` ASC";

    $query_two = "Select COUNT(`gy_refund_id`) From `gy_refund` Order By `gy_refund_id` ASC";

    $query_three = "Select * From `gy_refund` Order By `gy_refund_id` ASC ";

    $my_num_rows = 20;

    include 'my_pagination.php';

    $count_results=$link->query($query_one)->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-file-text"></i> <?php echo $my_project_header_title; ?></h3>
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
                        <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="re_date_search_f" id="re_date_search1" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="re_date_search_t" id="re_date_search2" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" name="refund_btn" class="btn btn-success" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Refund Report Data Table - <span style="color: green;">REFUND</span> / <span style="color: #9c9c00;">REPLACE</span> - <b><?php echo 0+$count_results; ?></b> result(s)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Date</center></th>
                                            <th><center>Trans. ID</center></th>
                                            <th><center>Customer Name</center></th>
                                            <th><center>Type</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Price</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Total</center></th>
                                            <th><center>Note/App. By.</center></th>
                                            <th><center>Date Purchased</center></th>
                                            <th><center>User</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($ref_row=$query->fetch_array()) {

                                            if ($ref_row['gy_refund_type'] == 'REFUND') {
                                                $my_row_color = "success";
                                            }else{
                                                $my_row_color = "warning";
                                            }

                                            $my_total = $ref_row['gy_product_price'] * $ref_row['gy_product_quantity'];

                                            //get user info
                                            $cashier_identifier=$ref_row['gy_user_id'];
                                            $get_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$cashier_identifier'");
                                            $user_info_row=$get_user_info->fetch_array();

                                            //get product/item details
                                            $p_identifier=$ref_row['gy_product_code'];
                                            $get_p_info=$link->query("Select * From `gy_products` Where `gy_product_code`='$p_identifier'");
                                            $p_row=$get_p_info->fetch_array();
                                    ?>

                                        <tr class="<?php echo $my_row_color; ?>">
                                            <td style="font-weight: bold;"><center><?php echo date("M d, Y g:i:s A", strtotime($ref_row['gy_refund_date'])); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $ref_row['gy_trans_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $ref_row['gy_trans_custname']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $ref_row['gy_refund_type']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $ref_row['gy_product_name']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo number_format($ref_row['gy_product_price'],2); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo number_format($ref_row['gy_product_quantity'],2)." ".$p_row['gy_product_unit']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo number_format($my_total,2); ?></center></td>
                                            <td><center><button type="button" class="btn btn-<?php echo $my_row_color; ?>" title="click to see view the note ..." data-target="#details_<?php echo $ref_row['gy_refund_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i:s A",strtotime($ref_row['gy_trans_date'])); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $user_info_row['gy_full_name']; ?></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="details_<?php echo $ref_row['gy_refund_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">NOTE</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info" style="border-radius: 0px;">
                                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                                            Customer Name: <b><?php echo $ref_row['gy_trans_custname']; ?></b>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p style="text-align: justify;">
                                                                                <?php echo $ref_row['gy_refund_note']; ?>
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
        $('#re_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
