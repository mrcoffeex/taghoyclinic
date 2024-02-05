
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Profile Background</h4>
            </div>
            <div class="modal-body">
                <?php
                    include('../../conf/conn.php');
                    $profile=$link->query("Select * From `gy_user` Where `gy_user_id`='$user_id'");
                    $pro=$profile->fetch_array();

                    $my_user_id=$pro['gy_user_id'];
                ?>

                <div class="panel-body">
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="update_profile?cd=<?php echo $my_user_id; ?>">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="my_acc_name" value="<?php echo $pro['gy_full_name']; ?>" class="form-control" placeholder="Enter Your Profile Username here ..." autofocus required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="my_prof_username" value="<?php echo $pro['gy_username']; ?>" class="form-control" placeholder="Enter Your Profile Username here ..." required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="update_profile" class="btn btn-info"><i class="fa fa-edit fa-fw"></i> Update Profile</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>