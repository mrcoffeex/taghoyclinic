<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $condition = @$_GET['condition'];
    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];

    if ($condition == "cash") {

        if ($datef == $datet) {
            $date_format = date("M d, Y", strtotime($datef));
        }else{
            $date_format = date("M d - ", strtotime($datef))." ".date("M d, Y", strtotime($datet));
        }

        //title
        $my_title = "Cash Expenses Report: ".$date_format;

        //query
        $query_one=$link->query("Select * From `gy_expenses` Where `gy_exp_type`='CASH' AND date(`gy_exp_date`) BETWEEN '$datef' AND '$datet' Order By `gy_exp_date` ASC");

    }else{

        //title
        if ($datef == $datet) {
            $date_format = date("M d, Y", strtotime($datef));
        }else{
            $date_format = date("M d - ", strtotime($datef))." ".date("M d, Y", strtotime($datet));
        }

        //title
        $my_title = "Company Expenses Report: ".$date_format;

        //query
        $query_one=$link->query("Select * From `gy_expenses` Where `gy_exp_type`!='CASH' AND date(`gy_exp_date`) BETWEEN '$datef' AND '$datet' Order By `gy_exp_date` ASC");

    }

    $count_results=$query_one->num_rows;

    $my_project_header_title = $my_title;

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
                            <button type="button" onclick="window.print();" class="btn btn-primary" title="click to print ..."><i class="fa fa-print fa-fw"></i> Print Result</button>
                        </div>
                        <div class="col-md-12">
                            <h4 style="font-weight: bold; margin-bottom: -10px; margin-top: 30px;"><center><?php echo $my_project_name; ?></center>
                            <p style="font-size: 20px;">
                               <center>
                                <span style="font-size: 20px; font-weight: bold;"><?php echo $my_project_header_title; ?></span><br>
                                <span style="font-size: 13px;">Date printed: <?php echo date("F d, Y"); ?></span><br><br>
                                </center>
                            </p>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th><center>No.</center></th>
                                        <th><center>Date</center></th>
                                        <th><center>Note</center></th>
                                        <th><center>Amount</center></th>
                                        <th><center>User</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        //vars
                                        $numrow = 0;
                                        $totalexp = 0;
                                        while ($row_item_detail=$query_one->fetch_array()) {
                                            $numrow++;
                                        //get user
                                        $myuserdataexp=words($row_item_detail['gy_user_id']);
                                        $getuserdatahere=$link->query("Select * From `gy_user` Where `gy_user_id`='$myuserdataexp'");
                                        $userdatahererow=$getuserdatahere->fetch_array();

                                        //get approved
                                        $myappdataexp=words($row_item_detail['gy_approved_by']);
                                        $getappdatahere=$link->query("Select * From `gy_user` Where `gy_user_id`='$myappdataexp'");
                                        $appdatahererow=$getappdatahere->fetch_array();

                                        @$totalexp += $exp_row['gy_exp_amount'];       
                                    ?>                  
                                    <tr>
                                        <td style="font-weight: bold; padding: 1px;"><center><?php echo $numrow; ?></center></td>
                                        <td style="padding: 1px;"><?php echo date("Md g:i:s A", strtotime($row_item_detail['gy_exp_date'])); ?></td>
                                        <td style="padding: 1px;"><?php echo $row_item_detail['gy_exp_note']." <b>Approved By: ".$appdatahererow['gy_full_name']."</b>"; ?></td>
                                        <td style="font-weight: bold; padding: 1px;"><?php echo @number_format($row_item_detail['gy_exp_amount'],2); ?></td>
                                        <td style="font-weight: bold; padding: 1px;"><center><?php echo $userdatahererow['gy_full_name']; ?></center></td>
                                    </tr>
                                                
                                <?php } ?>

                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                        <td><center>Total</center></td>
                                        <td><center><?php echo @number_format($totalexp,2); ?></center></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    
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