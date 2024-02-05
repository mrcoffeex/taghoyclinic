<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_project_header_title = "Deposit Logs";

    $my_dir_value = @$_GET['cd'];

    $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_dir_value'");
    $accountrow=$getaccount->fetch_array();

    //account code
    $account=words($accountrow['gy_acc_id']);
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
                                <span style="font-size: 20px; font-weight: bold;"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></span><br>
                                </center>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th><center>Date</center></th>
                                        <th style="color: green;"><center>Amount</center></th>
                                        <th><center>User</center></th>
                                        <th><center>Void</center></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        //vars  
                                        //get deposit logs
                                        $my_query="Select * From `gy_deposit` Where `gy_acc_id`='$account'";
                                        $sql_item_detail=$link->query($my_query);

                                        while ($log_row=$sql_item_detail->fetch_array()) {
                                            //get user info
                                            $useridentify=words($log_row['gy_dep_by']);
                                            $getuseracc=$link->query("Select * From `gy_user` Where `gy_user_id`='$useridentify'");
                                            $useraccrow=$getuseracc->fetch_array();

                                    ?>                  
                                    <tr class="info">
                                        <td style="font-weight: bold;"><center><?php echo date("M d, Y - g:i:s A", strtotime($log_row['gy_dep_date'])); ?></center></td>
                                        <td style="font-weight: bold;"><?php echo @number_format($log_row['gy_dep_amount'],2); ?></td>
                                        <td style="font-weight: bold;"><center><?php echo $useraccrow['gy_full_name']; ?></center></td>
                                        <td style="font-weight: bold;"><center><button type="button" class="btn btn-danger" title="click to void ..." data-target="#void_<?php echo $log_row['gy_dep_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o"></i></button></center></td>
                                    </tr>

                                    <!-- Void -->

                                    <div class="modal fade" id="void_<?php echo $log_row['gy_dep_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void -> <?php echo @number_format($log_row['gy_dep_amount'],2); ?> </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="void_deposit_log?cd=<?php echo $log_row['gy_dep_id']; ?>&acc=<?php echo $log_row['gy_acc_id']; ?>">
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
                    <br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>