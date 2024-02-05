<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Product Image";

    $my_notification = @$_GET['note'];

    if ($my_notification == "updated") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Changes saved";
    }else if ($my_notification == "invalid") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Invalid input";
    }else if ($my_notification == "empty") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "no image found";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_products` Order By `gy_product_name` ASC";

    $query_two = "Select COUNT(`gy_product_id`) FROM `gy_products` Order By `gy_product_name` ASC";

    $query_three = "Select * from `gy_products` Order By `gy_product_name` ASC ";

    $my_num_rows = 18;

    include 'my_pagination.php';

    $count_products=$link->query($query_one)->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-image"></i> <?php echo $my_project_header_title; ?></h3>
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
                                <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name/Category/Supplier Name/Update Code ..." name="image_search" style="border-radius: 0px;" autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Buttons -->
                            <button type="submit" name="searchAlbum" class="btn btn-success"><i class="fa fa-search fa-fw"></i> Search</button>  
                        </div>
                        </form> 
                        <hr>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Product List <span style="color: red;"><?= 0 + $count_products; ?> result(s)</span>
                            <span style="float: right;"><span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                        <?php  
                            while ($product_row=$query->fetch_array()) {

                                if ($product_row['gy_product_quantity'] <= $product_row['gy_product_restock_limit']) {
                                    $my_limit = "danger";
                                }else{
                                    $my_limit = "default";
                                }
                        ?>

                            <div class="col-lg-2 col-md-3 col-xs-12">
                                <div class="panel panel-<?= $my_limit ?>">
                                    <div class="panel-body">
                                        <div class="row text-<?= $my_limit ?>">
                                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                                <img src="<?= displayImage($product_row['gy_product_image'], '../../img/no_image.jpg', '../../mrcoffeexpicturebox/') ?>" class="img-responsive" style="max-height: 250px; min-height: 250px; width: 100%;" alt="">
                                            </div>
                                            <div class="col-md-12 text-bold" style="margin-top: 5px;">
                                                <?= $product_row['gy_product_code'] ?>
                                            </div>
                                            <div class="col-md-12">
                                                <?= stringLimit($product_row['gy_product_name'], 21) ?>
                                            </div>
                                            <div class="col-md-6" style="padding-left: 1px; padding-right: 1px; margin-top: 5px;">
                                                <button type="button" class="btn btn-warning btn-block" title="click to see product details" data-target="#details_<?php echo $product_row['gy_product_id']; ?>" data-toggle="modal" style="border-radius: 0px;"><i class="fa fa-list fa-fw"></i></button>
                                            </div>
                                            <div class="col-md-6" style="padding-left: 1px; padding-right: 1px; margin-top: 5px;">
                                                <button type="button" class="btn btn-success btn-block" title="click to edit product details" data-toggle="modal" data-target="#upload_<?php echo $product_row['gy_product_id']; ?>" style="border-radius: 0px;"><i class="fa fa-upload fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

                            <div class="modal fade" id="upload_<?php echo $product_row['gy_product_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-upload fa-fw"></i> Upload Image</h4>
                                        </div>
                                        <form method="post" enctype="multipart/form-data" action="updateProductImage?productId=<?= $product_row['gy_product_id'] ?>&pn=<?= $pagenum ?>&s_type=normal&search_text=" onsubmit="btnLoader(this.uploadImage)">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for=""><?= $product_row['gy_product_name'] ?> Image</label>
                                                            <input type="file" class="form-control" name="productImage" id="productImage" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="uploadImage" id="uploadImage" class="btn btn-success btn-block">Upload</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
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
