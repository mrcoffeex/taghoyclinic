<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Item Added";
    }else if ($my_notification == "not_found") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Item not found";
    }else if ($my_notification == "duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Duplicate Item";
    }else if ($my_notification == "empty") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "No Items Found";
    }else if ($my_notification == "item_remove") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Item Removed";
    }else if ($my_notification == "stocks_pullout") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Stocks Pulled-Out";
    }else if ($my_notification == "item_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Quantity Updated";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $branch_id = @$_GET['br_id'];
    $branch_name = get_branch_name($branch_id);

    if ($branch_name == "unknown") {
        $branch_value = "";
        $branch_name = "";

        header("location: 404");
    }else{
        $branch_value = $branch_id;
        $branch_name = $branch_name;
    }

    //my scripts
    $check_transaction=$link->query("Select * From `gy_pullout` Where `gy_pullout_by`='$user_id' AND `gy_pullout_status`='0' AND `gy_branch_id`='$branch_id'");
    $count_trans=$check_transaction->num_rows;

    if ($count_trans > 0) {
        $get_trans=$link->query("Select * From `gy_pullout` Where `gy_pullout_by`='$user_id' AND `gy_pullout_status`='0' AND `gy_branch_id`='$branch_id' Order By `gy_pullout_code` DESC");
        $get_trans_row=$get_trans->fetch_array();

        $my_trans_code = $get_trans_row['gy_pullout_code'];
    }else{
        $get_latest_trans=$link->query("Select * From `gy_pullout` Order By `gy_pullout_code` DESC LIMIT 1");
        $trans_row=$get_latest_trans->fetch_array();

        if ($trans_row['gy_pullout_code'] == 0) {
            $my_trans_code = "1001";
        }else{
            $my_trans_code = $trans_row['gy_pullout_code'] + 1;
        }
    }

    $my_project_header_title = "Pull-Out Counter <span style='color: blue;'>".$branch_name."</span>";

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
                    <h3 class="page-header"><i class="fa fa-times"></i> <?php echo $my_project_header_title; ?></h3>
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
                    <div class="form-group">
                        <form method="post" enctype="multipart/form-data" action="add_pullout_item?br_id=<?= $branch_id; ?>&cd=<?php echo $my_trans_code; ?>">
                            <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name ...  (alt + 1)" accesskey="1" list="myProducts" name="product_search" id="suggest" style="border-radius: 0px;" autocomplete="off" autofocus required>
                            <datalist id="myProducts"></datalist>
                        </form>
                    </div>
                </div>

                <?php  

                    //free vars
                    $total = "";

                    //items
                    $get_items=$link->query("Select * From `gy_pullout` Where `gy_pullout_code`='$my_trans_code' AND `gy_pullout_status`='0' AND `gy_pullout_by`='$user_id' AND `gy_branch_id`='$branch_id' Order By `gy_pullout_id` DESC");

                    //count items
                    $count_items=$get_items->num_rows;
                ?>
                <div class="col-md-9">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Data Table: <b><?php echo $count_items; ?> items(s)</b>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>CAP</center></th>
                                            <th><center>SRP</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Edit</center></th>
                                            <th><center>Remove</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            //get items
                                            while ($item_row=$get_items->fetch_array()) {

                                                //get the product info
                                                $my_product_code=$item_row['gy_product_id'];
                                                $get_product_info=$link->query("Select * From `gy_products` Where `gy_product_id`='$my_product_code'");
                                                $product_row=$get_product_info->fetch_array();
                                        ?>
                                            <tr class="info">
                                                <td><center><b><?php echo $product_row['gy_product_code']; ?></b></center></td>
                                                <td><center><b><?php echo $item_row['gy_product_name']; ?></b> -  <span style="color: blue; font-weight: bold;"><?php echo $product_row['gy_product_quantity']." ".$product_row['gy_product_unit']; ?></span></center></td>
                                                <td><center><b><?php echo number_format($product_row['gy_product_price_cap'], 2); ?></b></center></td>
                                                <td><center><b><?php echo number_format($product_row['gy_product_price_srp'], 2); ?></b></center></td>
                                                <td><center><b><?php echo $item_row['gy_pullout_quantity']."</b> ".$product_row['gy_product_unit']; ?></center></td>
                                                <td><center><button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_<?php echo $item_row['gy_pullout_id']; ?>" title="click to edit quantity ..."><i class="fa fa-edit fa-fw"></i></button></center></td>
                                                <td><center><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_<?php echo $item_row['gy_pullout_id']; ?>" title="click to remove ..."><i class="fa fa-times fa-fw"></i></button></center></td>
                                            </tr>

                                            <!-- modals -->

                                            <!-- Edit -->

                                            <div class="modal fade" id="edit_<?php echo $item_row['gy_pullout_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit fa-fw"></i> Edit Item </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" enctype="multipart/form-data" action="edit_pullout_quantity?br_id=<?= $branch_id; ?>&cd=<?php echo $item_row['gy_pullout_id']; ?>">

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label>Quantity by <?php echo $product_row['gy_product_unit']; ?></label>
                                                                            <input type="number" name="my_quantity" step="0.01" min="0" value="<?php echo $item_row['gy_pullout_quantity']; ?>" class="form-control" autofocus required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <button type="submit" name="submit_edit" class="btn btn-warning"><i class="fa fa-angle-right fa-fw"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete -->

                                            <div class="modal fade" id="delete_<?php echo $item_row['gy_pullout_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Remove Item</small></h4>
                                                        </div>
                                                        <form method="post" enctype="multipart/form-data" action="delete_pullout_item?br_id=<?= $branch_id; ?>&cd=<?php echo $item_row['gy_pullout_id']; ?>">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <p style="font-size: 25px; margin-left: 15px; margin-right: 15px;">Do You want to remove <span style="color: blue;"><?php echo $item_row['gy_product_name']; ?></span> on the list?</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="delete_item" class="btn btn-danger">Remove</button>
                                                            </div>
                                                        </form>
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

                <div class="col-md-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Pull-Out Details
                        </div>

                        <div class="panel-body">
                            <form method="post" enctype="multipart/form-data" action="submit_pullout_transaction" onsubmit="return validateForm(this);">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Pull-Out Code</label>
                                            <input type="text" name="my_trans_code" class="form-control" value="<?php echo $my_trans_code; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Branch</label>
                                            <select class="form-control" name="my_branch" readonly required>
                                                <option value="<?= $branch_value; ?>"><?= $branch_name; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Pull-Out Type</label>
                                            <select class="form-control" name="my_type" required>
                                                <option></option>
                                                <option value="SOLD">SOLD</option>
                                                <option value="FOR_USE">FOR USE</option>
                                                <option value="BACK_ORDER">BACK ORDER</option>
                                                <option value="DAMAGE">DAMAGED ITEM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Note</label>
                                            <textarea name="my_note" class="form-control" rows="2" placeholder="type your note here ..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Prepared By:</label>
                                            <input type="text" name="my_prepared_by" class="form-control" value="<?php echo $user_info; ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" name="submit_trans" id="submit_trans" class="btn btn-primary" style="width: 100%;"><i class="fa fa-chevron-circle-right fa-fw"></i> Submit Transaction</button><br>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
      
            formObj.submit_trans.disabled = true; 
            return true;  
      
        }  
    </script>

    <script type="text/javascript">
        var timer;
        $(document).ready(function(){
            $("#suggest").keyup(function(){
                clearTimeout(timer);
                var ms = 200; // milliseconds
                $.get("live_search?br_id=<?= $branch_id; ?>", {product_search: $(this).val()}, function(data){
                    timer = setTimeout(function() {
                        $("datalist").empty();
                        $("datalist").html(data);
                    }, ms);
                });
            });
        });
    </script>

</body>

</html>
