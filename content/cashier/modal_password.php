<div class="modal fade" id="changepass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
            </div>
            <div class="modal-body">
                <?php
                    if(isset($_POST['submit'])){
                        $user=$link->query("Select * From `gy_user` Where `gy_user_id`='$user_id'");
                        $var=$user->fetch_array();

                        $true_old_pass = decryptIt($var['gy_password']);

                        $old = $_POST['old_password'];
                        $pass1 = encryptIt($_POST['password1']);
                        $pass2 = encryptIt($_POST['password2']);

                        if($old != $true_old_pass){
                            echo "
                                <script>
                                    window.alert('Old Password Not Exist!');
                                </script>
                            ";
                        }else if($pass1 != $pass2){
                            echo "
                                <script>
                                    window.alert('New Password Mismatch!');
                                </script>
                            ";
                        }else if($old != $true_old_pass && $pass1 != $pass2){
                            echo "
                                <script>
                                    window.alert('Wrong Information!');
                                </script>
                            ";
                        }else{
                            $update=$link->query("Update `gy_user` SET `gy_password`='$pass2' Where `gy_user_id`='$user_id'");
                            //condition
                            if ($update) {
                                echo "
                                    <script>
                                        window.alert('Update Successful, Please Login Again!');
                                        window.location.href = '../../index.php'
                                    </script>
                                ";
                                session_destroy();
                            }
                            
                        }

                    }

                ?>

                 <form method="post" enctype="multipart/form-data">
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                        <input type="password" class="form-control" name="old_password" placeholder="Old Password" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-cogs fa-fw"></i></span>
                        <input type="password" class="form-control" name="password1" placeholder="New Password" required>
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon"><i class="fa fa-cogs fa-fw"></i></span>
                        <input type="password" class="form-control" name="password2" placeholder="Re-type New Password" required>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>