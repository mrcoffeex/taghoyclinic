<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_project_header_title = "Logs";

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
                    <?php  
                        $gettransactions=$link->query("Select * From `gy_tra` Where `gy_tra`.`gy_acc_id`='$account' Order By `gy_trans_code` DESC");
                        $counttrans=$gettransactions->num_rows;

                            if ($counttrans == 0) {
                                echo "<center><p style='color: red; font-size: 17px;'><i class='fa fa-warning'></i> No Transactions</p></center>";
                            }

                        while ($trarow=$gettransactions->fetch_array()) {
                            //tra code
                            $mytracode=words($trarow['gy_trans_code']);
                    ?>
                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th colspan="6">TRA Code: <span style="color: blue;"><?php echo $mytracode; ?></span></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th><center>Date</center></th>
                                        <th><center>Payer</center></th>
                                        <th><center>Method</center></th>
                                        <th style="color: green;"><center>Amount</center></th>
                                        <th style="color: blue;"><center>Deposit Rendered</center></th>
                                        <th><center>Void</center></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        //vars  
                                        // Select ordered items
                                        $my_query="SELECT `gy_trans_code` as `col1`, `gy_trans_date` as `col2`, `gy_trans_custname` as `col3`, `gy_trans_pay` as `col4`, `gy_trans_total` as `col5`, `gy_trans_id` as `col6`, `gy_trans_depositpay` as `col7` From `gy_transaction` Where `gy_tra_code`='$mytracode' UNION SELECT `gy_trans_code` as `col1`, `gy_int_date` as `col2`, NULL as `col3`, NULL as `col4`, `gy_int_value` as `col5`, `gy_int_id` as `col6`, NULL as `col7` From `gy_interest` Where `gy_trans_code`='$mytracode' AND `gy_int_value` != 0 Order By `col2` ASC";
                                        $sql_item_detail=$link->query($my_query);

                                        while ($log_row=$sql_item_detail->fetch_array()) {
                                            //condition
                                            if ($log_row['col4'] != NULL) {
                                                if ($log_row['col4'] == 0) {
                                                    $paymethod = "CASH";
                                                }else if ($log_row['col4'] == 1) {
                                                    $paymethod = "CHEQUE";
                                                }else{
                                                    $paymethod = "CARD";
                                                }

                                                $rowcolor = "success";
                                                $amountcolor = "green";
                                                $redirectval = "pay";
                                            }else{
                                                $paymethod = "INTEREST";
                                                $rowcolor = "danger";
                                                $amountcolor = "red";
                                                $redirectval = "int";
                                            }
                                    ?>                  
                                    <tr class="<?php echo $rowcolor; ?>">
                                        <td style="font-weight: bold;"><center><?php echo date("M d, Y - g:i:s A", strtotime($log_row['col2'])); ?></center></td>
                                        <td style="font-weight: bold;"><center><?php echo $log_row['col3']; ?></center></td>
                                        <td style="font-weight: bold;"><center><?php echo $paymethod; ?></center></td>
                                        <td style="font-weight: bold; color: <?php echo $amountcolor; ?>;"><center><?php echo @number_format($log_row['col5'],2); ?></center></td>
                                        <td style="font-weight: bold; color: blue; ?>;"><center><?php echo @number_format($log_row['col7'],2); ?></center></td>
                                        <td style="font-weight: bold;"><center><button type="button" class="btn btn-danger" title="click to void ..." data-target="#<?php echo $redirectval; ?>_<?php echo $log_row['col1']; ?>" data-toggle="modal"><i class="fa fa-trash-o"></i></button></center></td>
                                    </tr>

                                    <!-- Void -->

                                    <div class="modal fade" id="<?php echo $redirectval; ?>_<?php echo $log_row['col1']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void -> <?php echo @number_format($log_row['col5'],2); ?> </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="void_tra_log?cd=<?php echo $log_row['col1'].'&mode='.$redirectval.'&code='.$log_row['col6']; ?>&dir=<?php echo $my_dir_value; ?>">
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
                    <?php } ?>
                    <br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>