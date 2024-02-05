<?php 
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $date_now = date("Y-m-d");

    //get expensesy
    $get_exp=$link->query("Select SUM(`gy_exp_amount`) As `total_exp` From `gy_expenses` Where date(`gy_exp_date`)='$date_now'");
    $exp_row=$get_exp->fetch_array();

    $my_total_exp=$exp_row['total_exp'];

    //get stock transfer
    $my_total_transfer="";
    $get_transfer=$link->query("Select * From `gy_stock_transfer` Where date(`gy_transfer_date`)='$date_now' AND `gy_transfer_status`='1'");
    while($transfer_row=$get_transfer->fetch_array()){
        @$my_total_transfer += $transfer_row['gy_product_price_cap'] * $transfer_row['gy_transfer_quantity'];
    }
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
        }
        </style>
    </head>
    
    <body onload="window.print()">
        <!-- <div style="position: absolute;">
            <img src="print/r_background.png" alt="Notebook" style="width:100%; height: 100%; margin-left: 100px; margin-top: -50px; opacity: 0.1;">
        </div> -->
        <div class="block-content collapse in" style="margin-top: -20px;">
            <div class="span12">
                <div>
                    <p style="font-size: 20px;">
                        <center><span style="font-size: 30px;">Sales Report - <span style="color: blue;"><?php echo date("F d, Y", strtotime($date_now)); ?></span><br></span></center>
                    </p>
                </div>
                    <?php  
                        //get salesman
                        $get_salesman_info=$link->query("Select DISTINCT(`gy_transaction`.`gy_prepared_by`) As `my_salesman`,`gy_user`.`gy_full_name` From `gy_user` LEFT JOIN `gy_transaction` On `gy_user`.`gy_user_id`=`gy_transaction`.`gy_prepared_by` Where `gy_user`.`gy_user_type`='1' OR `gy_user`.`gy_user_type`='2' AND `gy_transaction`.`gy_user_id` != '0' AND date(`gy_transaction`.`gy_trans_date`)='$date_now' Order By `gy_user`.`gy_user_id` ASC");
                        $count_salesman=$get_salesman_info->num_rows;
                        while ($salesman_info_row=$get_salesman_info->fetch_array()) {

                            //id values
                            $salesman_identity=words($salesman_info_row['my_salesman']);

                            //get sales reports by salesman
                            $query_one=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' Order By `gy_trans_code` ASC");

                            $order_no="";
                            $my_final_total="";

                            $count_results=$query_one->num_rows;
                    ?>
                    <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                        <thead class="nmgs"> 
                            <tr>                    
                                <th style="font-size: 18px;" colspan="3"><center><b><?php echo $salesman_info_row['gy_full_name']; ?></b></center></th>          
                            </tr>       
                            <tr>                    
                                <th style="font-size: 18px;">No.</th>
                                <th style="font-size: 18px;">TransCode</th>
                                <th style="font-size: 18px;">Total</th>           
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                //get products
                                //make pagination
                                while ($data_row=$query_one->fetch_array()) {

                                    @$order_no += 1;
                                    @$my_final_total += $data_row['gy_trans_total'];
                            ?>                  
                                <tr>
                                    <td class="pla"><?php echo $order_no; ?></td>
                                    <td class="pla"><?php echo $data_row['gy_trans_code']; ?></td>
                                    <td class="pla"><?php echo number_format($data_row['gy_trans_total'],2); ?></td>
                                </tr>
                                            
                            <?php } ?>    
                                <tr>
                                    <td class="pla" style="color: green;"><?php echo $order_no; ?></td>
                                    <td class="pla" style="color: green;"><b>Total</b></td>
                                    <td class="pla" style="color: green;"><b><?php echo @number_format(0+$my_final_total,2); ?></b></td>
                                </tr>                             
                        </tbody>
                    </table>
                    <br>
                    <?php 
                        //total sales
                        $grand_total="";
                        $get_total_sales=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_status`='1'");
                        while ($total_sales_row=$get_total_sales->fetch_array()) {
                            @$grand_total += $total_sales_row['gy_trans_total'];
                        }
                    ?>

                    <?php } ?>   

                    <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 50%; float: left;">
                        <thead class="nmgs">
                            <th style="font-size: 18px;" colspan="2"><center><b>Sale Total Summary</b></center></th>
                        </thead>
                        <tbody>
                        <?php  
                            //get salesman
                            $get_salesman_info=$link->query("Select DISTINCT(`gy_transaction`.`gy_prepared_by`) As `my_salesman`,`gy_user`.`gy_full_name` From `gy_user` LEFT JOIN `gy_transaction` On `gy_user`.`gy_user_id`=`gy_transaction`.`gy_prepared_by` Where `gy_user`.`gy_user_type`='1' OR `gy_user`.`gy_user_type`='2' AND `gy_transaction`.`gy_user_id` != '0' AND date(`gy_transaction`.`gy_trans_date`)='$date_now' Order By `gy_user`.`gy_user_id` ASC");
                            $count_salesman=$get_salesman_info->num_rows;
                            while ($salesman_info_row=$get_salesman_info->fetch_array()) {

                                //id values
                                $salesman_identity=words($salesman_info_row['my_salesman']);

                                //get sales reports by salesman
                                $query_one=$link->query("Select * From `gy_transaction` Where `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_prepared_by`='$salesman_identity' AND date(`gy_trans_date`)='$date_now' Order By `gy_trans_code` ASC");

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
                                    <td class="pla" style="color: blue;"><center><?php echo $salesman_info_row['gy_full_name']; ?></center></td>
                                    <td class="pla" style="color: blue;"><center><?php echo @number_format(0+$salesman_total,2); ?></center></td>
                                </tr>
                        <?php } ?>  
                            <tr>
                                <td class="pla" style="color: blue;"><center>Sales Total</center></td>
                                <td class="pla" style="color: blue;"><center><?php echo @number_format(0+$grand_total,2); ?></center></td>
                            </tr>  
                            <tr>
                                <td class="pla" style="color: red;"><center>Expenses Total</center></td>
                                <td class="pla" style="color: red;"><center><?php echo @number_format(0+$my_total_exp,2); ?></center></td>
                            </tr>  
                            <tr>
                                <td class="pla" style="color: green;"><center>Grand Total</center></td>
                                <td class="pla" style="color: green;"><center><?php echo @number_format(0+$grand_total - $my_total_exp,2); ?></center></td>
                            </tr>
                        </tbody>
                    </table> 

                    <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example" style="width: 50%; float: left; margin-bottom: 30px;">
                        <thead class="nmgs">
                            <th style="font-size: 18px;" colspan="2"><center><b>Stock-Transfer Summary</b></center></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="pla" style=" color: black;"><center>Stock Transfer Total Amount</center></td>
                                <td class="pla" style=" color: black;"><center><?php echo @number_format(0+$my_total_transfer,2); ?></center></td>
                            </tr> 
                        </tbody>
                    </table>
      
                <div>
                    <center>
                    <p style="font-size: 15px; text-transform: uppercase;">
                        <b>NOTICE</b>: <span style="color: red; font-weight: bold;">CONFIDENTIAL</span>
                    </p></center>
                </div>

            </div>
        </div>  
    </body>
    
</html>