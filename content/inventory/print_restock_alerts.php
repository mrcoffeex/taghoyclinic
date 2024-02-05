<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    $my_header_tite = "RE-STOCK ALERTS";

    $my_dir_value = @$_GET['cd'];
    $my_dir_mode = @$_GET['mode'];

    if ($my_dir_mode == "common_search") {
        $my_query = "Select * From `gy_products` Where CONCAT(`gy_product_code`,`gy_product_name`) Like '%$my_dir_value%' AND `gy_product_quantity`<=`gy_product_restock_limit` Order By `gy_product_name` ASC";
    }else if ($my_dir_mode == "cat_search") {
        $my_query = "Select * From `gy_products` Where `gy_product_cat`='$my_dir_value' AND `gy_product_quantity`<=`gy_product_restock_limit` Order By `gy_product_name` ASC";
    }else if ($my_dir_mode == "" && $my_dir_value == "") {
        $my_query = "Select * From `gy_products` Where `gy_product_quantity`<=`gy_product_restock_limit` Order By `gy_product_name` ASC";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $my_header_tite; ?></title>           
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
                    <center><span style="font-size: 30px;"><?php echo $my_header_tite; ?><br></span></center>

        <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example">
            <thead class="nmgs">        
                <tr>
                    <th style="font-size: 18px;">No.</th> 
                    <th style="font-size: 18px;">Category</th>                    
                    <th style="font-size: 18px;">Code</th>
                    <th style="font-size: 18px;">Description</th>
                    <th style="font-size: 18px;">Quantity</th>
                    <th style="font-size: 18px;">Supplier</th> 
                    <th style="font-size: 18px;">Branch</th>            
                </tr>
            </thead>
        <tbody>
            <?php
                //vars
                $numrow = 0;
                // Select ordered items
                $sql_item_detail=$link->query($my_query);

                while ($row_item_detail=$sql_item_detail->fetch_array()) {
                    $numrow++; 

                    //get supplier
                    $my_scode=words($row_item_detail['gy_supplier_code']);
                    $get_supplier=$link->query("Select * From `gy_supplier` Where `gy_supplier_code`='$my_scode'");
                    $supplier_row=$get_supplier->fetch_array();

                    if ($row_item_detail['gy_supplier_code'] == 0) {
                        $my_supplier_data = "NO DATA";
                        $my_supplier_color = "red";
                    }else{
                        $my_supplier_data = $supplier_row['gy_supplier_name'];
                        $my_supplier_color = "green";
                    }                       
            ?>                  
            <tr>
                <td class="pla" style="font-size: 13px;"><?php echo $numrow; ?></td>
                <td class="pla" style="font-size: 13px;"><?php echo $row_item_detail['gy_product_cat']; ?></td>
                <td class="pla" style="font-size: 13px;"><?php echo $row_item_detail['gy_product_code']; ?></td>
                <td class="pla" style="text-transform: uppercase; font-size: 13px;"><?php echo $row_item_detail['gy_product_name']; ?></td>
                <td class="pla" style="font-size: 13px; color: blue;"><?php echo $row_item_detail['gy_product_quantity']." <span style='color: black;'>".$row_item_detail['gy_product_unit']."</span>"; ?></td>
                <td class="pla" style="font-size: 13px; color: <?php echo $my_supplier_color; ?>;"><?php echo $my_supplier_data; ?></td>
                <td class="pla" style="font-size: 13px; color: <?php echo $my_supplier_color; ?>;"><?php echo get_branch_name($row_item_detail['gy_branch_id']); ?></td>
            </tr>
                        
        <?php } ?>                                  
        </tbody>
    </table>    
    </div>
</div>  
    </body>
    
</html>