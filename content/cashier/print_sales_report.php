<?php 
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $date_now = date("Y-m-d");

    //get number of transactions
    $get_trans_quantity=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_user_id`='$user_id'");
    $total_trans_num=$get_trans_quantity->num_rows;

    $total_ref_rep="";
    $get_ref_summ=$link->query("Select * From `gy_refund` Where date(`gy_refund_date`)='$date_now' AND `gy_user_id`='$user_id'");
    while ($ref_summ_row=$get_ref_summ->fetch_array()) {

        @$total_ref_rep += $ref_summ_row['gy_product_price'] * $ref_summ_row['gy_product_quantity'];
    }

    //total expenses
    $totalExpenses = getTotalExpenses($date_now, $date_now, $user_id);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Print Sales Report</title>           
        <!-- Bootstrap -->
            <!-- <link href="images/logo.png" rel="icon" type="image"> -->
        <link href="print/logo_web.png" rel="icon" type="image">
        <link href="custom/mine.css" rel="stylesheet" >
        <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
        <meta name=Generator content="Microsoft Word 14 (filtered)">
        <style>
         @font-face
            {font-family:Calibri;
            panose-1:2 15 5 2 2 2 4 3 2 4;}
        @font-face
            {font-family:Tahoma;
            panose-1:2 11 6 4 3 5 4 4 2 4;}
         p.MsoNormal, li.MsoNormal, div.MsoNormal
            {margin-top:0in;
            margin-right:0in;
            margin-bottom:8.0pt;
            margin-left:0in;
            line-height:107%;
            font-size:11.0pt;
            font-family:"Calibri","sans-serif";}
        p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
            {mso-style-link:"Balloon Text Char";
            margin:0in;
            margin-bottom:.0001pt;
            font-size:8.0pt;
            font-family:"Tahoma","sans-serif";}
        span.BalloonTextChar
            {mso-style-name:"Balloon Text Char";
            mso-style-link:"Balloon Text";
            font-family:"Tahoma","sans-serif";}
        .MsoChpDefault
            {font-family:"Calibri","sans-serif";}
        .MsoPapDefault
            {margin-bottom:8.0pt;
            line-height:107%;}
        @page WordSection1
            {size:13.0in 8.5in;
            margin:48.25pt .5in .5in .75in;}
        div.WordSection1
            {page:WordSection1;}

        @media print{
            .no-view{
                display: none !important;
            }
        }

        @page{
            margin: 1cm 0cm 0cm 0cm;
        }

        th,td{
            padding: 1px;
        }
        </style>
    </head>
    
    <body onload="window.print();">
        <!-- <div style="position: absolute;">
            <img src="print/r_background.png" alt="Notebook" style="width:100%; height: 100%; margin-left: 100px; margin-top: -50px; opacity: 0.05;">
        </div> -->
        <div class="block-content collapse in">
            <div class="span12">
                <div>
                    <p style="font-size: 20px;">
                        <center><span style="font-size: 30px;">Sales Report - <span style="color: blue;"><?php echo date("M d, Y", strtotime($date_now)); ?></span><br></span></center>
                    </p>
                </div>
                    <?php  
                        //get salesman
                        $get_salesman_info=$link->query("Select DISTINCT(`gy_prepared_by`) As `my_salesman` From `gy_transaction` Where `gy_transaction`.`gy_user_id`='$user_id' AND date(`gy_transaction`.`gy_trans_date`)='$date_now' Order By `gy_transaction`.`gy_prepared_by` ASC");
                        $count_salesman=$get_salesman_info->num_rows;
                        while ($salesman_info_row=$get_salesman_info->fetch_array()) {
                            
                            $mysalesmandataguide=words($salesman_info_row['my_salesman']);
                            $getsalesmandata=$link->query("Select * From `gy_user` Where `gy_user_id`='$mysalesmandataguide'");
                            $salesmandatarow=$getsalesmandata->fetch_array();

                            //id values
                            $salesman_identity=words($salesman_info_row['my_salesman']);

                            //get sales reports by salesman
                            $query_one=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' AND `gy_user_id`='$user_id' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_code` ASC");

                            $order_no=0;
                            $my_final_total=0;

                            $count_results=$query_one->num_rows;

                    ?>
                    <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-bottom: 15px;">
                        <thead class=""> 
                            <tr class="nmgs">                    
                                <th style="font-size: 18px;" colspan="3"><center><b><?php echo $salesmandatarow['gy_full_name']; ?></b> Sales</center></th>          
                            </tr>       
                            <tr>                    
                                <th style="font-size: 12px;">No.</th>
                                <th style="font-size: 12px;">TransCode</th>
                                <th style="font-size: 12px;">Total</th>           
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                //get products
                                //make pagination
                                while ($data_row=$query_one->fetch_array()) {

                                    @$order_no += 1;
                                    @$my_final_total += $data_row['gy_trans_total'];

                                    //get check trans
                                    if ($data_row['gy_trans_pay'] == 1) {
                                        $data_row_color = "#de9100";
                                    }else if ($data_row['gy_trans_pay'] == 2) {
                                        $data_row_color = "#0000ff";
                                    }else{
                                        $data_row_color = "#000";
                                    }
                            ?>                  
                                <tr>
                                    <td class="pla" style="font-size: 12px; color: <?php echo $data_row_color ?>;"><?php echo $order_no; ?></td>
                                    <td class="pla" style="font-size: 12px; color: <?php echo $data_row_color ?>;"><?php echo $data_row['gy_trans_code']; ?></td>
                                    <td class="pla" style="font-size: 12px; color: <?php echo $data_row_color ?>;"><?php echo number_format($data_row['gy_trans_total'],2); ?></td>
                                </tr>
                                            
                            <?php } ?> 
                                <tr>
                                    <td class="pla" style="color: blue; font-size: 12px;"><?php echo $order_no; ?></td>
                                    <td class="pla" style="color: blue; font-size: 12px;"><b>Total</b></td>
                                    <td class="pla" style="color: blue; font-size: 12px;"><b><?php echo @number_format(0+$my_final_total,2); ?></b></td>
                                </tr>                             
                        </tbody>
                    </table>
                    <?php 
                        //total sales
                        $grand_total=0;
                        $get_total_sales=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id'");
                        while ($total_sales_row=$get_total_sales->fetch_array()) {
                            @$grand_total += $total_sales_row['gy_trans_total'];
                        }
                    ?>

                    <?php } ?>

                    <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-bottom: 15px;">
                        <thead class="">
                            <tr class="nmgs">
                                <th colspan="2" style="font-size: 18px; color: green;"><center>GROSS SALES</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                //get salesman
                                $get_salesman_info=$link->query("Select DISTINCT(`gy_prepared_by`) As `my_salesman` From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_code` ASC");
                                $count_salesman=$get_salesman_info->num_rows;
                                while ($salesman_info_row=$get_salesman_info->fetch_array()) {

                                    //id values
                                    $salesman_identity=words($salesman_info_row['my_salesman']);

                                    ///get salesman info
                                    $getsalesmanfullname=$link->query("Select * From `gy_user` Where `gy_user_id`='$salesman_identity'");
                                    $salesnamerow=$getsalesmanfullname->fetch_array();

                                    //get sales reports by salesman
                                    $query_one=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' AND date(`gy_trans_date`)='$date_now' AND `gy_user_id`='$user_id' Order By `gy_trans_code` ASC");

                                    $order_no=0;
                                    $my_final_total=0;

                                    $count_results=$query_one->num_rows;
                            ?>
                            <?php 
                                    //total total sales and salesman
                                    $salesman_total=0;
                                    while ($final_row=$query_one->fetch_array()) {
                                        @$salesman_total += $final_row['gy_trans_total'];
                                    }
                                ?>
                                <tr>
                                    <td class="pla" style="color: green; font-size: 15px;"><center><?php echo $salesnamerow['gy_full_name']; ?></center></td>
                                    <td class="pla" style="color: green; font-size: 15px;"><center><?php echo @number_format(0+$salesman_total,2); ?></center></td>
                                </tr>
                            <?php } ?>
                                <tr>
                                    <td class="pla" style="color: green; font-size: 15px;"><center>TOTAL GROSS SALES</center></td>
                                    <td class="pla" style="color: green; font-size: 15px;"><center><?php echo @number_format(0+$grand_total,2); ?></center></td>
                                </tr>
                        </tbody>
                    </table>

                    <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-bottom: 15px;">
                        <thead class="">
                            <tr class="nmgs">
                                <th colspan="3" style="font-size: 18px; color: red;"><center>EXPENSES</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                //get salesman
                                $totalExpenses=0;
                                $getExpenses=selectExpenses($date_now, $date_now, $user_id);
                                $countExpenses=$getExpenses->num_rows;
                                while ($exp=$getExpenses->fetch_array()) {
                                    $totalExpenses += $exp['gy_exp_amount'];
                            ?>
                            <tr>
                                <td class="pla" style="color: red; font-size: 15px;"><center><?php echo $exp['gy_exp_date']; ?></center></td>
                                <td class="pla" style="color: red; font-size: 15px;"><center><?php echo @number_format(0 + $exp['gy_exp_amount'],2); ?></center></td>
                                <td class="pla" style="color: red; font-size: 15px;"><center><?php echo $exp['gy_exp_note']; ?></center></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td class="pla" style="color: red; font-size: 15px;"><center>TOTAL EXPENSES</center></td>
                                <td class="pla" style="color: red; font-size: 15px;"><center><?php echo @number_format(0+$totalExpenses,2); ?></center></td>
                                <td class="pla" style="color: red; font-size: 15px;"><center>&nbsp;</center></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-bottom: 15px;">
                        <thead class="">
                            <tr class="nmgs">
                                <th colspan="2" style="font-size: 18px;"><center>SUMMARY</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="pla" style="font-size: 15px; color: blue;"><center>No. of Transactions</center></td>
                                <td class="pla" style="font-size: 15px; color: blue;"><center><?php echo 0+$total_trans_num; ?></center></td>
                            </tr>
                            <tr>
                                <td class="pla" style="font-size: 15px; color: green;"><center>TOTAL GROSS SALES</center></td>
                                <td class="pla" style="font-size: 15px; color: green; "><center><?php echo @number_format(0 + $grand_total,2); ?></center></td>
                            </tr>
                            <tr>
                                <td class="pla" style="font-size: 15px; color: red;"><center>EXPENSES/center></td>
                                <td class="pla" style="font-size: 15px; color: red;"><center><?php echo @number_format(0 + $totalExpenses,2); ?></center></td>
                            </tr> 
                            <tr>
                                <td class="pla" style="font-size: 15px; color: red;"><center>REPLACE/REFUND</center></td>
                                <td class="pla" style="font-size: 15px; color: red;"><center><?php echo @number_format(0 + $total_ref_rep,2); ?></center></td>
                            </tr>  
                            <tr>
                                <td class="pla" style="font-size: 15px; color: blue;"><center>NET SALES</center></td>
                                <td class="pla" style="font-size: 15px; color: blue;"><center><?php echo @number_format((0 + $grand_total) - ($totalExpenses + $total_ref_rep),2); ?></center></td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>  
    </body>
</html>