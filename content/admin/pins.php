<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Password Pins";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Password is added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Password Info is Updated";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Password Mismatch";
    }else if ($my_notification == "pin_outs") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Password Mismatch";
    }else if ($my_notification == "code_duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Duplicate PIN type is not allowed in every single user";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Password successfully removed";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_optimum_secure` Order By `gy_user_id` ASC";

    $query_two = "Select COUNT(`gy_sec_id`) From `gy_optimum_secure` Order By `gy_user_id` ASC";

    $query_three = "Select * From `gy_optimum_secure` Order By `gy_user_id` ASC ";

    $my_num_rows = 30;

    include 'my_pagination.php';

    $count_results=$link->query($query_one)->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-lock"></i> <?php echo $my_project_header_title; ?></h3>
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
                            <!-- Buttons -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_pin"><i class="fa fa-plus fa-fw"></i> Add New Pin</button>
                        </div>
                        <hr>
                    </div>

                    <div class="modal fade" id="add_pin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-user fa-fw"></i> Add Password Pin</center></h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post" enctype="multipart/form-data" action="add_pin">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>User</label>
                                                    <select name="my_user" class="form-control" required>
                                                        <option></option>
                                                        <?php  
                                                            //get users
                                                            $get_users=$link->query("Select * From `gy_user` Where `gy_user_type`= 0 OR `gy_user_type`= 3 Order By `gy_user_type` ASC");
                                                            while ($userpin_row=$get_users->fetch_array()) {
                                                                //type
                                                                if ($userpin_row['gy_user_type'] == "0") {
                                                                    $my_role = "Admin";
                                                                }else if ($userpin_row['gy_user_type'] == "1") {
                                                                    $my_role = "Salesman";
                                                                }else if ($userpin_row['gy_user_type'] == "2") {
                                                                    $my_role = "Cashier";
                                                                }else if ($userpin_row['gy_user_type'] == "3") {
                                                                    $my_role = "Moderator";
                                                                }else if ($userpin_row['gy_user_type'] == "4") {
                                                                    $my_role = "Bodega Staff";
                                                                }else if ($userpin_row['gy_user_type'] == "5") {
                                                                    $my_role = "Salesman Encoder";
                                                                }else{
                                                                    $my_role = "unknown";
                                                                }
                                                        ?>
                                                        <option value="<?php echo $userpin_row['gy_user_id']; ?>"><?php echo $my_role." &nbsp;&nbsp; - &nbsp;&nbsp; ".$userpin_row['gy_full_name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Permission</label>
                                                    <select name="my_pin_type" class="form-control" required>
                                                        <option></option>
                                                        <option value="delete_pin">Delete PIN </option>
                                                        <option value="delete_product">Delete Product/Item </option>
                                                        <option value="add_discount">Add Discount </option>
                                                        <option value="delete_sales">Void Sale/Transaction </option>
                                                        <option value="update_cash">Update Beginning Balance </option>
                                                        <option value="delete_trans">Void Order List </option>
                                                        <option value="remittance">Add Remittance </option>
                                                        <option value="cash_breakdown">Cash Breakdown </option>
                                                        <option value="void_remittance">Void Remittance </option>
                                                        <option value="custom_breakdown">Custom Breakdown </option>
                                                        <option value="expenses">All Expenses Permission</option>
                                                        <option value="ref_rep">Refund/Replace </option>
                                                        <option value="print">Duplicate Thermal Print </option>
                                                        <option value="restock_pullout_stock_transfer">Re-Stock/Pull-Out/Stock Transfer </option>
                                                        <option value="users">System Users </option>
                                                        <option value="delete_supplier">Delete Supplier </option>
                                                        <option value="void_tra">TRA Void </option>
                                                        <option value="void_ro">Request Order Void </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Create PIN</label>
                                                    <input type="password" name="my_password1" id="my_password1" onkeyup="check_password()" minlength="6" maxlength="16" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>ReType PIN</label>
                                                    <input type="password" name="my_password2" id="my_password2" onkeyup="check_password()" minlength="6" maxlength="16" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label id="warning"></label>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" name="submit_pin" id="submit_pin" class="btn btn-primary" title="click to add user ..." disabled="false">Add <i class="fa fa-angle-right fa-fw"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        function check_password(){
                            var pass1 = document.getElementById('my_password1').value;
                            var pass2 = document.getElementById('my_password2').value;

                            if (pass1 == "") {
                                document.getElementById('submit_pin').disabled = true;
                            }else if (pass2 == "") {
                                document.getElementById('submit_pin').disabled = true;
                            }else if (pass1 == "" && pass2 == "") {
                                document.getElementById('submit_pin').disabled = true;
                            }else if (pass1 != pass2) {
                                document.getElementById('submit_pin').disabled = true;
                                document.getElementById('warning').innerHTML = "Password Mismatch";
                            }else if (pass1 == pass2) {
                                document.getElementById('submit_pin').disabled = false;
                                document.getElementById('warning').innerHTML = "";
                            }else{
                                document.getElementById('submit_pin').disabled = false;
                                document.getElementById('warning').innerHTML = "";
                            }
                        }
                    </script>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Password Pin Data Table <b><?php echo 0+$count_results; ?></b> result(s)
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>PIN commands</center></th>
                                            <th style="color: blue;"><center>User</center></th>
                                            <th><center>Show PIN</center></th>
                                            <th><center>Edit</center></th>
                                            <th><center>Delete</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($user_row=$query->fetch_array()) {
                                            //get user info
                                            $my_pro_id = words($user_row['gy_user_id']);
                                            $find_user=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_pro_id'");
                                            $user_pro_row=$find_user->fetch_array();

                                            //permission
                                            if ($user_row['gy_sec_type'] == 'delete_pin') {
                                                $perm = "Delete PIN ";
                                                $btn_status = "disabled";
                                            }else if ($user_row['gy_sec_type'] == 'delete_product') {
                                                $perm = "Delete Product/Item ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'add_discount') {
                                                $perm = "Add Discount ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'delete_sales') {
                                                $perm = "Void Sale/Transaction ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'update_cash') {
                                                $perm = "Update Beginning Balance ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'delete_trans') {
                                                $perm = "Void Order List ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'remittance') {
                                                $perm = "Add Remittance ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'cash_breakdown') {
                                                $perm = "Cash Breakdown ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'void_remittance') {
                                                $perm = "Void Remittance ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'custom_breakdown') {
                                                $perm = "Custom Breakdown ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'expenses') {
                                                $perm = "All Expenses Permission";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'ref_rep') {
                                                $perm = "Refund/Replace ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'print') {
                                                $perm = "Duplicate Thermal Print ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'restock_pullout_stock_transfer') {
                                                $perm = "Re-Stock/Pull-Out/Stock Transfer ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'users') {
                                                $perm = "System Users ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'delete_supplier') {
                                                $perm = "Delete Supplier ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'void_tra') {
                                                $perm = "TRA Void ";
                                                $btn_status = "";
                                            }else if ($user_row['gy_sec_type'] == 'void_ro') {
                                                $perm = "Request Order Void ";
                                                $btn_status = "";
                                            }else{
                                                $perm = "Unknown";
                                                $btn_status = "";
                                            }
                                    ?>

                                        <tr class="info">
                                            <td><center><b><?php echo $perm; ?></b></center></td>
                                            <td style="color: blue;"><center><?php echo $user_pro_row['gy_full_name']; ?></center></td>
                                            <td><center><button type="button" class="btn btn-warning" title="click to show your pin ..." data-toggle="modal" data-target="#show_<?php echo $user_row['gy_sec_id']; ?>"><i class="fa fa-lock fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-info" title="click to edit pin details ..." data-toggle="modal" data-target="#edit_<?php echo $user_row['gy_sec_id']; ?>"><i class="fa fa-edit fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to delete pin ..." data-target="#delete_<?php echo $user_row['gy_sec_id']; ?>" data-toggle="modal" <?php echo $btn_status; ?>><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Edit -->

                                        <div class="modal fade" id="edit_<?php echo $user_row['gy_sec_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-edit fa-fw"></i> Edit Password PIN Details</center></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="edit_pin?cd=<?php echo $user_row['gy_sec_id']; ?>">

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>User</label>
                                                                        <select name="my_user" class="form-control" onchange="check_password_<?php echo $user_row['gy_sec_id']; ?>()" required>
                                                                            <?php  
                                                                                //get users
                                                                                $my_prime_id = words($user_row['gy_user_id']);
                                                                                $gets_users=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_prime_id' Order By `gy_user_type` ASC");
                                                                                $userpins_row=$gets_users->fetch_array();
                                                                                    //type
                                                                                    if ($userpins_row['gy_user_type'] == "0") {
                                                                                        $my_roles = "Admin";
                                                                                    }else if ($userpins_row['gy_user_type'] == "1") {
                                                                                        $my_roles = "Salesman";
                                                                                    }else if ($userpins_row['gy_user_type'] == "2") {
                                                                                        $my_roles = "Cashier";
                                                                                    }else if ($userpins_row['gy_user_type'] == "3") {
                                                                                        $my_roles = "Moderator";
                                                                                    }else if ($userpins_row['gy_user_type'] == "4") {
                                                                                        $my_roles = "Bodega Staff";
                                                                                    }else if ($userpins_row['gy_user_type'] == "5") {
                                                                                        $my_roles = "Salesman Encoder";
                                                                                    }else{
                                                                                        $my_roles = "unknown";
                                                                                    }
                                                                                    
                                                                            ?>
                                                                            <option value="<?php echo $userpins_row['gy_user_id']; ?>"><?php echo $my_roles." &nbsp;&nbsp; - &nbsp;&nbsp; ".$userpins_row['gy_full_name']; ?></option>
                                                                            <?php  
                                                                                //get users
                                                                                $get_users=$link->query("Select * From `gy_user` Where `gy_user_type`= 0 OR `gy_user_type`= 3 Order By `gy_user_type` ASC");
                                                                                while ($userpin_row=$get_users->fetch_array()) {
                                                                                    //type
                                                                                    if ($userpin_row['gy_user_type'] == "0") {
                                                                                        $my_role = "Admin";
                                                                                    }else if ($userpin_row['gy_user_type'] == "1") {
                                                                                        $my_role = "Salesman";
                                                                                    }else if ($userpin_row['gy_user_type'] == "2") {
                                                                                        $my_role = "Cashier";
                                                                                    }else if ($userpin_row['gy_user_type'] == "3") {
                                                                                        $my_role = "Moderator";
                                                                                    }else if ($userpin_row['gy_user_type'] == "4") {
                                                                                        $my_role = "Bodega Staff";
                                                                                    }else if ($userpin_row['gy_user_type'] == "5") {
                                                                                        $my_role = "Salesman Encoder";
                                                                                    }else{
                                                                                        $my_role = "unknown";
                                                                                    }
                                                                            ?>
                                                                            <option value="<?php echo $userpin_row['gy_user_id']; ?>"><?php echo $my_role." &nbsp;&nbsp; - &nbsp;&nbsp; ".$userpin_row['gy_full_name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Permission</label>
                                                                        <select name="my_pin_type" class="form-control" onchange="check_password_<?php echo $user_row['gy_sec_id']; ?>()" required>

                                                                            <?php  
                                                                                //permission
                                                                                if ($user_row['gy_sec_type'] == 'delete_pin') {
                                                                                    $perms = "Delete PIN ";
                                                                                }else if ($user_row['gy_sec_type'] == 'delete_product') {
                                                                                    $perms = "Delete Product/Item ";
                                                                                }else if ($user_row['gy_sec_type'] == 'add_discount') {
                                                                                    $perms = "Add Discount ";
                                                                                }else if ($user_row['gy_sec_type'] == 'delete_sales') {
                                                                                    $perms = "Void Sale/Transaction ";
                                                                                }else if ($user_row['gy_sec_type'] == 'update_cash') {
                                                                                    $perms = "Update Beginning Balance ";
                                                                                }else if ($user_row['gy_sec_type'] == 'delete_trans') {
                                                                                    $perms = "Void Order List ";
                                                                                }else if ($user_row['gy_sec_type'] == 'remittance') {
                                                                                    $perms = "Add Remittance ";
                                                                                }else if ($user_row['gy_sec_type'] == 'cash_breakdown') {
                                                                                    $perms = "Cash Breakdown ";
                                                                                }else if ($user_row['gy_sec_type'] == 'void_remittance') {
                                                                                    $perms = "Void Remittance ";
                                                                                }else if ($user_row['gy_sec_type'] == 'custom_breakdown') {
                                                                                    $perms = "Custom Breakdown ";
                                                                                }else if ($user_row['gy_sec_type'] == 'expenses') {
                                                                                    $perms = "All Expenses Permission";
                                                                                }else if ($user_row['gy_sec_type'] == 'ref_rep') {
                                                                                    $perms = "Refund/Replace ";
                                                                                }else if ($user_row['gy_sec_type'] == 'print') {
                                                                                    $perms = "Duplicate Thermal Print ";
                                                                                }else if ($user_row['gy_sec_type'] == 'restock_pullout_stock_transfer') {
                                                                                    $perms = "Re-Stock/Pull-Out/Stock Transfer ";
                                                                                }else if ($user_row['gy_sec_type'] == 'users') {
                                                                                    $perms = "System Users ";
                                                                                }else if ($user_row['gy_sec_type'] == 'delete_supplier') {
                                                                                    $perms = "Delete Supplier ";
                                                                                }else if ($user_row['gy_sec_type'] == 'void_tra') {
                                                                                    $perms = "TRA Void ";
                                                                                }else if ($user_row['gy_sec_type'] == 'void_ro') {
                                                                                    $perms = "Request Order Void ";
                                                                                }else{
                                                                                    $perms = "Unknown";
                                                                                }
                                                                            ?>

                                                                            <option value="<?php echo $user_row['gy_sec_type']; ?>"><?php echo $perms; ?></option>
                                                                            <option value="delete_pin">Delete PIN </option>
                                                                            <option value="delete_product">Delete Product/Item </option>
                                                                            <option value="add_discount">Add Discount </option>
                                                                            <option value="delete_sales">Void Sale/Transaction </option>
                                                                            <option value="update_cash">Update Beginning Balance </option>
                                                                            <option value="delete_trans">Void Order List </option>
                                                                            <option value="remittance">Add Remittance </option>
                                                                            <option value="cash_breakdown">Cash Breakdown </option>
                                                                            <option value="void_remittance">Void Remittance </option>
                                                                            <option value="custom_breakdown">Custom Breakdown </option>
                                                                            <option value="expenses">All Expenses Permission</option>
                                                                            <option value="ref_rep">Refund/Replace </option>
                                                                            <option value="print">Duplicate Thermal Print </option>
                                                                            <option value="restock_pullout_stock_transfer">Re-Stock/Pull-Out/Stock Transfer </option>
                                                                            <option value="users">System Users </option>
                                                                            <option value="delete_supplier">Delete Supplier </option>
                                                                            <option value="void_tra">TRA Void </option>
                                                                            <option value="void_ro">Request Order Void </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Create PIN</label>
                                                                        <input type="password" name="my_password1" id="my_password1_<?php echo $user_row['gy_sec_id']; ?>" onkeyup="check_password_<?php echo $user_row['gy_sec_id']; ?>()" minlength="6" maxlength="16" class="form-control" value="<?php echo decryptIt($user_row['gy_sec_value']); ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>ReType PIN</label>
                                                                        <input type="password" name="my_password2" id="my_password2_<?php echo $user_row['gy_sec_id']; ?>" onkeyup="check_password_<?php echo $user_row['gy_sec_id']; ?>()" minlength="6" maxlength="16" class="form-control" value="<?php echo decryptIt($user_row['gy_sec_value']); ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label id="warning_<?php echo $user_row['gy_sec_id']; ?>"></label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="submit" name="submit_pin_edit" id="submit_pin_edit_<?php echo $user_row['gy_sec_id']; ?>" class="btn btn-primary" title="click to add user ..." disabled="false">Update <i class="fa fa-angle-right fa-fw"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script type="text/javascript">
                                            function check_password_<?php echo $user_row['gy_sec_id']; ?>(){
                                                var pass1 = document.getElementById('my_password1_<?php echo $user_row['gy_sec_id']; ?>').value;
                                                var pass2 = document.getElementById('my_password2_<?php echo $user_row['gy_sec_id']; ?>').value;

                                                if (pass1 == "") {
                                                    document.getElementById('submit_pin_edit_<?php echo $user_row['gy_sec_id']; ?>').disabled = true;
                                                }else if (pass2 == "") {
                                                    document.getElementById('submit_pin_edit_<?php echo $user_row['gy_sec_id']; ?>').disabled = true;
                                                }else if (pass1 == "" && pass2 == "") {
                                                    document.getElementById('submit_pin_edit_<?php echo $user_row['gy_sec_id']; ?>').disabled = true;
                                                }else if (pass1 != pass2) {
                                                    document.getElementById('submit_pin_edit_<?php echo $user_row['gy_sec_id']; ?>').disabled = true;
                                                    document.getElementById('warning_<?php echo $user_row['gy_sec_id']; ?>').innerHTML = "Password Mismatch";
                                                }else if (pass1 == pass2) {
                                                    document.getElementById('submit_pin_edit_<?php echo $user_row['gy_sec_id']; ?>').disabled = false;
                                                    document.getElementById('warning_<?php echo $user_row['gy_sec_id']; ?>').innerHTML = "";
                                                }else{
                                                    document.getElementById('submit_pin_edit_<?php echo $user_row['gy_sec_id']; ?>').disabled = false;
                                                    document.getElementById('warning_<?php echo $user_row['gy_sec_id']; ?>').innerHTML = "";
                                                }
                                            }
                                        </script>

                                        <!-- Show PIN -->

                                        <div class="modal fade" id="show_<?php echo $user_row['gy_sec_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock fa-fw"></i> Show PIN </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <center><h1 style="color: #fff;"><?php echo decryptIt($user_row['gy_sec_value']); ?></h1></center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $user_row['gy_sec_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete PIN </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_pin?cd=<?php echo $user_row['gy_sec_id']; ?>">
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
