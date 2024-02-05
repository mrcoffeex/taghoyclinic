<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Transaction Summary";

    $my_dir_value = @$_GET['cd'];  

    //get trans details
    $get_trans_details=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
    $trans_row=$get_trans_details->fetch_array();
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-desktop"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <p style="font-size: 20px;">Transaction ID: <b><?php echo $trans_row['gy_trans_code']; ?></b></p>
                        </div>

                        <div class="panel-body">
                              <p style="font-size: 20px;">
                                Customer: &nbsp;&nbsp;&nbsp;<u><?php echo $trans_row['gy_trans_custname']; ?></u><br><br/>

                                Items: &nbsp;&nbsp;&nbsp;<br/>

                                <?php  
                                    //get items
                                    //trans code
                                    $total = "";

                                    $my_tcode=$trans_row['gy_trans_code'];
                                    $get_items=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` On `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$my_tcode' Order By `gy_products`.`gy_product_price_srp` DESC");
                                    while ($item_row=$get_items->fetch_array()) {

                                        $my_final_price = $item_row['gy_product_price'] - $item_row['gy_product_discount'];

                                        $my_subtotal = $my_final_price * $item_row['gy_trans_quantity'];

                                        $total += $my_subtotal;

                                        echo "
                                            {$item_row['gy_product_code']} &nbsp;&nbsp;&nbsp; ".substr($item_row['gy_product_name'], 0, 50)." - ".number_format($item_row['gy_product_price'],2)."(<i>-".$item_row['gy_product_discount']."</i>) x ".$item_row['gy_trans_quantity']." &nbsp;&nbsp;&nbsp; <span style='float: right;'><b>".number_format($my_subtotal,2)."</span></b><br/>
                                        ";
                                    }
                                ?>
                                <br/>
                                <span style='float: right; color: green;'>Total: &nbsp;&nbsp;&nbsp;<u>Php <b><?php echo number_format($total,2); ?></b></u></span><br/>
                            </p>  
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row">
                         <div class="col-md-12">
                            <div class="form-group">
                                <a href="sales_counter"><button type="button" class="btn btn-primary btn-lg" style="border-radius: 0px;" title="click to go to sales counter ..."><i class="fa fa-desktop fa-fw" accesskey="1"></i> New Transaction (alt + 1)</button></a>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#suggest").keyup(function(){
                $.get("live_search", {product_search: $(this).val()}, function(data){
                    $("datalist").empty();
                    $("datalist").html(data);
                });
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#my_account_code").keyup(function(){
                $.get("live_search_account_code", {my_account_code: $(this).val()}, function(data){
                    $("#my_cust_name").empty();
                    $("#my_cust_name").val(data);
                });
            });
        });
    </script>

    <script type="text/javascript">
        function get_the_change(){
            var cash = document.getElementById('my_cash').value;
            var total = <?php echo $total; ?>;

            var change = parseFloat(cash) - parseFloat(total);

            if (!isNaN(change)) {
                document.getElementById('my_change').value=change;
            }
        }
    </script>

</body>

</html>
