<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['cd'];

    $get_form_info=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
    $info_row=$get_form_info->fetch_array();

    //user prepared
    $p_by = $info_row['gy_prepared_by'];

    //prepared by
    $get_prepared_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$p_by'");
    $prepared_info=$get_prepared_info->fetch_array();
?>

<link href="print/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="print/styles.css" rel="stylesheet" id="bootstrap-css">
<script src="print/bootstrap.min.js"></script>
<script src="print/jquery.min.js"></script>
<script src="print/my_scripts.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!--Author      : @arboshiki-->
<style type="text/css">
    @media print{
        .tago{
            display: none !important;
        }
    }
</style>

<body onload="window.print()">

<div id="invoice">

    <div class="toolbar hidden-print">
        <div class="text-right">
            <button type="button" onclick="window.print()" class="btn btn-info tago"><i class="fa fa-print"></i> Print</button>
            <a href="sales_counter"><button type="button" class="btn btn-info tago"><i class="fa fa-print"></i> Sales Counter</button></a>
            <a href="quotations"><button type="button" class="btn btn-info tago"><i class="fa fa-print"></i> Quotations</button></a>
        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <img src="print/logo_web.png" data-holder-rendered="true" width="300px" height="200px" /> 
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            ASCU HARDWARE
                        </h2>
                        <div>Somewhere, Midsayap</div>
                        <div>(123) 456-789</div>
                        <div>company@example.com</div>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">Customer Name:</div>
                        <h2 class="to"><?php echo $info_row['gy_trans_custname']; ?></h2>
                    </div>
                    <div class="col invoice-details">
                        <h3 class="invoice-id">Transaction ID: <?php echo $info_row['gy_trans_code']; ?></h3>
                        <div class="date">Date Issued: <?php echo date("F d, Y", strtotime($info_row['gy_trans_date'])); ?></div>
                        <div class="date">Prepared By: <?php echo $prepared_info['gy_full_name']; ?></div>
                    </div>
                </div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="total"><center>Bar Code</center></th>
                            <th class="text-left">Description</th>
                            <th class="text-right">Price (SRP)</th>
                            <th class="text-right">Discount</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Sub-Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php  
                        //get transaction details
                        //free vars
                        $total = "";
                        $srp_total = "";
                        $get_details=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$my_dir_value' Order By `gy_trans_details`.`gy_product_price` DESC");
                        $count_items=$get_details->num_rows;
                        while ($item_row=$get_details->fetch_array()) {
                            if ($item_row['gy_product_discount'] == 0) {
                                $my_final_price = $item_row['gy_product_price'];
                            }else{
                                $my_final_price = $item_row['gy_product_price'] - $item_row['gy_product_discount'];
                            }

                            $my_subtotal = $my_final_price * $item_row['gy_trans_quantity'];

                            $total += $my_subtotal;

                            $srp_total += $item_row['gy_product_price'] * $item_row['gy_trans_quantity'];
                    ?>
                        <tr>
                            <td class="no"><center><?php echo $item_row['gy_product_code']; ?></center></td>
                            <td class="text-left"><?php echo $item_row['gy_product_name']; ?> <br>(<?php echo $item_row['gy_product_desc']; ?>)</td>
                            <td class="unit"><?php echo number_format($item_row['gy_product_price'],2); ?></td>
                            <td class="qty"><?php echo number_format($item_row['gy_product_discount'],2); ?> %</td>
                            <td class="qty"><?php echo number_format($my_final_price,2); ?></td>
                            <td class="qty"><?php echo $item_row['gy_trans_quantity']."</b> ".$item_row['gy_product_unit']; ?></td>
                            <td class="total"><?php echo number_format($my_subtotal,2); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="3">Total Discount (Php)</td>
                            <td><?php echo number_format($srp_total - $total,2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td colspan="3">TOTAL</td>
                            <td><?php echo number_format($total,2); ?></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="thanks">ASCU Hardware, You Construction Solution.</div>
                <div class="notices">
                    <div>NOTICE:</div>
                    <div class="notice">This <b>Quotation form</b> will expire in <b>1 week</b> upon claim.</div>
                </div>
            </main>
            <footer>
                Invoice was created on a computer and is valid without the signature and seal.
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>

</body>