<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Overheads Today";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Expenses is added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Expenses Info is Updated";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Password Mismatch";
    }else if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty Input Value";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Expenses Info successfully removed";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $date_now = words(date("Y-m-d"));

    $my_query=$link->query("Select * From `gy_expenses` Where date(`gy_exp_date`)='$date_now' AND `gy_exp_type`!='CASH' Order By `gy_exp_date` DESC");

    $count_results=$my_query->num_rows;
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
                    <h3 class="page-header"><a href="exp_general"><button type="button" class="btn btn-success" title="click to go back to general expenses ..."><i class="fa fa-arrow-left fa-fw"></i></button></a> <i class="fa fa-dropbox"></i> <?php echo $my_project_header_title; ?></h3>
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
                        <form method="post" id="my_form" enctype="multipart/form-data" action="redirect_manager">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="exp_date_f" id="exp_date1" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" name="exp_date_t" id="exp_date2" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success" name="searchstock_exp"><i class="fa fa-search fa-fw"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="modal fade" id="add_exp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-user fa-fw"></i> Add Expenses</center></h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post" enctype="multipart/form-data" action="add_exp">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    <input type="date" name="my_date" value="<?php echo date('Y-m-d'); ?>" class="form-control" autofocus required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Note</label>
                                                    <textarea name="my_note" rows="2" class="form-control" placeholder="text here ..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" name="my_amount" min="0" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" name="submit_exp" id="submit_exp" class="btn btn-primary" title="click to add user ...">Add <i class="fa fa-angle-right fa-fw"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Expenses Data Table <span style="color: green;">FOR USE</span> / <span style="color: #009fb1;">DAMAGED ITEM</span> <b><?php echo $count_results; ?> results(s)</b>
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> <a href="print_exp?datef=<?php echo date('Y-m-d'); ?>&datet=<?php echo date('Y-m-d'); ?>&condition=notcash" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-success" title="click to print result ..."><i class="fa fa-print"></i> Print</button></a> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Date</center></th>
                                            <th><center>Type</center></th>
                                            <th><center>Note</center></th>
                                            <th><center>Amount</center></th>
                                            <th><center>User</center></th>
                                            <th><center>Branch</center></th>
                                            <th><center>Edit</center></th>
                                            <th><center>Delete</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        $total_amount="";
                                        while ($exp_row=$my_query->fetch_array()) {

                                            $my_user_info=words($exp_row['gy_user_id']);

                                            //get user info
                                            $get_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user_info'");
                                            $user_info_row=$get_user_info->fetch_array();

                                            @$total_amount += $exp_row['gy_exp_amount'];

                                            if ($exp_row['gy_exp_type'] == "FOR_USE") {
                                                $my_type_pull = "FOR USE";
                                                $my_color = "success";
                                            }else if ($exp_row['gy_exp_type'] == "DAMAGE") {
                                                $my_type_pull = "DAMAGED ITEM";
                                                $my_color = "info";
                                            }else{
                                                $my_type_pull = "OTHERS";
                                                $my_color = "default";
                                            }

                                            //get approved
                                            $myappdataexp=words($exp_row['gy_approved_by']);
                                            $getappdatahere=$link->query("Select * From `gy_user` Where `gy_user_id`='$myappdataexp'");
                                            $appdatahererow=$getappdatahere->fetch_array();
                                    ?>

                                        <tr class="<?php echo $my_color; ?>">
                                            <td><center><?php echo date("F d, Y g:i:s A", strtotime($exp_row['gy_exp_date'])); ?></center></td>
                                            <td><center><?php echo $my_type_pull; ?></center></td>
                                            <td><?php echo $exp_row['gy_exp_note']." <b>Approved By: ".$appdatahererow['gy_full_name']."</b>"; ?></td>
                                            <td><center><?php echo number_format($exp_row['gy_exp_amount'],2); ?></center></td>
                                            <td><center><?php echo $user_info_row['gy_full_name']; ?></center></td>
                                            <td><center><?php echo get_branch_name($exp_row['gy_branch_id']); ?></center></td>
                                            <td><center><button type="button" class="btn btn-info" title="click to edit user details ..." data-toggle="modal" data-target="#edit_<?php echo $exp_row['gy_exp_id']; ?>"><i class="fa fa-edit fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to delete user ..." data-target="#delete_<?php echo $exp_row['gy_exp_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Edit -->

                                        <div class="modal fade" id="edit_<?php echo $exp_row['gy_exp_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-edit fa-fw"></i> Edit Details</center></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="edit_exp?cd=<?php echo $exp_row['gy_exp_id']; ?>&sd=expenses" >

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Date</label>
                                                                        <input type="date" name="my_date" class="form-control" value="<?php echo date('Y-m-d', strtotime($exp_row['gy_exp_date'])); ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Note</label>
                                                                        <textarea name="my_note" rows="2" class="form-control" required><?php echo $exp_row['gy_exp_note']; ?>
                                                                        </textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Amount</label>
                                                                        <input type="number" name="my_amount" min="0" step="0.01" class="form-control" value="<?php echo $exp_row['gy_exp_amount']; ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="submit" name="update_exp" id="update_exp" class="btn btn-info" title="click to update user ...">Update <i class="fa fa-angle-right fa-fw"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $exp_row['gy_exp_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete Expenses </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_exp?cd=<?php echo $exp_row['gy_exp_id']; ?>&sd=expenses">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Delete Secure PIN</label>
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
                                    <tr>
                                        <td colspan="3"><b><center>Total Amount</center></b></td>
                                        <td><center><b><?php echo @number_format($total_amount,2); ?></center></b></td>
                                        <td colspan="3"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- <script type="text/javascript">
        $('#exp_date').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
