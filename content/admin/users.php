<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "System Users";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "User is added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "User Info is Updated";
    }else if ($my_notification == "user_moved") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "User moved to Archieve.";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Password Mismatch";
    }else if ($my_notification == "code_duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Account Code already exist! Try another code";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "User successfully removed";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_user` Where `gy_user_type`!='0' AND `gy_user_status`='0' Order By `gy_user_id` ASC";

    $query_two = "Select COUNT(`gy_user_id`) FROM `gy_user` Where `gy_user_type`!='0' AND `gy_user_status`='0' Order By `gy_user_id` ASC";

    $query_three = "Select * from `gy_user` Where `gy_user_type`!='0' AND `gy_user_status`='0' Order By `gy_user_id` ASC ";

    $my_num_rows = 20;

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
                    <h3 class="page-header"><i class="fa fa-user"></i> <?php echo $my_project_header_title; ?></h3>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_user" title="click to add user ..."><i class="fa fa-plus fa-fw"></i> Add New User</button>
                            <a href="user_archieve" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-warning" title="click to open user archieve"><i class="fa fa-folder-open fa-fw"></i> User Archieve</button></a>
                        </div>
                        <hr>
                    </div>

                    <div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-plus fa-fw"></i> Add User</center></h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post" enctype="multipart/form-data" action="add_user">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Acct. Code</label>
                                                    <input type="text" name="my_code" minlength="6" maxlength="11" class="form-control" value="<?php echo my_rand_int(8); ?>" readonly required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="my_name" minlength="4" maxlength="16" class="form-control" autofocus autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Branch</label>
                                                    <select name="my_branch" id="my_branch" class="form-control" required>
                                                        <!-- <option value="0">All Access</option> -->
                                                        <?php  
                                                            //get branch
                                                            $getbranch=$link->query("SELECT * From `gy_branch` Order By `gy_branch_id` ASC");
                                                            while ($branches=$getbranch->fetch_array()) {
                                                        ?>
                                                        <option value="<?= $branches['gy_branch_id']; ?>"><?= $branches['gy_branch_name']; ?></option>
                                                    <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Role</label>
                                                    <select name="my_role" id="my_role" class="form-control" required>
                                                        <option></option>
                                                        <option value="0">Administrator</option>
                                                        <option value="1">Inventory</option>
                                                        <option value="2">Cashier</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input type="text" name="my_username" minlength="6" maxlength="16" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input type="password" name="my_password1" id="my_password1" onkeyup="check_password()" minlength="6" maxlength="16" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>ReType Password</label>
                                                    <input type="password" name="my_password2" id="my_password2" onkeyup="check_password()" minlength="6" maxlength="16"class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label id="warning"></label>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" name="submit_user" id="submit_user" class="btn btn-primary" title="click to add user ..." disabled="false">Add <i class="fa fa-angle-right fa-fw"></i></button>
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
                                document.getElementById('submit_user').disabled = true;
                            }else if (pass2 == "") {
                                document.getElementById('submit_user').disabled = true;
                            }else if (pass1 == "" && pass2 == "") {
                                document.getElementById('submit_user').disabled = true;
                            }else if (pass1 != pass2) {
                                document.getElementById('submit_user').disabled = true;
                                document.getElementById('warning').innerHTML = "Password Mismatch";
                            }else if (pass1 == pass2) {
                                document.getElementById('submit_user').disabled = false;
                                document.getElementById('warning').innerHTML = "";
                            }else{
                                document.getElementById('submit_user').disabled = false;
                                document.getElementById('warning').innerHTML = "";
                            }
                        }
                    </script>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Users Data Table
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Archieve</center></th>
                                            <th><center>Acct. Code</center></th>
                                            <th style="color: blue;"><center>Name</center></th>
                                            <th><center>Username</center></th>
                                            <th><center>Branch</center></th>
                                            <th><center>Edit</center></th><!-- 
                                            <th><center>Delete</center></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($user_row=$query->fetch_array()) {

                                            //roles
                                            if ($user_row['gy_user_type'] == "0") {
                                                $my_role = "Admin";
                                            }else if ($user_row['gy_user_type'] == "1") {
                                                $my_role = "Inventory";
                                            }else if ($user_row['gy_user_type'] == "2") {
                                                $my_role = "Cashier";
                                            }else{
                                                $my_role = "unknown";
                                            }

                                            $branch_name = get_branch_name($user_row['gy_branch_id']);

                                    ?>

                                        <tr>
                                            <td><center><button type="button" class="btn btn-warning" title="click to move to archieve ..." data-toggle="modal" data-target="#set_<?php echo $user_row['gy_user_id']; ?>"><i class="fa fa-folder-open fa-fw"></i></button></center></td>
                                            <td><center><b><?php echo $user_row['gy_user_code']; ?></b></center></td>
                                            <td style="color: blue;"><center><?php echo $user_row['gy_full_name']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $user_row['gy_username']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?= $branch_name; ?></center></td>
                                            <td><center><button type="button" class="btn btn-info" title="click to edit user details ..." data-toggle="modal" data-target="#edit_<?php echo $user_row['gy_user_id']; ?>"><i class="fa fa-edit fa-fw"></i></button></center></td>
                                            <!-- <td><center><button type="button" class="btn btn-danger" title="click to delete user ..." data-target="#delete_<?php #echo $user_row['gy_user_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td> -->
                                        </tr>

                                        <?php include 'modal_user.php'; ?>

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

        $(document).ready(function(){
            $('#my_role option[value="0"]').prop('disabled', false);
            $('#my_role option[value="1"]').prop('disabled', false);
            $('#my_role option[value="2"]').prop('disabled', true);
            $('#my_role option[value="3"]').prop('disabled', true);
            $('#my_role option[value="4"]').prop('disabled', false);

            $('#my_branch').change(function() {
                
                $('#my_role').val('');

                if ($('#my_branch').val() == 0) {
                    $('#my_role option[value="0"]').prop('disabled', false);
                    $('#my_role option[value="1"]').prop('disabled', false);
                    $('#my_role option[value="2"]').prop('disabled', true);
                    $('#my_role option[value="3"]').prop('disabled', true);
                    $('#my_role option[value="4"]').prop('disabled', false);
                }else{
                    $('#my_role option[value="0"]').prop('disabled', true);
                    $('#my_role option[value="1"]').prop('disabled', true);
                    $('#my_role option[value="2"]').prop('disabled', false);
                    $('#my_role option[value="3"]').prop('disabled', false);
                    $('#my_role option[value="4"]').prop('disabled', true);
                }
                
            });
        });
    </script>

</body>

</html>
