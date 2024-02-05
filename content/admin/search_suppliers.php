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
                window.location.href = 'suppliers'
            </script>
         ";
    }else if ($search_text == "mrcoffeex_only_zero") {
        echo "
            <script>
                window.alert('Only Zero is not allowed!');
                window.location.href = 'suppliers'
            </script>
         ";
    }else{

        $my_project_header_title = "Search Supplier List: ".$search_text;

        $query_one = "Select * From `gy_supplier` Where CONCAT(`gy_supplier_name`,`gy_supplier_desc`,`gy_supplier_address`,`gy_supplier_contact`) LIKE '%$search_text%' Order By `gy_supplier_id` ASC";

        $query_two = "Select COUNT(`gy_supplier_id`) FROM `gy_supplier` Where CONCAT(`gy_supplier_name`,`gy_supplier_desc`,`gy_supplier_address`,`gy_supplier_contact`) LIKE '%$search_text%' Order By `gy_supplier_id` ASC";

        $query_three = "Select * from `gy_supplier` Where CONCAT(`gy_supplier_name`,`gy_supplier_desc`,`gy_supplier_address`,`gy_supplier_contact`) LIKE '%$search_text%' Order By `gy_supplier_id` ASC ";

        $my_num_rows = 50;

        include 'my_pagination_search.php';
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
                                    <input type="text" class="form-control" placeholder="Search for Product Bar Code/Product Name/Supplier Code/Supplier Name ..." name="product_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Buttons -->
                            <a href="add_supplier"><button type="button" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i> Add New Supplier</button></a>
                        </div>
                        <hr>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Supplier Data Table
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Code</center></th>
                                            <th><center>Supplier</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Address</center></th>
                                            <th><center>Contact #</center></th>
                                            <th><center>Edit</center></th>
                                            <th><center>Delete</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($supp_row=$query->fetch_array()) {
                                    ?>

                                        <tr class="<?php echo $my_limit; ?>">
                                            <td><center><?php echo $supp_row['gy_supplier_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $supp_row['gy_supplier_name']; ?></center></td>
                                            <td><center><?php echo $supp_row['gy_supplier_desc']; ?></center></td>
                                            <td><center><?php echo $supp_row['gy_supplier_address']; ?></center></td>
                                            <td><center><?php echo $supp_row['gy_supplier_contact']; ?></center></td>
                                            <td><center><a href="edit_supplier?cd=<?php echo $supp_row['gy_supplier_id']; ?>"><button type="button" class="btn btn-info" title="click to edit supplier details"><i class="fa fa-edit fa-fw"></i></button></a></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to delete supplier" data-target="#delete_<?php echo $supp_row['gy_supplier_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $supp_row['gy_supplier_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete Supplier </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_supplier?cd=<?php echo $supp_row['gy_supplier_id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
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
