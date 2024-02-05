<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $search_text = @$_GET['search_text'];

    $my_project_header_title = "Print Masterlist Search: ".$search_text;

    $my_query = "Select * From `gy_products` Where `gy_product_name` LIKE '%$search_text%' ORDER BY `gy_product_name` ASC";

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
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></h3>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Category</label>
                                    <select class="form-control" name="my_cat" id="my_cat" required>
                                        <option disabled selected>-- Select Category --</option>
                                        <option value="all">ALL</option>
                                        <?php  
                                            //get cashiers
                                            $get_cashier_select=$link->query("Select * From `gy_category`");
                                            while($cashier_select_row=$get_cashier_select->fetch_array()) {
                                        ?>
                                        <option><?php echo $cashier_select_row['gy_cat_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </form> 
                        <form method="post" enctype="multipart/form-data" action="redirect_manager">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Products</label>
                                    <input type="text" name="search_master" class="form-control" placeholder="search products here ..." autofocus required>
                                </div>
                            </div>
                        </form>                  
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <form method="post" enctype="multipart/form-data" id="my_pop_form" action="print_master_selected">
                        <div class="col-md-12">
                            <button type="submit" name="print_selected" class="btn btn-success" title="click to print ..."><i class="fa fa-print"></i> Print Checked Items</button>
                            <br><br>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAl"> All</th>
                                        <th><center>No.</center></th>
                                        <th><center>Cat.</center></th>
                                        <th><center>Code</center></th>
                                        <th><center>Description</center></th>
                                        <th><center>Coding</center></th>
                                        <th><center>SRP</center></th>
                                        <th><center>Limit</center></th>
                                        <th><center>Quantity</center></th> 
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        //vars
                                        $numrow = 0;
                                        // Select ordered items
                                        $sql_item_detail=$link->query($my_query);

                                        while ($row_item_detail=$sql_item_detail->fetch_array()) {
                                            $numrow++;          
                                    ?>                  
                                    <tr>
                                        <td><input type="checkbox" id="checkItem" name="check[]" value="<?php echo $row_item_detail['gy_product_id']; ?>"></td>
                                        <td style="font-size: 13px;"><?php echo $numrow; ?></td>
                                        <td style="font-size: 13px;"><?php echo $row_item_detail['gy_product_cat']; ?></td>
                                        <td style="font-size: 13px;"><?php echo $row_item_detail['gy_product_code']; ?></td>
                                        <td style="text-transform: uppercase; font-size: 10px;"><?php echo $row_item_detail['gy_product_name']; ?></td>
                                        <td style="font-size: 13px; color: blue;"><?php echo toAlpha($row_item_detail['gy_product_price_cap']); ?></td>
                                        <td style="font-size: 13px; color: green;"><?php echo number_format($row_item_detail['gy_product_price_srp'],2); ?></td>
                                        <td style="font-size: 13px; color: red;"><?php echo number_format($row_item_detail['gy_product_discount_per'],2); ?></td>
                                        <td style="font-size: 13px; color: blue;"><?php echo $row_item_detail['gy_product_quantity']." <span style='color: black;'>".$row_item_detail['gy_product_unit']."</span>"; ?></td>
                                    </tr>
                                                
                                <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">
        var myForm = document.getElementById('my_pop_form');
        myForm.onsubmit = function() {
            var w = window.open('about:blank','print_master_selected','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1280,height=720,left = 312,top = 234');
            this.target = 'print_master_selected';
        };
    </script>

    <script type="text/javascript">
        $("#checkAl").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>

    <script type="text/javascript">
        $('#my_cat').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script>

</body>

</html>
