<div class="modal fade" id="edit_<?php echo $user_row['gy_user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-edit fa-fw"></i> Edit User</center></h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="edit_user?cd=<?php echo $user_row['gy_user_id']; ?>">

                    <?php  
                        if ($user_row['gy_user_code'] == 0) {
                            $my_user_code = my_rand_int(8);
                        }else{
                            $my_user_code = $user_row['gy_user_code'];
                        }
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Acct. Code</label>
                                <input type="text" name="my_code" minlength="6" maxlength="11" class="form-control" value="<?php echo $my_user_code; ?>" readonly autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="my_name" minlength="4" maxlength="16" class="form-control" value="<?php echo $user_row['gy_full_name']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="my_username" minlength="6" maxlength="16" class="form-control" value="<?php echo $user_row['gy_username']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="my_password1" id="my_password1_<?php echo $user_row['gy_user_id']; ?>" onkeyup="check_password_<?php echo $user_row['gy_user_id']; ?>()" minlength="6" maxlength="16" class="form-control" value="<?php echo decryptIt($user_row['gy_password']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>ReType Password</label>
                                <input type="password" name="my_password2" id="my_password2_<?php echo $user_row['gy_user_id']; ?>" onkeyup="check_password_<?php echo $user_row['gy_user_id']; ?>()" minlength="6" maxlength="16" class="form-control" value="<?php echo decryptIt($user_row['gy_password']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label id="warning_<?php echo $user_row['gy_user_id']; ?>"></label>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="submit_user_edit" id="submit_user_<?php echo $user_row['gy_user_id']; ?>" class="btn btn-info" title="click to add user ...">Update <i class="fa fa-angle-right fa-fw"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function check_password_<?php echo $user_row['gy_user_id']; ?>(){
        var pass1 = document.getElementById('my_password1_<?php echo $user_row['gy_user_id']; ?>').value;
        var pass2 = document.getElementById('my_password2_<?php echo $user_row['gy_user_id']; ?>').value;

        if (pass1 == "") {
            document.getElementById('submit_user_<?php echo $user_row['gy_user_id']; ?>').disabled = true;
        }else if (pass2 == "") {
            document.getElementById('submit_user_<?php echo $user_row['gy_user_id']; ?>').disabled = true;
        }else if (pass1 == "" && pass2 == "") {
            document.getElementById('submit_user_<?php echo $user_row['gy_user_id']; ?>').disabled = true;
        }else if (pass1 != pass2) {
            document.getElementById('submit_user_<?php echo $user_row['gy_user_id']; ?>').disabled = true;
            document.getElementById('warning_<?php echo $user_row['gy_user_id']; ?>').innerHTML = "Password Mismatch";
        }else if (pass1 == pass2) {
            document.getElementById('submit_user_<?php echo $user_row['gy_user_id']; ?>').disabled = false;
            document.getElementById('warning_<?php echo $user_row['gy_user_id']; ?>').innerHTML = "";
        }else{
            document.getElementById('submit_user_<?php echo $user_row['gy_user_id']; ?>').disabled = false;
            document.getElementById('warning_<?php echo $user_row['gy_user_id']; ?>').innerHTML = "";
        }
    }
</script>

<!-- Delete -->

<div class="modal fade" id="delete_<?php echo $user_row['gy_user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Delete User <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="delete_user?cd=<?php echo $user_row['gy_user_id']; ?>">
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

<!-- Set -->

<div class="modal fade" id="set_<?php echo $user_row['gy_user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-folder-open fa-fw"></i> Move to archieve user <?php echo $user_row['gy_full_name']; ?> ? <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="update_user_status?cd=<?php echo $user_row['gy_user_id']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Secure PIN</label>
                                <input type="password" name="my_secure_pin" class="form-control" autofocus required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>