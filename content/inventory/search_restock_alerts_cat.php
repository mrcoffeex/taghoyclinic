<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $search_text = @$_GET['search_text'];

    if ($search_text == "mrcoffeex_only_space") {
         echo "
            <script>
                window.alert('White Spaces is not allowed!');
                window.location.href = 'restock_alerts'
            </script>
         ";
    }else if ($search_text == "mrcoffeex_only_zero") {
        echo "
            <script>
                window.alert('Only Zero is not allowed!');
                window.location.href = 'restock_alerts'
            </script>
         ";
    }else{

        $my_project_header_title = "Re-Stock Alerts Category Search: ".$search_text;

        $query_one = "Select * From `gy_products` Where `gy_product_cat`='$search_text' AND `gy_product_quantity`<=`gy_product_restock_limit` Order By `gy_product_name` ASC";

        $query_two = "Select COUNT(`gy_product_id`) FROM `gy_products` Where `gy_product_cat`='$search_text' AND `gy_product_quantity`<=`gy_product_restock_limit` Order By `gy_product_name` ASC";

        $query_three = "Select * From `gy_products` Where `gy_product_cat`='$search_text' AND `gy_product_quantity`<=`gy_product_restock_limit` Order By `gy_product_name` ASC ";

        $my_num_rows = 50;

        include 'my_pagination_search.php';

        $count_results=$link->query($query_one)->num_rows;
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
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-search fa-fw"></i> <?php echo $my_project_header_title; ?> <a href="print_restock_alerts?mode=cat_search&cd=<?php echo $search_text; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-success" title="click to print restock alerts ..."><i class="fa fa-print fa-fw"></i> Print</button></a></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Search Engine -->
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Product Code/Product Name" name="restock_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <!-- Search Engine -->
                            <div class="form-group">
                                <form method="post" id="my_form" enctype="multipart/form-data" action="redirect_manager">
                                    <select class="form-control"  name="restock_search_cat" id="restock_search_cat" style="border-radius: 0px;" required>
                                        <option disabled selected>-- Select Category --</option>
                                        <?php 
                                            //get categories
                                            $get_categories=$link->query("Select * From `gy_category`");
                                            while ($cat_row=$get_categories->fetch_array()) {
                                        ?>
                                        <option><?php echo $cat_row['gy_cat_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            Product Data Table - <b><?php echo 0+$count_results; ?></b> result(s)
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Category</center></th>
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Supplier</center></th>
                                            <th><center>Branch</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        while ($product_row=$query->fetch_array()) {

                                            //get supplier
                                            $my_scode=words($product_row['gy_supplier_code']);
                                            $get_supplier=$link->query("Select * From `gy_supplier` Where `gy_supplier_code`='$my_scode'");
                                            $supplier_row=$get_supplier->fetch_array();

                                            if ($product_row['gy_supplier_code'] == 0) {
                                                $my_supplier_data = "NO DATA";
                                                $my_supplier_color = "red";
                                            }else{
                                                $my_supplier_data = $supplier_row['gy_supplier_name'];
                                                $my_supplier_color = "green";
                                            }
                                    ?>

                                        <tr class="danger">
                                            <td style="font-weight: bold;"><center><?php echo $product_row['gy_product_cat']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $product_row['gy_product_code']; ?></center></td>
                                            <td><center><?php echo $product_row['gy_product_name']; ?></center></td>
                                            <td><center><?php echo $product_row['gy_product_quantity']." ".$product_row['gy_product_unit']; ?></center></td>
                                            <td style="font-weight: bold; color: <?php echo $my_supplier_color; ?>"><center><?php echo $my_supplier_data; ?></center></td>
                                            <td><center><?php echo get_branch_name($product_row['gy_branch_id']); ?></center></td>
                                        </tr>
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

    <script type="text/javascript">
        $('#restock_search_cat').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script>

</body>

</html>
`