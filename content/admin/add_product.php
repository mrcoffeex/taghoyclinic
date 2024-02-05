
<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Add New Product";

    //for notification

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "New product is added.";
    }else if ($my_notification == "duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Duplicate Product Code is not allowed";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here.";
    }else if ($my_notification == "invalid_upload") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "file size or file format is not valid.";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
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
                    <h3 class="page-header"><i class="fa fa-plus"></i> <?php echo $my_project_header_title; ?></h3>
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
                <form method="post" enctype="multipart/form-data" action="add_this_product" onsubmit="return validateForm(this);">

                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Product Information</div>
                            <div class="panel-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bar Code <small>*only alphanumeric and dash (-)</small></label>
                                        <input type="text" class="form-control" maxlength="100" id="alphanumericField" name="my_code" autofocus required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="my_category" class="form-control" required>
                                            <option></option>
                                            <?php  
                                                //categories
                                                $get_categories=$link->query("Select * From `gy_category` Order By `gy_cat_id` ASC");
                                                while ($category_row=$get_categories->fetch_array()) {
                                            ?>
                                            <option><?php echo $category_row['gy_cat_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <select name="my_color" class="form-control" required>
                                            <option></option>
                                            <option>Red</option>
                                            <option>Pink</option>
                                            <option>Orange</option>
                                            <option>Yellow</option>
                                            <option>Green</option>
                                            <option>Blue</option>
                                            <option>Brown</option>
                                            <option>Violet/Purple</option>
                                            <option>Black</option>
                                            <option>White</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select class="form-control" name="my_branch" required>
                                            <?php  
                                                //get branches
                                                $get_branch=$link->query("Select * From `gy_branch`");
                                                while ($branch_row=$get_branch->fetch_array()) {
                                            ?>
                                            <option value="<?php echo $branch_row['gy_branch_id']; ?>"><?php echo $branch_row['gy_branch_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Image <small>*jpg / png only</small></label>
                                        <input type="file" class="form-control" name="my_image" accept="png, jpg" >
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Product Description</label>
                                        <input type="text" class="form-control" maxlength="100" name="my_name" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Product Details</label>
                                        <input type="text" class="form-control" maxlength="255" name="my_desc" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">Product Supplier / Pricing</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price (Capital)</label>
                                            <input type="number" class="form-control" step="0.01" min="0" name="my_price_cap" id="my_price_cap" onkeyup="get_the_price()" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unit Price</label>
                                            <input type="number" class="form-control" step="0.01" min="0" name="my_price_srp" id="my_price_srp" onkeyup="get_the_price()" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control" step="0.01" min="0" name="my_quantity" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Unit</label>
                                            <select class="form-control" name="my_unit" required>
                                                <option></option>
                                                <?php 
                                                    //my suppliers
                                                    $get_unit=$link->query("Select * From `gy_unit` Order By `gy_unit_id` ASC");
                                                    while ($unit_row=$get_unit->fetch_array()){
                                                ?>
                                                <option><?php echo $unit_row['gy_unit_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Discount Perct. (%)</label>
                                            <select class="form-control" name="my_discount_limit" id="my_discount_limit" onchange="get_the_price()">
                                                <option value="0">0%</option>
                                                <option value="5">5%</option>
                                                <option value="10">10%</option>
                                                <option value="15">15%</option>
                                                <option value="20">20%</option>
                                                <option value="25">25%</option>
                                                <option value="30">30%</option>
                                                <option value="35">35%</option>
                                                <option value="40">40%</option>
                                                <option value="45">45%</option>
                                                <option value="50">50%</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Discount Limit (Price)</label>
                                            <input type="number" class="form-control" step="0.01" min="0" name="my_limit" id="my_limit" onkeyup="get_the_discount()" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Discounted value</label>
                                            <input type="number" class="form-control" step="0.01" min="0" name="discounted_value" id="discounted_value" readonly required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Restock Limit /Unit</label>
                                            <input type="number" class="form-control" min="0" name="my_restock_limit" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <!-- Submit Button Here -->
                    <div class="col-md-12 text-center">
                        <button type="submit" name="auth_add_product" id="auth_add_product" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Save</button>
                        <button type="reset" class="btn btn-warning"><i class="fa fa-edit fa-fw"></i> New / Reset</button>
                        <a href="products"><button type="button" class="btn btn-danger"><i class="fa fa-times fa-fw"></i> Exit</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
      
            formObj.auth_add_product.disabled = true; 
            return true;  
      
        }  
    </script>

    <script type="text/javascript">

        // $(document).ready(function(){
        //     $("#my_price_cap").keyup(function(){
        //         //set price capital max to srp
        //         var mycap1 = $("#my_price_cap").val();
        //         var mysrp1 = $("#my_price_srp").val();

        //         if (mycap1 == "") {
        //             mycap1 = 0;
        //         }

        //         if (mysrp1 == "") {
        //             mysrp1 = 0;
        //         }

        //         var srptage = parseFloat(mysrp1) * parseFloat(5/100);
        //         var captage = parseFloat(mycap1) + srptage;

        //         if (mysrp1 == "") {
        //             document.getElementById('my_price_cap').max = "";
        //         }else{
        //             if (mycap1 == "") {
        //                 document.getElementById('my_price_cap').min = "0";
        //                 document.getElementById('my_price_cap').max = "";
        //             }else{
        //                 document.getElementById('my_price_srp').min = Math.floor(captage);
        //                 document.getElementById('my_price_cap').max = Math.floor(mysrp1 - 1);
        //             }
        //         }
        //     });
        // });

        // $(document).ready(function(){
        //     $("#my_price_srp").keyup(function(){
        //         //set price capital max to srp
        //         var mycap2 = $("#my_price_cap").val();
        //         var mysrp2 = $("#my_price_srp").val();

        //         if (mycap2 == "") {
        //             mycap2 = 0;
        //         }

        //         if (mysrp2 == "") {
        //             mysrp2 = 0;
        //         }

        //         var captage1 = parseFloat(mycap2) * parseFloat(5/100);

        //         var captage2 = parseFloat(mycap2) + captage1;

        //         if (mysrp2 == "") {
        //             document.getElementById('my_price_srp').min = "";
        //         }else{
        //             document.getElementById('my_price_srp').min = Math.floor(captage2);
        //             document.getElementById('my_price_cap').max = Math.floor(mysrp2 - 1);
        //         }
        //     });
        // });

        function get_the_price(){
            var b = document.getElementById('my_price_srp').value;

            var e = document.getElementById("my_discount_limit");
            var per_value = e.options[e.selectedIndex].value;

            var c = parseFloat(per_value) / 100; 

            var y = parseFloat(b) * parseFloat(c);

            var x = parseFloat(b) - parseFloat(y);

            if (!isNaN(x)) {
                document.getElementById('my_limit').value = x;
            }

            if (!isNaN(y)) {
                document.getElementById('discounted_value').value = y;
            }

        }

        function get_the_discount(){
            var aa = document.getElementById('my_price_srp').value;
            var ac = document.getElementById('my_price_cap').value;
            var ab = document.getElementById('my_limit').value;

            if (ab == "") {
                ab = 0;
            }

            var dis1 = parseFloat(ac) * parseFloat(5/100);

            var dis2 = parseFloat(ac) + dis1;

            var xx = parseFloat(aa) - parseFloat(ab);

            if (!isNaN(xx)) {
                document.getElementById('my_limit').min = Math.floor(dis2);
                document.getElementById('my_limit').max = Math.floor(aa);
                document.getElementById('discounted_value').value = xx;
            }
        }
    </script>

    <script type="text/javascript">
        var timer;
        $(document).ready(function(){
            $("#my_convert_item").keyup(function(){
                clearTimeout(timer);
                var ms = 200; // milliseconds
                $.get("live_search", {product_search: $(this).val()}, function(data){
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
