<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_header_tite = "User Archieve";

    $my_query="Select * From `gy_user` Where `gy_user_status`!='0'";

    $my_notification = @$_GET['note'];

    if ($my_notification == "user_recover") {
        $color_note = "green";
        $message = "User has been recovered.";
        $iconize = "fa fa-check";
    }else if ($my_notification == "error") {
        $color_note = "red";
        $message = "Theres something wrong here";
        $iconize = "fa fa-warning";
    }else if ($my_notification == "pin_out") {
        $color_note = "red";
        $message = "Password Mismatch";
        $iconize = "fa fa-key";
    }else{
        $color_note = "#fff";
        $message = "";
        $iconize = "";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>

    <style type="text/css">
        img{
            max-width:180px;
        }

        input[type=file]{
            padding:0px;
        }

        @media print{
            .no-print{
                display: none !important;
            }

            .my_hr{
                height: 5px;
                color: #000;
                background-color: #000;
                border: none;
            }

            td{
                background-color: rgba(255,255,255, 0.1);
            }
        }

        .my_hr{
            height: 5px;
            color: #000;
            background-color: #000;
            border: none;
        }

        td{
            background-color: rgba(255,255,255, 0.1);
            font-size: 14px;
        }
    </style>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <p style="font-size: 20px;">
                               <center>
                                <span style="font-size: 20px; font-weight: bold;"><i class="fa fa-folder-open"></i> USER ARCHIEVE</span><br>
                                </center>
                            </p>
                        </div>
                        <?php  
                            if ($my_notification == "") {
                                # empty
                            }else{
                        ?>
                        <div class="col-md-12">
                            <p>
                               <center>
                                <span style="font-size: 17px; color: <?php echo $color_note; ?> ;"><i class="<?php echo $iconize; ?>"></i> <?php echo $message; ?></span> <br>
                                </center>
                            </p>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th><center>Account Name</center></th>
                                        <th><center>Username</center></th>
                                        <th><center>Type</center></th>
                                        <th><center>Recover</center></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        //vars  
                                        // Select ordered items
                                        $sql_item_detail=$link->query($my_query);

                                        while ($log_row=$sql_item_detail->fetch_array()) {
                                            //roles
                                            if ($log_row['gy_user_type'] == "0") {
                                                $my_roles = "Admin";
                                            }else if ($log_row['gy_user_type'] == "1") {
                                                $my_roles = "Salesman";
                                            }else if ($log_row['gy_user_type'] == "2") {
                                                $my_roles = "Cashier";
                                            }else if ($log_row['gy_user_type'] == "3") {
                                                $my_roles = "Moderator";
                                            }else if ($log_row['gy_user_type'] == "4") {
                                                $my_roles = "Bodega Staff";
                                            }else if ($log_row['gy_user_type'] == "5") {
                                                $my_roles = "Salesman Encoder";
                                            }else{
                                                $my_roles = "unknown";
                                            }
                                    ?>                  
                                    <tr class="warning">
                                        <td style="font-weight: bold;"><center><?php echo $log_row['gy_full_name']; ?></center></td>
                                        <td style="font-weight: bold;"><center><?php echo $log_row['gy_username']; ?></center></td>
                                        <td style="font-weight: bold;"><center><?php echo $my_roles; ?></center></td>
                                        <td><center><button type="button" class="btn btn-success" title="click to recover ..." data-target="#rec_<?php echo $log_row['gy_user_id']; ?>" data-toggle="modal"><i class="fa fa-chevron-circle-right"></i></button></center></td>
                                    </tr>

                                    <!-- Recover -->

                                    <div class="modal fade" id="rec_<?php echo $log_row['gy_user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-chevron-circle-right fa-fw"></i> Recover user <?php echo $log_row['gy_full_name']; ?> ? </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="update_user_status?cd=<?php echo $log_row['gy_user_id']; ?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
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
                                                
                                <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>