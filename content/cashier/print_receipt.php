<?php 
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    //  require __DIR__ . '\printer\autoload.php';
    // use Mike42\Escpos\Printer;
    // use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

    // $connector = new WindowsPrintConnector("XP-58");
    // $printer = new Printer($connector);
    // $printer -> pulse();
    // $printer -> close();

    $my_dir_value = @$_GET['cd'];

    $get_trans_details=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
    $trans_details=$get_trans_details->fetch_array();

    //vars
    $transaction = $trans_details['gy_trans_code'];
    $customer = $trans_details['gy_trans_custname'];
    $cashier = $user_info;

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Print Receipt</title>           
        <!-- Bootstrap -->
            <!-- <link href="images/logo.png" rel="icon" type="image"> -->
        <link href="print/logo_web.png" rel="icon" type="image">
        <link href="custom/mine.css" rel="stylesheet" >
        <meta http-equiv=Content-Type content="text/html; charset=windows-1252">
        <meta name=Generator content="Microsoft Word 14 (filtered)">
        <style>
        <!--
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
        -->
        </style>
    </head>
    
    <body onload="window.print()">
        <div style="position: absolute;">
            <img src="print/r_background.png" alt="Notebook" style="width:100%; height: 100%; margin-left: 100px; margin-top: -50px; opacity: 0.1;">
        </div>
        <div class="block-content collapse in" style="margin-top: -20px;">
            <div class="span12">
                <div>
                    <p style="font-size: 20px;">
                    <center><span style="font-size: 30px;">RECEIPT<br></span></center>
                    <!-- <br>    -->
                    <?php echo $transaction; ?><br>
                    <span style="text-transform: uppercase;">Customer: <b><?php echo $customer; ?></b></span><br>
                    <?php echo date("F d, Y g:i:s A", strtotime($trans_details['gy_trans_date'])); ?>
                </p>
                </div>

        <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example">
            <thead class="nmgs">        
                <tr>                    
                    <th style="font-size: 18px;">Qty</th>
                    <th style="font-size: 18px;">Unit</th>
                    <th style="font-size: 18px;">Description</th>
                    <th style="font-size: 18px;">Unit Price</th>
                    <th style="font-size: 18px;">Total</th>             
                </tr>
            </thead>
        <tbody>
        <!-----------------------------------Content------------------------------------>
            <?php
                //vars
                $total = '';
                // Select ordered items
                $sql_item_detail=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` ON `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$transaction' Order By `gy_trans_details`.`gy_product_price` DESC");

                while ($row_item_detail=$sql_item_detail->fetch_array()) {

                    $item_name = $row_item_detail['gy_product_name'];
                    $quantity = $row_item_detail['gy_trans_quantity'];

                    $price = $row_item_detail['gy_product_price_srp'] - $row_item_detail['gy_product_discount'];
                    $sub_total = $price * $row_item_detail['gy_trans_quantity'];
                    @$total += $sub_total;
                                            
            ?>                  
            <tr>
                <td class="pla"><?php echo $row_item_detail['gy_trans_quantity']; ?></td>
                <td class="pla"><?php echo $row_item_detail['gy_product_unit']; ?></td>
                <td class="pla" style="text-transform: uppercase; font-size: 18px;"><?php echo $row_item_detail['gy_product_name']; ?></td>
                <td class="pla"><?php echo number_format($price,2); ?></td>
                <td class="pla"><?php echo number_format($sub_total,2); ?></td>
            </tr>
                        
        <?php } ?>   
            <tr>
                <td class="pla" colspan=3></td>
                <td class="pla" style="font-size: 13px;"><strong>TOTAL</strong></td>
                <td class="pla" style="font-size: 13px;">Php <strong><?php echo number_format("$total",2);  ?></strong></td>
            </tr>
            <tr>
                <td class="pla" colspan=3><strong></strong></td>
                <td class="pla" style="font-size: 13px;"><strong>CASH</strong></td>
                <td class="pla" style="font-size: 13px;">Php <strong><?php echo number_format($trans_details['gy_trans_cash'],2);  ?></strong></td>
            </tr>
            <tr>
                <td class="pla" colspan=3><strong></strong></td>
                <td class="pla" style="font-size: 18px;"><strong>CHANGE</strong></td>
                <td class="pla" style="font-size: 18px;">Php <strong><?php echo number_format($trans_details['gy_trans_change'],2);  ?></strong></td>
            </tr>                                   
        </tbody>
    </table>    
      
        <div>
            <center>
            <p style="font-size: 15px; text-transform: uppercase;">
                <b>NOTICE</b>: This is just a temporary receipt 
                Not valid for claiming input tax 
                this serves as your proff of payment.
            </p></center>
        </div>

    <br>
    <hr>
    <br>
    <p style="font-size: 20px; margin-top: -15px;">Transaction Code: <b><?php echo $my_dir_value; ?></b></p>
    <?php
        //vars
        $total = '';
        // Select ordered items
        $get_bodega_info=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` ON `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$transaction' AND `gy_products`.`gy_product_cat`='bodega' Order By `gy_trans_details`.`gy_product_price` DESC");

        while ($bodega_row=$get_bodega_info->fetch_array()) {                         
    ?>
    <p style="font-size: 18px; margin-top: -15px;">
        <?php echo $bodega_row['gy_product_code']; ?> - <span style="text-transform: uppercase;"><?php echo $bodega_row['gy_product_name']; ?></span> - <b><?php echo $bodega_row['gy_trans_quantity']." ".$bodega_row['gy_product_unit']; ?></b> <br>
    </p>
    <?php } ?>

</div>
</div>  
    </body>
    
</html>