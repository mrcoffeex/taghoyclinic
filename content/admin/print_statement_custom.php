<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $redirect = @$_GET['dir'];

    $my_project_header_title = "PRINT STATEMENT OF ACCOUNT";

    if (isset($_POST['print'])){

        $count = 0;

        $transaction = "SELECT * FROM `gy_tra` Where `gy_trans_code` IN ( ";

        $sql = "SELECT * FROM `gy_tra_details` LEFT JOIN `gy_products` On `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code` IN ( ";

        $trans = @$_POST['trans'];

        if ($trans == 0) {
            echo "
                <script>
                    window.alert('No Transaction Selected');
                    window.location.href = 'tra_trans?cd=$redirect'
                </script>
            ";
        }else{
            foreach($trans as $value){
                $transaction .= "'$value',";
                $sql .= "'$value',";
                $count++;
            }

            if ($count > 0){

                $transaction = substr($transaction,0,-1) . ')';
                $sql = substr($sql,0,-1) . ')';

            }
        }
    }

    $ytotal=0;
    $yinterest=0;
    $ycash=0;
    $transactions=$link->query($transaction);
    while ($transrow=$transactions->fetch_array()) {
        
        $ytotal += $transrow['gy_trans_total'];
        $yinterest += $transrow['gy_trans_interest'];
        $ycash += $transrow['gy_trans_cash'];
        
    }

    $transinfo=$link->query($transaction);
    $inforow=$transinfo->fetch_array();
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
            font-size: 12px;
        }
    </style>

    <script type="text/javascript">
        window.print();
    </script>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 no-print">
                            <a href="tra_trans?cd=<?php echo $redirect; ?>"><button type="button" class="btn btn-success" title="click to back to re-stock counter ..."><i class="fa fa-chevron-circle-left fa-fw"></i> Back</button></a>

                            <button type="button" onclick="window.print();" class="btn btn-primary" title="click to print ..."><i class="fa fa-print fa-fw"></i> Print Result</button>
                        </div>
                        <div class="col-md-12">
                            <p style="font-size: 20px;">
                               <center>
                                <br><br>
                                <span style="font-size: 20px; font-weight: bold;"><?php echo $my_project_name; ?></span><br>
                                <span style="font-size: 17px; font-weight: bold;"><?php echo $my_project_address; ?></span><br>
                                <br>
                                <span style="font-size: 22px; text-transform: uppercase; font-weight: bold;">Statement of Account</span>
                                <br>
                                <span style="font-size: 17px; font-weight: bold;">as of <?php echo date("F d, Y"); ?></span>
                                </center>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p style="font-size: 12px;">
                                <span style="text-transform: uppercase; font-weight: bold;">in account of</span><br>
                                <span style="font-weight: bold; font-size: 15px;"><?php echo $inforow['gy_trans_custname']; ?></span><br>
                            </p>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>                    
                                        <th><center>Date</center></th>                   
                                        <th><center>C#</center></th>                   
                                        <th><center>Particulars</center></th>
                                        <th><center>Amounts</center></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        // Select ordered items
                                        $total=0;

                                        $getitems=$link->query($sql);

                                        while ($row_item_detail=$getitems->fetch_array()) {   

                                        @$total += $row_item_detail['gy_product_price'] * $row_item_detail['gy_trans_quantity']  
                                    ?>                  
                                    <tr>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right;"><?php echo date("m/d/Y", strtotime($row_item_detail['gy_transdet_date'])); ?></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;"><center><?php echo $row_item_detail['gy_trans_code']; ?></center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;"><center><?php echo $row_item_detail['gy_product_name']; ?></center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right;"><?php echo @number_format($row_item_detail['gy_product_price'] * $row_item_detail['gy_trans_quantity'],2); ?></td>
                                    </tr>
                                                
                                <?php } ?>                   
                                    <tr>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right;">&nbsp;</td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;"><center>&nbsp;</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;text-align: right; font-weight: bold;"><center>SubTotal</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right; font-weight: bold;"><?php echo @number_format($ytotal,2); ?></td>
                                    </tr>                  
                                    <tr>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right;">&nbsp;</td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;"><center>&nbsp;</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;text-align: right; font-weight: bold;"><center>Interest</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right; font-weight: bold;"><?php echo @number_format($yinterest,2); ?></td>
                                    </tr>                  
                                    <tr>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right;">&nbsp;</td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;"><center>&nbsp;</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;text-align: right; font-weight: bold;"><center>Total</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right; font-weight: bold;"><?php echo @number_format($ytotal + $yinterest,2); ?></td>
                                    </tr>                  
                                    <tr>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right;">&nbsp;</td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;"><center>&nbsp;</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;text-align: right; font-weight: bold;"><center>Paid Amount</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right; font-weight: bold;"><?php echo @number_format($ycash,2); ?></td>
                                    </tr>                  
                                    <tr>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right;">&nbsp;</td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;"><center>&nbsp;</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px;text-align: right; font-weight: bold;"><center>Balance</center></td>
                                        <td class="pla" style="padding: 4px; font-size: 15px; text-align: right; font-weight: bold;"><?php echo @number_format(($ytotal + $yinterest - $ycash),2); ?></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <p>Prepared By: <?php echo $user_info; ?> <span class="pull-right">RECEIVED BY:</span></p>
                        </div>
                        <div class="col-md-12">
                            <p>Checked By: _____________________________ <span class="pull-right">_____________________________</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <center><p><b><i>Ask for Interest ...</i></b></p></center>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>