<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Products";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Pull-Out is Successful";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "empty_input") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Quantity is not specified";
    }else if ($my_notification == "only_space") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "White spaces is not allowed.";
    }else if ($my_notification == "only_zero") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Only zero (0) is not allowed";
    }else if ($my_notification == "restock") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Restock Successful";
    }else if ($my_notification == "pullout") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Pull-Out Successful";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Incorrect PIN";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Product Deleted";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_products` Order By `gy_product_name` ASC";

    $query_two = "Select COUNT(`gy_product_id`) FROM `gy_products` Order By `gy_product_name` ASC";

    $query_three = "Select * from `gy_products` Order By `gy_product_name` ASC ";

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
                    <h3 class="page-header"><i class="fa fa-dropbox"></i> <?php echo $my_project_header_title; ?></h3>
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
                                    <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name ..." name="product_search_pull" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Product Data Table
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>For Use</center></th>
                                            <th><center>Stock Transfer/DR</center></th>
                                            <th><center>Back Order</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($product_row=$query->fetch_array()) {

                                            //get restock status
                                            if ($product_row['gy_product_quantity'] <= $product_row['gy_product_restock_limit']) {
                                                $my_limit = "danger";
                                            }else{
                                                $my_limit = "default";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_limit; ?>">
                                            <td style="font-weight: bold;"><center><?php echo $product_row['gy_product_code']; ?></center></td>
                                            <td><center><?php echo $product_row['gy_product_name']; ?></center></td>
                                            <td><center><?php echo $product_row['gy_product_quantity']." ".$product_row['gy_product_unit']; ?></center></td>
                                            <td><center><button type="button" class="btn btn-success" title="click to add for use items ..." data-target="#for_use_<?php echo $product_row['gy_product_code']; ?>" data-toggle="modal"><i class="fa fa-times fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-warning" title="click to transfer product/items ..." data-target="#stock_trans_<?php echo $product_row['gy_product_code']; ?>" data-toggle="modal"><i class="fa fa-times fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to add back order" data-target="#back_order_<?php echo $product_row['gy_product_code']; ?>" data-toggle="modal"><i class="fa fa-times fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- For Use -->

                                        <div class="modal fade" id="for_use_<?php echo $product_row['gy_product_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-times fa-fw"></i> Pull-Out Product <span style="color: green;">For Use</span> <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="pull_out?cd=<?php echo $product_row['gy_product_code']; ?>">

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Quantity Pulled-Out by <?php echo $product_row['gy_product_unit']; ?></label>
                                                                        <input type="number" name="my_pullout_quantity" min="0" max="<?php echo $product_row['gy_product_quantity']; ?>" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Note</label>
                                                                        <textarea class="form-control" name="my_note" rows="2" placeholder="type your note here ..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <button type="submit" name="for_use" class="btn btn-success" title="click to pull-out item for use ..."><i class="fa fa-times fa-fw"></i> Pull-Out</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stock Transfer/DR -->

                                        <div class="modal fade" id="stock_trans_<?php echo $product_row['gy_product_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-times fa-fw"></i> Pull-Out Product <span style="color: orange;">Stock Transfer/DR</span> <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="pull_out?cd=<?php echo $product_row['gy_product_code']; ?>">

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Quantity Pulled-Out by <?php echo $product_row['gy_product_unit']; ?></label>
                                                                        <input type="number" name="my_pullout_quantity" min="0" max="<?php echo $product_row['gy_product_quantity']; ?>" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Note</label>
                                                                        <textarea class="form-control" name="my_note" rows="2" placeholder="type your note here ..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <button type="submit" name="stock_transfer" class="btn btn-warning" title="click to pull-out item for Stock Transfer ..."><i class="fa fa-times fa-fw"></i> Pull-Out</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Back-Order -->

                                        <div class="modal fade" id="back_order_<?php echo $product_row['gy_product_code']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-times fa-fw"></i> Pull-Out Product <span style="color: red;">Back-Order</span> <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="pull_out?cd=<?php echo $product_row['gy_product_code']; ?>">

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Quantity Pulled-Out by <?php echo $product_row['gy_product_unit']; ?></label>
                                                                        <input type="number" name="my_pullout_quantity" min="0" max="<?php echo $product_row['gy_product_quantity']; ?>" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Note</label>
                                                                        <textarea class="form-control" name="my_note" rows="2" placeholder="type your note here ..."></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <button type="submit" name="back_order" class="btn btn-danger" title="click to pull-out back-order item ..."><i class="fa fa-times fa-fw"></i> Pull-Out</button>
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

</body>

</html>
