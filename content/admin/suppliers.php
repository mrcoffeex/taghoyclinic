<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Supplier List";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Supplier is added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Supplier Info is Updated";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Supplier successfully removed";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_supplier` Order By `gy_supplier_id` ASC";

    $query_two = "Select COUNT(`gy_supplier_id`) FROM `gy_supplier` Order By `gy_supplier_id` ASC";

    $query_three = "Select * from `gy_supplier` Order By `gy_supplier_id` ASC ";

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
                    <h3 class="page-header"><i class="fa fa-briefcase"></i> <?php echo $my_project_header_title; ?></h3>
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
                                    <input type="text" class="form-control" placeholder="Search for Supplier Name" name="supplier_search" style="border-radius: 0px;" autofocus required>
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
