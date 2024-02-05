<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $br = @$_GET['br'];
    $search_text = @$_GET['search_text'];

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Product is Updated";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "only_space") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "White spaces is not allowed.";
    }else if ($my_notification == "only_zero") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Only zero (0) is not allowed";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
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


    if ($search_text == "mrcoffeex_only_space") {
         echo "
            <script>
                window.alert('White Spaces is not allowed!');
                window.location.href = 'products'
            </script>
         ";
    }else if ($search_text == "mrcoffeex_only_zero") {
        echo "
            <script>
                window.alert('Only Zero is not allowed!');
                window.location.href = 'products'
            </script>
         ";
    }else{

        $my_project_header_title = "Search Product Inventory: ".$search_text;

        //check the supplier alike
        $check_supplier=$link->query("Select * From `gy_supplier` Where `gy_supplier_name`='$search_text' Order By `gy_supplier_name` ASC");
        $count_check=$check_supplier->num_rows;
        $check_supp_row=$check_supplier->fetch_array();

        //my first result
        $my_supplier_code=$check_supp_row['gy_supplier_code'];

        if ($count_check > 0) {
            //not null

            if ($br == 0) {

                //count results
                $count_search_results=$link->query("Select * From `gy_products` Where `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%'");
                $search_count=$count_search_results->num_rows;

                $query_one = "Select * From `gy_products` Where `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name`+0 ASC";

                $query_two = "Select COUNT(`gy_product_id`) From `gy_products` Where `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name`+0 ASC";

                $query_three = "Select * From `gy_products` Where `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name`+0 ASC ";
            }else{

                //count results
                $count_search_results=$link->query("Select * From `gy_products` Where `gy_branch_id`='$br' AND `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%'");
                $search_count=$count_search_results->num_rows;

                $query_one = "Select * From `gy_products` Where `gy_branch_id`='$br' AND `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name`+0 ASC";

                $query_two = "Select COUNT(`gy_product_id`) From `gy_products` Where `gy_branch_id`='$br' AND `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name`+0 ASC";

                $query_three = "Select * From `gy_products` Where `gy_branch_id`='$br' AND `gy_supplier_code`='$my_supplier_code' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name`+0 ASC ";
            }

        }else{

            if ($br == 0) {

                //count results
                $count_search_results=$link->query("Select * From `gy_products` Where CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%'");
                $search_count=$count_search_results->num_rows;

                $query_one = "Select * From `gy_products` Where CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name` ASC";

                $query_two = "Select COUNT(`gy_product_id`) From `gy_products` Where CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name` ASC";

                $query_three = "Select * From `gy_products` Where CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name` ASC ";
            }else{

                //count results
                $count_search_results=$link->query("Select * From `gy_products` Where `gy_branch_id`='$br' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%'");
                $search_count=$count_search_results->num_rows;

                $query_one = "Select * From `gy_products` Where `gy_branch_id`='$br' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name` ASC";

                $query_two = "Select COUNT(`gy_product_id`) From `gy_products` Where `gy_branch_id`='$br' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name` ASC";

                $query_three = "Select * From `gy_products` Where `gy_branch_id`='$br' AND CONCAT(`gy_product_name`,`gy_product_code`,`gy_product_desc`,`gy_supplier_code`,`gy_product_cat`,`gy_update_code`) LIKE '%$search_text%' Order By `gy_product_name` ASC ";
            }
        }

        $my_num_rows = 20;

        include 'my_pagination_product.php';

    }

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
                    <h3 class="page-header"><i class="fa fa-search"></i> <?php echo $my_project_header_title; ?></h3>
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
                        <form method="post" enctype="multipart/form-data" action="redirect_manager">
                        <div class="col-md-2">
                            <!-- Search Engine -->
                            <div class="form-group">
                                <select name="branch_product" class="form-control" required>
                                    <?php  
                                        if ($br == "") {
                                            //empty here
                                        }else{
                                    ?>

                                    <option value="<?= $br; ?>"><?= get_branch_name($br); ?></option>

                                    <?php } ?>

                                    <option value="0">All</option>

                                    <?php
                                        //get branches
                                        $getbranch=$link->query("SELECT * From `gy_branch` Order By `gy_branch_id` ASC");
                                        while ($branches=$getbranch->fetch_array()) {
                                    ?>
                                    <option value="<?= $branches['gy_branch_id']; ?>"><?= $branches['gy_branch_name']; ?></option>
                                    <?php } ?>
                                </select>   
                            </div>
                        </div>
                        <div class="col-md-6">  
                            <!-- Search Engine -->
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name/Category/Supplier Name/Update Code ..." name="product_search" style="border-radius: 0px;" autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Buttons -->
                            <button type="submit" id="submit" class="btn btn-success"><i class="fa fa-search fa-fw"></i> Search</button>
                            <a href="add_product"><button type="button" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i> Add New Product</button></a>   
                        </div>
                        </form> 
                        <hr>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Product List <span style="color: red;"><?= 0 + $search_count; ?> result(s)</span>
                            <span style="float: right;"> <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Price (Capt.)</center></th>
                                            <th><center>Unit Price</center></th>
                                            <th><center>LIMIT</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Branch</center></th>
                                            <th><center>Category / Color</center></th>
                                            <th><center>Details</center></th>
                                            <th><center>Edit</center></th>
                                            <th><center>Delete</center></th>
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

                                            //disable if no convert item
                                            if ($product_row['gy_convert_item_code'] != "") {

                                                //chcek if the item cod eis real code
                                                $check_this=$product_row['gy_convert_item_code'];
                                                $check_code=$link->query("Select * From `gy_products` Where `gy_product_code`='$check_this'");
                                                $count_check_res=$check_code->num_rows;

                                                if ($count_check_res > 0) {
                                                    $null_value = "";
                                                }else{
                                                    $null_value = "disabled";
                                                }
                                            }else{
                                                $null_value = "disabled";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_limit; ?>">
                                            <td style="font-weight: bold; padding: 1px;"><center><?php echo $product_row['gy_product_code']; ?></center></td>
                                            <td style="padding: 1px;"><center><a href="previewImage?productId=<?= $product_row['gy_product_id'] ?>" onclick="window.open(this.href, 'mywin', 'left=20, top=20, width=1280, height=720, toolbar=1, resizable=0'); return false;"><?php echo $product_row['gy_product_name']; ?></a></center></td>
                                            <td style="padding: 1px;"><center><?php echo number_format($product_row['gy_product_price_cap'],2); ?></center></td>
                                            <td style="padding: 1px;"><center><?php echo number_format($product_row['gy_product_price_srp'],2); ?></center></td>
                                            <td style="padding: 1px;"><center><?php echo number_format($product_row['gy_product_discount_per'],2); ?></center></td>
                                            <td style="padding: 1px;"><center><?php echo $product_row['gy_product_quantity']." ".$product_row['gy_product_unit']; ?></center></td>
                                            <td style="font-weight: bold; padding: 1px;"><center><?php echo get_branch_name($product_row['gy_branch_id']); ?></center></td>
                                            <td style="font-weight: bold; padding: 1px;"><center><?php echo $product_row['gy_product_cat']; ?> / <?php echo $product_row['gy_product_color']; ?></center></td>
                                            <td style="padding: 1px;"><center><button type="button" class="btn btn-warning" title="click to see product details" data-target="#details_<?php echo $product_row['gy_product_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td style="padding: 1px;"><center><a href="edit_product?cd=<?php echo $product_row['gy_product_id']; ?>&pn=<?= $pagenum; ?>&br=<?= $br; ?>&search_text=<?php echo $search_text; ?>&s_type=search"><button type="button" class="btn btn-info" title="click to edit product details"><i class="fa fa-edit fa-fw"></i></button></a></center></td>
                                            <td style="padding: 1px;"><center><button type="button" class="btn btn-danger" title="click to delete product" data-target="#delete_<?php echo $product_row['gy_product_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Product Details -->
                                        
                                        <div class="modal fade" id="details_<?php echo $product_row['gy_product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><u><?php echo $product_row['gy_product_name']; ?></u> Info</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-yellow" style="border-radius: 0px;">
                                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                                            Product Info
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <p>
                                                                                        Bar Code: <br>
                                                                                        Category / Color: <br>
                                                                                        Name: <br>
                                                                                        Description: <br>
                                                                                        Unit: <br>
                                                                                        Price (Capt.): <br>
                                                                                        Unit Price: <br>
                                                                                        Quantity: <br>
                                                                                        Restock Limit: <br>
                                                                                        Date Restocked: <br>
                                                                                        Date Registered: <br>
                                                                                        Last Update: <br>
                                                                                        Image: <br>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-md-8">
                                                                                    <p class="text-bold">
                                                                                        <u><?php echo $product_row['gy_product_code']; ?></u><br>
                                                                                        <u><?php echo $product_row['gy_product_cat'] . " / " . $product_row['gy_product_color']; ?></u><br>
                                                                                        <u><?php echo $product_row['gy_product_name']; ?></u><br>
                                                                                        <u><?php echo $product_row['gy_product_desc']; ?></u><br>
                                                                                        <u><?php echo $product_row['gy_product_unit']; ?></u><br>
                                                                                        <u>Php <?php echo number_format($product_row['gy_product_price_cap'],2); ?></u><br/>
                                                                                        <u>Php <?php echo number_format($product_row['gy_product_price_srp'],2); ?></u><br/>
                                                                                        <u><?php echo $product_row['gy_product_quantity']." ".$product_row['gy_product_unit']; ?></u><br/>
                                                                                        <u><?php echo $product_row['gy_product_restock_limit']." ".$product_row['gy_product_unit']; ?></u><br/>
                                                                                        <u><?php echo date("F d, Y g:i:s A", strtotime($product_row['gy_product_date_restock'])); ?></u><br/>
                                                                                        <u><?php echo  date("F d, Y g:i:s A", strtotime($product_row['gy_product_date_reg'])); ?></u><br/>
                                                                                        <u><?php echo  date("F d, Y g:i:s A", strtotime($product_row['gy_product_update_date'])); ?></u><br/>
                                                                                        <a href="previewImage?productId=<?= $product_row['gy_product_id'] ?>" onclick="window.open(this.href, 'mywin', 'left=20, top=20, width=1280, height=720, toolbar=1, resizable=0'); return false;">click to show image</a>
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
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $product_row['gy_product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete Product</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_product?cd=<?php echo $product_row['gy_product_id']; ?>&pn=<?= $pagenum ?>&s_type=search&search_text=<?= $search_text ?>&br=<?= $br ?>">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> Delete Secure PIN</label>
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

</body>

</html>
