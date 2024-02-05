<?php 
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];
    $datef = @$_GET['datef'];
    $datet = @$_GET['datet'];
    $condition = @$_GET['condition'];

    if ($datef == $datet) {
        $my_range = date("F d, Y", strtotime($datef));
    }else{
        $my_range = date("F d", strtotime($datef))." to ".date("F d, Y", strtotime($datet));
    }
    
    //get cashier info
    $get_cashier_per_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_dir_value'");
    $my_master_info_row=$get_cashier_per_info->fetch_array();

    $my_master_id = $my_master_info_row['gy_user_id'];

    $my_project_header_title = $my_master_info_row['gy_full_name']." Sales Report On: ".$my_range;

    // start here
    $datefirst = $datef;
    $date1 = date("Y-m-d", strtotime("-1 day", strtotime($datefirst)));
    $date2 = $datet;
        
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $my_project_header_title; ?></title>           
        <!-- Bootstrap -->
            <!-- <link href="images/logo.png" rel="icon" type="image"> -->
        <link href="print/logo_web.png" rel="icon" type="image">
        <link href="custom/mine.css" rel="stylesheet" >
        <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
        <meta name=Generator content="Microsoft Word 14 (filtered)">
        <style>
         /* Font Definitions */
         @font-face
            {font-family:Calibri;
            panose-1:2 15 5 2 2 2 4 3 2 4;}
        @font-face
            {font-family:Tahoma;
            panose-1:2 11 6 4 3 5 4 4 2 4;}
         /* Style Definitions */
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
            .page-break  { 
                clear: both;
                page-break-after: always;
            }
        }

        @page{
            margin: 5mm 0cm 0cm 0cm;
        }

        th,td{
            padding: 1px;
        }
        </style>
    </head>
    
    <body onload="window.print();">
        <!-- <div style="position: absolute;">
            <img src="print/r_background.png" alt="Notebook" style="width:100%; height: 100%; margin-left: 100px; margin-top: -50px; opacity: 0.1;">
        </div> -->

        <!-- <p style="font-size: 20px;">
            <center><span style="font-size: 15px;" class="no-view">check the <b>background graphics</b> in <i>print preview</i> to Show colors.</span></center>
        </p> -->

        <?php  
            //start here
            while (strtotime($date1) < strtotime($date2)) {

                $date1 = date ("Y-m-d", strtotime("+1 day", strtotime($date1)));

                //get number of transactions
                $get_trans_quantity=$link->query("Select * From `gy_transaction` Where `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1'");
                $total_trans_num=$get_trans_quantity->num_rows;

                //get expenses
                $get_exp=$link->query("Select SUM(`gy_exp_amount`) As `total_exp` From `gy_expenses` Where `gy_user_id`='$my_master_id' AND date(`gy_exp_date`)='$date1'");
                $exp_row=$get_exp->fetch_array();

                $my_total_exp=$exp_row['total_exp'];

                //total sales data
                $my_check_change_total="";
                $royal_fee_total_display="";
                $get_total_royal_fee_by_cashier=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_pay`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1' Order By `gy_trans_code` ASC");
                while ($royal_row=$get_total_royal_fee_by_cashier->fetch_array()) {

                    //get royal fee
                    $get_perct = $royal_row['gy_trans_check_per'] / 100;

                    $royal_fee_total = $royal_row['gy_trans_royal_fee'];
                    @$royal_fee_total_display += $royal_row['gy_trans_royal_fee'];

                    $my_math_a = 100 - $royal_row['gy_trans_check_per'];
                    $my_math_b = $royal_row['gy_trans_royal_fee'] / $royal_row['gy_trans_check_per'];

                    @$my_check_change_total += $my_math_a * $my_math_b;
                }

                //get card payments
                @$cardpayments=0;
                $getcard=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date1' AND `gy_trans_type`='1' AND `gy_trans_pay`='2' AND `gy_trans_status`='1' AND `gy_user_id`='$my_master_id' Order By `gy_trans_code` ASC");
                while ($cardrow=$getcard->fetch_array()) {
                    @$cardpayments += $cardrow['gy_trans_total'];
                }

                @$totaldepcash=0;
                $getdepdatacash=$link->query("Select * From `gy_deposit` Where date(`gy_dep_date`)='$date1' AND `gy_user_id`='$my_master_id' AND `gy_dep_method`='0'");
                while ($cashdeprow=$getdepdatacash->fetch_array()) {
                    @$totaldepcash += $cashdeprow['gy_dep_amount'];
                }

                @$totaldepcheq=0;
                $getdepdatacheq=$link->query("Select * From `gy_deposit` Where date(`gy_dep_date`)='$date1' AND `gy_user_id`='$my_master_id' AND `gy_dep_method`='1'");
                while ($cheqdeprow=$getdepdatacheq->fetch_array()) {
                    @$totaldepcheq += $cheqdeprow['gy_dep_amount'];
                }

                @$totaldeps = $totaldepcash + $totaldepcheq;

                //get remittance total
                $remit_total="";
                $get_remit=$link->query("Select * From `gy_remittance` Where `gy_user_id`='$my_master_id' AND date(`gy_remit_date`)='$date1'");
                while ($remit_row=$get_remit->fetch_array()) {
                   @$remit_total += $remit_row['gy_remit_value'];
                }

                //get partial remittance total
                $partial_remit_total="";
                $get_partial_remit=$link->query("Select * From `gy_remittance` Where `gy_user_id`='$my_master_id' AND `gy_remit_type`='0' AND date(`gy_remit_date`)='$date1'");
                while ($partila_remit_row=$get_partial_remit->fetch_array()) {
                   @$partial_remit_total += $partila_remit_row['gy_remit_value'];
                }

                //get check remittance total
                $check_remit_total="";
                $get_check_remit=$link->query("Select * From `gy_remittance` Where `gy_user_id`='$my_master_id' AND `gy_remit_type`='2' AND date(`gy_remit_date`)='$date1'");
                while ($check_remit_row=$get_check_remit->fetch_array()) {
                   @$check_remit_total += $check_remit_row['gy_remit_value'];
                }

                //get fulk remittance total
                @$full_remit_total = @$remit_total - (@$partial_remit_total + @$check_remit_total);

                //get the latest beginning cash
                $get_beg=$link->query("SElect * From `gy_begin_cash` Where `gy_beg_by`='$my_master_id' Order By `gy_beg_id` DESC LIMIT 1");
                $get_beginning_cash_row=$get_beg->fetch_array();

                //cash breakdown
                $get_break=$link->query("Select * From `gy_breakdown` Where `gy_user_id`='$my_master_id' AND date(`gy_break_date`)='$date1'");
                $break_row=$get_break->fetch_array();

                //breakdown
                $a_a = 1000 * $break_row['gy_break_a_a'];
                $a_b = 500 * $break_row['gy_break_a_b'];
                $a_c = 200 * $break_row['gy_break_a_c'];
                $a_d = 100 * $break_row['gy_break_a_d'];
                $a_e = 50 * $break_row['gy_break_a_e'];
                $a_f = 20 * $break_row['gy_break_a_f'];
                $a_g = 10 * $break_row['gy_break_a_g'];
                $a_h = 5 * $break_row['gy_break_a_h'];
                $a_i = 1 * $break_row['gy_break_a_i'];

                @$total_cash_breakdown = $partial_remit_total + $a_a + $a_b + $a_c + $a_d + $a_e + $a_f + $a_g + $a_h + $a_i;

                if ($total_trans_num == 0) {
                    $announce = " - No Tranasction";
                    $daycolor = "#000";
                    $bgcolor = "#fff";
                    $myclass = "no-view";
                }else{
                    $announce = "";
                    $daycolor = "#fff";
                    $bgcolor = "#000";
                    $myclass = "";
                }
                
        ?>
        <div class="block-content collapse in <?php echo $myclass; ?>">
            <div class="span12">
                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 98%; float: left; margin-left: 10px; margin-bottom: 0px; margin-top: 30px;">
                    <thead class=""> 
                        <tr class="nmgs" style="background-color: <?php echo $bgcolor; ?>;">                    
                            <th style="font-size: 18px; color: <?php echo $daycolor; ?>; padding: 3px;" colspan="3"><center><b><?php echo date("F d, Y", strtotime($date1))."".$announce." - ".$my_master_info_row['gy_full_name']; ?></b></center></th>          
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="block-content collapse in">
            <div class="span12">
                <?php
                    if ($total_trans_num == 0) {
                        //empty no tables and data
                    }else{

                    //salesman view
                    if ($condition == 0) {
                        //empty view
                    }else{

                    //get salesman
                    $get_salesman_info=$link->query("Select DISTINCT(`gy_prepared_by`) As `my_salesman` From `gy_transaction` Where `gy_transaction`.`gy_user_id`='$my_master_id' AND date(`gy_transaction`.`gy_trans_date`)='$date1' Order By `gy_transaction`.`gy_prepared_by` ASC");
                    $count_salesman=$get_salesman_info->num_rows;
                    while ($salesman_info_row=$get_salesman_info->fetch_array()) {

                        $mysalesmandataguide=words($salesman_info_row['my_salesman']);
                        $getsalesmandata=$link->query("Select * From `gy_user` Where `gy_user_id`='$mysalesmandataguide'");
                        $salesmandatarow=$getsalesmandata->fetch_array();

                        //id values
                        $salesman_identity=words($salesman_info_row['my_salesman']);

                        //get sales reports by salesman
                        $query_one=$link->query("Select `gy_trans_total`,`gy_trans_pay`,`gy_trans_code` From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1' Order By `gy_trans_code` ASC");

                        $order_no="";
                        $my_final_total="";

                        $count_results=$query_one->num_rows;
                ?>
                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 32%; float: left; margin-left: 10px; margin-bottom: 0px;">
                    <thead class=""> 
                        <tr class="nmgs">                    
                            <th style="font-size: 18px;" colspan="3"><center><b><?php echo $salesmandatarow['gy_full_name']; ?></b></center></th>          
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
                                <td class="pla" style="font-size: 12px; color: <?php echo $data_row_color; ?>;"><?php echo $order_no; ?></td>
                                <td class="pla" style="font-size: 12px; color: <?php echo $data_row_color; ?>;"><?php echo $data_row['gy_trans_code']; ?></td>
                                <td class="pla" style="font-size: 12px; color: <?php echo $data_row_color; ?>;"><?php echo number_format($data_row['gy_trans_total'],2); ?></td>
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
                        } 
                    }

                ?> 

                <?php 
                    //total sales
                    $grand_total="";
                    $get_total_sales=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1'");
                    while ($total_sales_row=$get_total_sales->fetch_array()) {
                        @$grand_total += $total_sales_row['gy_trans_total'];
                    }
                ?>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 98%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="4" style="font-size: 18px; color: red;"><center>REPLACE/REFUND SUMMARY</center></th>
                        </tr>
                        <tr>
                            <th class="pla" style="font-size: 12px;"><center>Trans. Code</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Customer</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Amount</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Note</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $total_ref_rep="";
                            $get_ref_summ=$link->query("Select * From `gy_refund` Where date(`gy_refund_date`)='$date1' AND `gy_user_id`='$my_master_id'");
                            while ($ref_summ_row=$get_ref_summ->fetch_array()) {

                                @$total_ref_rep += $ref_summ_row['gy_product_price'] * $ref_summ_row['gy_product_quantity'];

                        ?>
                        <tr>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo $ref_summ_row['gy_trans_code']; ?></center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo $ref_summ_row['gy_trans_custname']; ?></center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo @number_format((0 + $ref_summ_row['gy_product_price']) * $ref_summ_row['gy_product_quantity'],2); ?></center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo $ref_summ_row['gy_refund_note']; ?></center></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="pla" style=""><center></center></td>
                            <td class="pla" style=" color: red; font-size: 12px;"><center>TOTAL</center></td>
                            <td class="pla" style=" color: red; font-size: 12px;"><center><?php echo @number_format(0 + $total_ref_rep,2); ?></center></td>
                            <td class="pla" style=""><center></center></td>
                        </tr>
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 98%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="4" style="font-size: 18px;"><center>DEPOSIT</center></th>
                        </tr>
                        <tr>
                            <th class="pla" style="font-size: 12px;"><center>Account</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Encoded By</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Type</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Amount</center></th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php  
                        $totaldepamount=0;
                        $getdepdata=$link->query("Select * From `gy_deposit` Where date(`gy_dep_date`)='$date1' AND `gy_user_id`='$my_master_id'");
                        while ($depdatarow=$getdepdata->fetch_array()) {

                            $myaccdepidentify=words($depdatarow['gy_acc_id']);
                            $myuserdepidentify=words($depdatarow['gy_dep_by']);
                            @$totaldepamount += $depdatarow['gy_dep_amount'];

                            //get account data
                            $getinfoacc=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$myaccdepidentify'");
                            $infoaccrow=$getinfoacc->fetch_array();

                            //get user data
                            $getdepuserdata=$link->query("Select * From `gy_user` Where `gy_user_id`='$myuserdepidentify'");
                            $depuserdatarow=$getdepuserdata->fetch_array();

                            if ($depdatarow['gy_dep_method'] == 0) {
                                $mydepmethod = "CASH";
                                $mydeprow = "#000";
                            }else{
                                $mydepmethod = "CHEQUE";
                                $mydeprow = "#de9100";
                            }
                        ?>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: <?php echo $mydeprow; ?>;"><center><?php echo $infoaccrow['gy_acc_name']; ?></center></td>
                            <td class="pla" style="font-size: 12px; color: <?php echo $mydeprow; ?>;"><center><?php echo $depuserdatarow['gy_full_name']; ?></center></td>
                            <td class="pla" style="font-size: 12px; color: <?php echo $mydeprow; ?>;"><center><?php echo $mydepmethod; ?></center></td>
                            <td class="pla" style="font-size: 12px; color: <?php echo $mydeprow; ?>;"><center><?php echo @number_format($depdatarow['gy_dep_amount'],2); ?></center></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="pla" style=""><center></center></td>
                            <td class="pla" style=""><center></center></td>
                            <td class="pla" style="font-size: 12px;"><center>TOTAL</center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format(0 + $totaldepamount,2); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 98%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="4" style="font-size: 18px; color: blue;"><center>CARD PAYMENTS</center></th>
                        </tr>
                        <tr>
                            <th class="pla" style="font-size: 12px;"><center>Trans. Code</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Amount</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Fee</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Total</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $cardmaintotalpays=0;
                            $cardtotalpays=0;
                            $getcardpayments=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_pay`='2' AND `gy_trans_status`='1' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1' Order By `gy_trans_code` ASC");
                            while ($cardpayrow=$getcardpayments->fetch_array()) {
                                //get toiotal without fee
                                $cardp = $cardpayrow['gy_trans_cardcent'] / 100;
                                $feevalue = $cardp * $cardpayrow['gy_trans_total'];
                                $mainvalue = $cardpayrow['gy_trans_total'] - $feevalue;

                                @$cardmaintotalpays += $mainvalue;
                                @$cardtotalpays += $cardpayrow['gy_trans_total'];
                        ?>
                        <tr>
                            <td class="pla" style="color: blue; font-size: 12px;"><center><?php echo $cardpayrow['gy_trans_code']; ?></center></td>
                            <td class="pla" style="color: blue; font-size: 12px;"><center><?php echo @number_format($mainvalue,2); ?></center></td>
                            <td class="pla" style="color: blue; font-size: 12px;"><center><?php echo $cardpayrow['gy_trans_cardcent']."%"; ?></center></td>
                            <td class="pla" style="color: blue; font-size: 12px;"><center><?php echo @number_format(0 + $cardpayrow['gy_trans_total'],2); ?></center></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="pla" style=""><center></center></td>
                            <td class="pla" style=" color: blue; font-size: 12px;"><center><?php echo @number_format($cardmaintotalpays,2); ?></center></td>
                            <td class="pla" style=""><center></center></td>
                            <td class="pla" style=" color: blue; font-size: 12px;"><center><?php echo @number_format($cardtotalpays,2); ?></center></td>
                        </tr>
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 98%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="4" style="font-size: 18px;"><center>TRA TRANSACTIONS</center></th>
                        </tr>
                        <tr>
                            <th class="pla" style="font-size: 12px;"><center>Trans. Code</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Account</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Amount</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Time</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $tratotal=0;
                            $gettratrans=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_tra_code`>'0' AND `gy_trans_status`='1' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1' Order By `gy_trans_code` ASC");
                            while ($trarow=$gettratrans->fetch_array()) {
                                //get toiotal without fee
                                @$tratotal += $trarow['gy_trans_total'];
                        ?>
                        <tr>
                            <td class="pla" style="font-size: 12px;"><center><?php echo $trarow['gy_trans_code']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo $trarow['gy_trans_custname']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($trarow['gy_trans_total'],2); ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo date("g:i:s A", strtotime($trarow['gy_trans_date'])); ?></center></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="pla" colspan="2"><center></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($tratotal,2); ?></center></td>
                            <td class="pla" style=""><center></center></td>
                        </tr>
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 98%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th style="font-size: 18px; color: #de9100;" colspan="7"><center><b>CHEQUE SUMMARY</b></center></th>
                        </tr>
                        <tr> 
                            <th class="pla" style="font-size: 12px;"><center>Transaction</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Cheque #</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Cheque Amount</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Sales</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Change</center></th>
                            <th class="pla" style="font-size: 12px;"><center>RF %</center></th>
                            <th class="pla" style="font-size: 12px;"><center>RF Amount</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $total_royal="";
                            $total_cash="";
                            $total_total="";
                            $total_change="";
                            $get_royal_fee_by_cashier=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_pay`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1' Order By `gy_trans_code` ASC");
                            while ($check_info_row=$get_royal_fee_by_cashier->fetch_array()) {
                                
                                //get royal fees
                                $perct = $check_info_row['gy_trans_check_per'] / 100;

                                $royal_fee_value = $check_info_row['gy_trans_royal_fee'];

                                @$total_royal += $check_info_row['gy_trans_royal_fee'];
                                @$total_cash += $check_info_row['gy_trans_cash'];
                                @$total_total += $check_info_row['gy_trans_total'];
                                @$total_change += $check_info_row['gy_trans_change'];

                        ?>

                        <tr>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo $check_info_row['gy_trans_code']; ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo $check_info_row['gy_trans_check_num']; ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo number_format($check_info_row['gy_trans_cash'],2); ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo number_format($check_info_row['gy_trans_total'],2); ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo number_format($check_info_row['gy_trans_change'],2); ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo $check_info_row['gy_trans_check_per']; ?> %</center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo number_format($royal_fee_value,2); ?></center></td>
                        </tr>
                        <?php }  

                            //big vars
                            @$overall_total = $grand_total + $royal_fee_total_display + $totaldepamount;
                            @$deduction_total = $my_total_exp + $total_ref_rep;

                            if ($total_cash_breakdown == 0) {
                                $final_judgement_value = 0;
                                $final_judgement = "NOT COUNTED";
                                $final_judgement_color = "black";
                            }else{

                                $up_var = $total_cash + $total_cash_breakdown + $cardpayments + $totaldepcheq;
                                $down_var = $overall_total - $deduction_total;

                                $diff_var = $up_var - $down_var;

                                if ($diff_var == 0) {
                                    $final_judgement_value = $diff_var;
                                    $final_judgement = "PERFECT";
                                    $final_judgement_color = "blue";
                                }else if ($diff_var < 0) {
                                    $final_judgement_value = $diff_var;
                                    $final_judgement = "SHORT";
                                    $final_judgement_color = "red";
                                }else if ($diff_var >= 1) {
                                    $final_judgement_value = $diff_var;
                                    $final_judgement = "OVER";
                                    $final_judgement_color = "green";
                                }else{
                                    $final_judgement_value = $diff_var;
                                    $final_judgement = "PERFECT";
                                    $final_judgement_color = "blue";
                                }
                            }

                            //add to assets or deductions
                            if ($final_judgement == "OVER") {
                                $my_over = $final_judgement_value;
                                $my_short = 0;
                            }else if ($final_judgement == "SHORT") {
                                $my_over = 0;
                                $my_short = $final_judgement_value * -1;
                            }else{
                                $my_over = 0;
                                $my_short = 0;
                            }

                            // $remaining_total = ($overall_total + $my_over) - ($deduction_total + $my_short);
                        ?>  
                        <tr>
                            <td class="pla" style="color: #de9100; font-size: 12px;" colspan="2"><center>TOTAL</center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo @number_format($total_cash,2); ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo @number_format($total_total,2); ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo @number_format($total_change,2); ?></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center></center></td>
                            <td class="pla" style="color: #de9100; font-size: 12px;"><center><?php echo @number_format($total_royal,2); ?></center></td>
                        </tr>
                    </tbody>
                </table>  

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 98%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="4" style="font-size: 18px; color: red;"><center>EXPENSES SUMMARY</center></th>
                        </tr>
                        <tr>
                            <th class="pla" style="font-size: 12px;"><center>Time</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Note</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Amount</center></th>
                            <th class="pla" style="font-size: 12px;"><center>Admin</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            $total_summ_exp="";
                            $get_exp_summ=$link->query("Select * From `gy_expenses` Where `gy_user_id`='$my_master_id' AND date(`gy_exp_date`)='$date1'");
                            while ($exp_summ_row=$get_exp_summ->fetch_array()) {

                                @$total_summ_exp += $exp_summ_row['gy_exp_amount'];

                                $my_approve_by = words($exp_summ_row['gy_approved_by']);

                                //get approve by info
                                $get_approve_by_info=$link->query("SElect * From `gy_user` Where `gy_user_id`='$my_approve_by'");
                                $apprv_row=$get_approve_by_info->fetch_array();

                                if ($exp_summ_row['gy_exp_type'] == "CASH") {
                                    $my_exp_amounts = $exp_summ_row['gy_exp_amount'];
                                }else{
                                    $my_exp_amounts = "";
                                }

                        ?>
                        <tr>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo date("g:i:s A",strtotime($exp_summ_row['gy_exp_date'])); ?></center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo $exp_summ_row['gy_exp_note']; ?></center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo @number_format(0 + $my_exp_amounts,2); ?></center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo $apprv_row['gy_full_name']; ?></center></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="pla" style=""><center></center></td>
                            <td class="pla" style=" color: red; font-size: 12px;"><center>TOTAL</center></td>
                            <td class="pla" style=" color: red; font-size: 12px;"><center><?php echo @number_format($my_total_exp,2); ?></center></td>
                            <td class="pla" style=""><center></center></td>
                        </tr>
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="2" style="font-size: 18px; color: green;"><center>RETAIL SALES</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                            //get salesman
                            $get_salesman_info=$link->query("Select DISTINCT(`gy_prepared_by`) As `my_salesman` From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1' Order By `gy_trans_code` ASC");
                            $count_salesman=$get_salesman_info->num_rows;
                            while ($salesman_info_row=$get_salesman_info->fetch_array()) {

                                //id values
                                $salesman_identity=words($salesman_info_row['my_salesman']);

                                ///get salesman info
                                $getsalesmanfullname=$link->query("Select * From `gy_user` Where `gy_user_id`='$salesman_identity'");
                                $salesnamerow=$getsalesmanfullname->fetch_array();

                                //get sales reports by salesman
                                $query_one=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' AND `gy_user_id`='$my_master_id' AND date(`gy_trans_date`)='$date1' Order By `gy_trans_code` ASC");

                                $order_no="";
                                $my_final_total="";

                                $count_results=$query_one->num_rows;
                        ?>
                        <?php 
                            //total total sales and salesman
                            $salesman_total="";
                            while ($final_row=$query_one->fetch_array()) {
                                @$salesman_total += $final_row['gy_trans_total'];
                            }
                        ?>
                            <tr>
                                <td class="pla" style="color: green; font-size: 12px;"><center><?php echo $salesnamerow['gy_full_name']; ?></center></td>
                                <td class="pla" style="color: green; font-size: 12px;"><center><?php echo @number_format(0+$salesman_total,2); ?></center></td>
                            </tr>
                        <?php } ?>
                            <tr>
                                <td class="pla" style="color: green; font-size: 12px;"><center>TOTAL RETAIL SALES</center></td>
                                <td class="pla" style="color: green; font-size: 12px;"><center><?php echo @number_format(0+$grand_total,2); ?></center></td>
                            </tr>
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 32%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="2" style="font-size: 18px; color: red;"><center>REMITTANCE</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pla" style="color: red; font-size: 12px;"><center>PARTIAL REMITTANCE</center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo @number_format(0+$partial_remit_total,2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="color: red; font-size: 12px;"><center>CHEQUE DATED REMITTANCE</center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo @number_format(0+$check_remit_total,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="color: red; font-size: 12px;"><center>FULL REMITTANCE</center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo @number_format(0+$full_remit_total,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="color: red; font-size: 12px;"><center>TOTAL REMITTANCE</center></td>
                            <td class="pla" style="color: red; font-size: 12px;"><center><?php echo @number_format(0+$remit_total,2); ?></center></td>
                        </tr> 
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 33%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="2" style="font-size: 18px;"><center>SUMMARY</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: black;"><center>Beginning Balance</center></td>
                            <td class="pla" style="font-size: 12px; color: black;"><center><?php echo number_format(0 + $get_beginning_cash_row['gy_beg_cash'],2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: blue;"><center>No. of Transactions</center></td>
                            <td class="pla" style="font-size: 12px; color: blue;"><center><?php echo 0+$total_trans_num; ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: green;"><center>TOTAL RETAIL SALES</center></td>
                            <td class="pla" style="font-size: 12px; color: green; "><center><?php echo @number_format(0 + $grand_total,2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px;"><center>TOTAL DEPOSIT</center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format(0 + $totaldepamount,2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: green;"><center>ROYAL FEES</center></td>
                            <td class="pla" style="font-size: 12px; color: green;"><center><?php echo @number_format(0 + $royal_fee_total_display,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="font-size: 12px; color: red;"><center>EXPENSES TOTAL (CASH)</center></td>
                            <td class="pla" style="font-size: 12px; color: red;"><center><?php echo @number_format(0 + $my_total_exp,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="font-size: 12px; color: red;"><center>REPLACE/REFUND</center></td>
                            <td class="pla" style="font-size: 12px; color: red;"><center><?php echo @number_format(0 + $total_ref_rep,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px; color: blue;"><center>TOTAL SALES</center></td>
                            <td class="pla" style="font-size: 12px; color: blue;"><center><?php echo @number_format(0+ ($grand_total + $royal_fee_total_display + $totaldepamount) - ($my_total_exp + $total_ref_rep),2); ?></center></td>
                        </tr>
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="3" style="font-size: 18px; color: #000;"><center>CASH BREAKDOWN</center></th>
                        </tr>
                    </thead>
                    <tbody> 
                        <tr>
                            <td class="pla" colspan="2" style="font-size: 12px;"><center>Cheque/s Total Amount</center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format(0+$total_cash + $totaldepcheq,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" colspan="2" style="font-size: 12px;"><center>Card Payments</center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format(0+$cardpayments,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" colspan="2" style="font-size: 12px;"><center>Partial Remittance</center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format(0+$partial_remit_total,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="font-size: 12px;">1000 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_a']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_a,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="font-size: 12px;">500 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_b']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_b,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px;">200 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_c']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_c,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px;">100 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_d']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_d,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px;">50 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_e']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_e,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px;">20 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_f']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_f,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px;">10 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_g']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_g,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px;">5 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_h']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_h,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px;">1 X </td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo 0+$break_row['gy_break_a_i']; ?></center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format($a_i,2); ?></center></td>
                        </tr>   
                        <tr>
                            <td class="pla" style="font-size: 10px;" colspan="2"><center>Total CASH/CARD/CHEQUE COUNT</center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format(0+$total_cash + $total_cash_breakdown + $cardpayments + $totaldepcheq,2); ?></center></td>
                        </tr> 
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="2" style="font-size: 18px;"><center>RESULT</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: blue;"><center>No. of Transactions</center></td>
                            <td class="pla" style="font-size: 12px; color: blue;"><center><?php echo 0+$total_trans_num; ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 10px;"><center>TOTAL CASH/CHEQUE COUNT</center></td>
                            <td class="pla" style="font-size: 12px;"><center><?php echo @number_format(0+$total_cash + $total_cash_breakdown + $cardpayments + $totaldepcheq,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="font-size: 10px; color: blue;"><center>GRAND TOTAL</center></td>
                            <td class="pla" style="font-size: 12px; color: blue;"><center><?php echo @number_format(0+ ($overall_total) - $deduction_total,2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: <?php echo $final_judgement_color; ?>;"><center>RESULT</center></td>
                            <td class="pla" style="font-size: 17px; color: <?php echo $final_judgement_color; ?>;"><center><?php echo @number_format(0 + $diff_var ,2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: <?php echo $final_judgement_color; ?>;"><center>STATUS</center></td>
                            <td class="pla" style="font-size: 17px; line-height: 17px; color: <?php echo $final_judgement_color; ?>;"><center><?php echo $final_judgement; ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 18px; padding: 7px;" colspan="2"><center><?php echo date("M d, Y", strtotime($date1)); ?></center></td>
                        </tr>  
                    </tbody>
                </table>

                <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 30%; float: left; margin-left: 10px; margin-top: 20px;">
                    <thead class="">
                        <tr class="nmgs">
                            <th colspan="2" style="font-size: 18px;"><center>INCOME SUMMARY</center></th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php  
                        //get product code and quantity
                        $my_total_cap=0;
                        $get_trans_details=$link->query("Select `gy_trans_details`.`gy_product_code`,`gy_trans_details`.`gy_trans_quantity`,`gy_products`.`gy_product_price_cap` From `gy_trans_details` LEFT JOIN `gy_transaction` On `gy_trans_details`.`gy_trans_code`=`gy_transaction`.`gy_trans_code` LEFT JOIN `gy_products` On `gy_products`.`gy_product_code`=`gy_trans_details`.`gy_product_code` Where `gy_transaction`.`gy_user_id`='$my_master_id' AND date(`gy_transaction`.`gy_trans_date`)='$date1'");
                        while ($deta_row=$get_trans_details->fetch_array()) {
                            //get product codes
                            $my_codes = words($deta_row['gy_product_code']);
                            $my_quantity = words($deta_row['gy_trans_quantity']);

                            //my capitals
                            @$my_total_cap += $deta_row['gy_product_price_cap'] * $my_quantity;
                        }
                    ?>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: #000;"><center>TOTAL RETAIL SALES</center></td>
                            <td class="pla" style="font-size: 12px; color: #000; "><center><?php echo @number_format(0 + $grand_total,2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: #000;"><center>CAPITAL TOTAL</center></td>
                            <td class="pla" style="font-size: 12px; color: #000;"><center><?php echo @number_format(0 + $my_total_cap,2); ?></center></td>
                        </tr>
                        <tr>
                            <td class="pla" style="font-size: 12px; color: green;"><center>INCOME</center></td>
                            <td class="pla" style="font-size: 12px; color: green;"><center><?php echo @number_format(0 + $grand_total - $my_total_cap,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="font-size: 12px; color: red;"><center>EXPENSES TOTAL (CASH)</center></td>
                            <td class="pla" style="font-size: 12px; color: red;"><center><?php echo @number_format(0 + $my_total_exp,2); ?></center></td>
                        </tr> 
                        <tr>
                            <td class="pla" style="font-size: 12px; color: red;"><center>REPLACE/REFUND</center></td>
                            <td class="pla" style="font-size: 12px; color: red;"><center><?php echo @number_format(0 + $total_ref_rep,2); ?></center></td>
                        </tr>  
                        <tr>
                            <td class="pla" style="font-size: 12px; color: blue;"><center>FINAL INCOME</center></td>
                            <td class="pla" style="font-size: 12px; color: blue;"><center><?php echo @number_format(0 + ($grand_total - $my_total_cap) - ($my_total_exp + $total_ref_rep),2); ?></center></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>

            

            <div class="page-break">
                <!-- <p style="font-size: 17px; font-weight: italic; margin-left: 100px;"><center></center></p> -->
            </div>
            <?php 
                    }
                } 

            ?>  
    </body>
</html>