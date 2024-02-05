<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    if(isset($_POST['print_selected'])){
        $checkbox = $_POST['check'];

        if (!$checkbox) {
            echo "
                <script>
                    window.close();
                </script>
            ";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>PRINT MASTERLIST</title>           
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
        <div class="block-content collapse in" style="margin-top: 0px;">
            <div class="span12">
                <div>
                    <p style="font-size: 17px;">
                        <center>
                            <span style="font-size: 30px;">PRODUCT MASTERLIST<br></span>
                            <?php echo $my_project_name; ?>
                        </center>
                    </p>

        <table class="qwe" cellpadding="0" cellspacing="0" border="0" class="table" id="example">
            <thead class="nmgs">        
                <tr>
                    <th style="font-size: 18px;">No.</th>                  
                    <th style="font-size: 18px;">Cat.</th>                   
                    <th style="font-size: 18px;">Code</th>
                    <th style="font-size: 18px;">Description</th>
                    <th style="font-size: 18px;">Coding</th>
                    <th style="font-size: 18px;">SRP</th>
                    <th style="font-size: 18px;">LIMIT</th>
                    <th style="font-size: 18px;">Quantity</th>             
                </tr>
            </thead>
        <tbody>
            <?php
                //vars
                
                // Select ordered items
                for($i=0;$i<count($checkbox);$i++){
                    
                    $del_id = $checkbox[$i]; 

                    $my_query=$link->query("Select * From `gy_products` Where `gy_product_id`='$del_id'"); 
                
                $numrow = 0;
                while ($row_item_detail=$my_query->fetch_array()) {
                    $numrow++;                        
            ?>                  
            <tr>
                <td class="pla" style="font-size: 13px;"><?php echo $numrow; ?></td>
                <td class="pla" style="font-size: 13px;"><?php echo $row_item_detail['gy_product_cat']; ?></td>
                <td class="pla" style="font-size: 13px;"><?php echo $row_item_detail['gy_product_code']; ?></td>
                <td class="pla" style="text-transform: uppercase; font-size: 13px;"><?php echo $row_item_detail['gy_product_name']; ?></td>
                <td class="pla" style="font-size: 13px; color: blue;"><?php echo toAlpha($row_item_detail['gy_product_price_cap']); ?></td>
                <td class="pla" style="font-size: 13px; color: green;"><?php echo number_format($row_item_detail['gy_product_price_srp'],2); ?></td>
                <td class="pla" style="font-size: 13px; color: red;"><?php echo number_format($row_item_detail['gy_product_discount_per'],2); ?></td>
                <td class="pla" style="font-size: 13px; color: blue;"><?php echo $row_item_detail['gy_product_quantity']." <span style='color: black;'>".$row_item_detail['gy_product_unit']."</span>"; ?></td>
            </tr>
                        
        <?php }} ?>                                  
        </tbody>
    </table>    
    </div>
</div>  
    </body>
    
</html>