<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = @$_GET['search_text'];

    $my_project_header_title = "Cash Breakdown <span style='color: red;'>".date("F d, Y", strtotime($my_dir_value))."</span>";

    $my_notification = @$_GET['note'];

    if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "nice_update_all") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Over-All Cash Breakdown is Updated";
    }else if ($my_notification == "nice_update_rem") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Remaining Cash Breakdown is Updated";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $date_now = words($my_dir_value);

   //get get cash type expenses
    $expenses_total=0;
    $get_exp_cash=$link->query("Select * From `gy_expenses` Where date(`gy_exp_date`)='$date_now' AND `gy_user_id`='$user_id' AND `gy_exp_type`='CASH'");
    while ($exp_cash_row=$get_exp_cash->fetch_array()) {
        @$expenses_total += $exp_cash_row['gy_exp_amount'];
    }

    //get card payments
    $cardpayments=0;
    $getcard=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_pay`='2' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' Order By `gy_trans_code` ASC");
    while ($cardrow=$getcard->fetch_array()) {
        @$cardpayments += $cardrow['gy_trans_total'];
    }

    @$totaldepcash=0;
    $getdepdatacash=$link->query("Select * From `gy_deposit` Where date(`gy_dep_date`)='$date_now' AND `gy_user_id`='$user_id' AND `gy_dep_method`='0'");
    while ($cashdeprow=$getdepdatacash->fetch_array()) {
        @$totaldepcash += $cashdeprow['gy_dep_amount'];
    }

    @$totaldepcheq=0;
    $getdepdatacheq=$link->query("Select * From `gy_deposit` Where date(`gy_dep_date`)='$date_now' AND `gy_user_id`='$user_id' AND `gy_dep_method`='1'");
    while ($cheqdeprow=$getdepdatacheq->fetch_array()) {
        @$totaldepcheq += $cheqdeprow['gy_dep_amount'];
    }

    @$totaldeps = $totaldepcash + $totaldepcheq;

    //cash breakdowns data
    $my_query=$link->query("Select * From `gy_breakdown` Where date(`gy_break_date`)='$date_now' AND `gy_user_id`='$user_id'");
    $break_row=$my_query->fetch_array();

    //get latest beginning balance for this cashier
    $get_beg=$link->query("SElect * From `gy_begin_cash` Where `gy_beg_by`='$user_id' Order By `gy_beg_id` DESC LIMIT 1");
    $get_beginning_cash_row=$get_beg->fetch_array();

    //total sales data
    $my_final_total=0;
    $get_total_sales_by_cashier=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' Order By `gy_trans_code` ASC");

    while ($sales_row=$get_total_sales_by_cashier->fetch_array()) {
        @$my_final_total += $sales_row['gy_trans_total'];
    }

    //total sales data
    $my_check_change_total=0;
    $my_total_check_amount=0;
    $royal_fee_total_display=0;
    $get_total_royal_fee_by_cashier=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_pay`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' Order By `gy_trans_code` ASC");
    while ($royal_row=$get_total_royal_fee_by_cashier->fetch_array()) {

        //get royal fee
        $get_perct = $royal_row['gy_trans_check_per'] / 100;

        $royal_fee_total = $royal_row['gy_trans_royal_fee'];
        @$royal_fee_total_display += $royal_row['gy_trans_royal_fee'];

        $my_math_a = 100 - $royal_row['gy_trans_check_per'];
        $my_math_b = $royal_row['gy_trans_royal_fee'] / $royal_row['gy_trans_check_per'];

        @$my_check_change_total += $my_math_a * $my_math_b;
        
        @$my_total_check_amount += $royal_row['gy_trans_cash'];
    }

    //get remittance
    $my_total_partial=0;
    $get_remit=$link->query("Select * From `gy_remittance` Where date(`gy_remit_date`)='$date_now' AND `gy_remit_type`='0' AND `gy_user_id`='$user_id'");
    while ($remit_row=$get_remit->fetch_array()) {
       @$my_total_partial += $remit_row['gy_remit_value'];
    }

    $my_check_total=0;
    $checks=$link->query("Select * From `gy_transaction` Where date(`gy_trans_date`)='$date_now' AND `gy_trans_type`='1' AND `gy_trans_pay`='1' AND `gy_trans_status`='1' AND `gy_user_id`='$user_id' Order By `gy_trans_code` ASC");
    while ($check_total_row=$checks->fetch_array()) {
        @$my_check_total += $check_total_row['gy_trans_cash'];
    }

    $total_ref_rep=0;
    $get_ref_summ=$link->query("Select * From `gy_refund` Where date(`gy_refund_date`)='$date_now' AND `gy_user_id`='$user_id'");
    while ($ref_summ_row=$get_ref_summ->fetch_array()) {

        @$total_ref_rep += $ref_summ_row['gy_product_price'] * $ref_summ_row['gy_product_quantity'];
    }

    $beg_cash = 0 + $get_beginning_cash_row['gy_beg_cash'];

    @$assets = @$my_final_total + $royal_fee_total_display + $totaldeps;
    @$deductions = @$expenses_total + $total_ref_rep;

    $over_all_data = $assets - $deductions;
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body onload="breakdown_calc()">

    <div id="wrapper">

        <?php include 'nav.php'; ?>

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-8">
                    <h3 class="page-header"><i class="fa fa-money"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
                <div class="col-lg-4">
                    <!-- notification here -->
                    <div class="alert alert-<?php echo @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?php echo @$the_note_status; ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo @$message; ?>.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="color: blue;">Over-All Cash Breakdown</span>
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Over-All Cash Breakdown -->
                            <form method="post" enctype="multipart/form-data" action="update_breakdown_over_date?cd=<?php echo $date_now; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Over-All Amount</span>
                                            <input type="number" name="over_all" id="over_all" class="form-control" min="0" step="0.01" value="<?php echo 0+$over_all_data; ?>" readonly required>
                                        </div>
                                    </div><div class="col-md-6">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">READING</span>
                                            <input type="number" name="over_all_reading" id="over_all_reading" class="form-control" min="0" step="0.01" value="0" style="color: blue;" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Total Cheque Transactions</span>
                                    <input type="number" class="form-control" min="0" step="0.01" name="check_amount" id="check_amount" value="<?php echo 0 + $my_total_check_amount + $totaldepcheq; ?>" readonly required>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Card Payments</span>
                                    <input type="number" class="form-control" min="0" step="0.01" name="card_amount" id="card_amount" value="<?php echo 0 + $cardpayments; ?>" readonly required>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Partial Remittance</span>
                                    <input type="number" class="form-control" min="0" name="partial" id="partial" value="<?php echo 0+$my_total_partial; ?>" onkeyup="breakdown_calc()" readonly required>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">1000 X </span>
                                    <input type="number" class="form-control" min="0" name="a_a" id="a_a" value="<?php echo 0+$break_row['gy_break_a_a']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_a" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">500 X </span>
                                    <input type="number" class="form-control" min="0" name="a_b" id="a_b" value="<?php echo 0+$break_row['gy_break_a_b']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_b" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">200 X </span>
                                    <input type="number" class="form-control" min="0" name="a_c" id="a_c" value="<?php echo 0+$break_row['gy_break_a_c']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_c" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">100 X </span>
                                    <input type="number" class="form-control" min="0" name="a_d" id="a_d" value="<?php echo 0+$break_row['gy_break_a_d']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_d" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">50 X </span>
                                    <input type="number" class="form-control" min="0" name="a_e" id="a_e" value="<?php echo 0+$break_row['gy_break_a_e']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_e" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">20 X </span>
                                    <input type="number" class="form-control" min="0" name="a_f" id="a_f" value="<?php echo 0+$break_row['gy_break_a_f']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_f" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">10 X </span>
                                    <input type="number" class="form-control" min="0" name="a_g" id="a_g" value="<?php echo 0+$break_row['gy_break_a_g']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_g" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">5 X </span>
                                    <input type="number" class="form-control" min="0" name="a_h" id="a_h" value="<?php echo 0+$break_row['gy_break_a_h']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_h" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon" style="width: 30%;">1 X </span>
                                    <input type="number" class="form-control" min="0" step="0.01" name="a_i" id="a_i" value="<?php echo 0+$break_row['gy_break_a_i']; ?>" onkeyup="breakdown_calc()" required>
                                    <span class="input-group-addon"> = <span id="b_i" style="color: blue;">0.00</span></span>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon" color: blue; font-weight: bold;">STATUS</span>
                                        <input type="text" class="form-control" name="my_over_all_status" id="my_over_all_status" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group">
                                        <span class="input-group-addon" color: blue; font-weight: bold;"><i class="fa fa-lock fa-fw"></i> ADMIN PIN</span>
                                        <input type="password" class="form-control" name="my_secure_pin" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit_breakdown_all_date" class="btn btn-lg btn-info" style="width: 100%;"><i class="fa fa-check fa-fw"></i> Submit Cash Breakdown</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">
        function ff(my_float){
            var float_value = parseFloat(my_float);

            return float_value;
        }

        function breakdown_calc(){

            var check_amount = document.getElementById('check_amount').value;
            var card_amount = document.getElementById('card_amount').value;

            var a_a = document.getElementById('a_a').value;
            var a_b = document.getElementById('a_b').value;
            var a_c = document.getElementById('a_c').value;
            var a_d = document.getElementById('a_d').value;
            var a_e = document.getElementById('a_e').value;
            var a_f = document.getElementById('a_f').value;
            var a_g = document.getElementById('a_g').value;
            var a_h = document.getElementById('a_h').value;
            var a_i = document.getElementById('a_i').value;

            var reading_over_all = <?php echo $my_total_partial; ?> + ff(check_amount) + ff(card_amount) + ff(a_a * 1000)  + ff(a_b * 500) + ff(a_c * 200) + ff(a_d * 100) + ff(a_e * 50) + ff(a_f * 20) + ff(a_g * 10) + ff(a_h * 5) + ff(a_i);

            if (<?php echo $over_all_data; ?> == reading_over_all) {
                document.getElementById('my_over_all_status').value = "PERFECT";
                document.getElementById('my_over_all_status').style.color = "blue";
            }else if (<?php echo $over_all_data; ?> > reading_over_all) {
                document.getElementById('my_over_all_status').value = "SHORT";
                document.getElementById('my_over_all_status').style.color = "red";
            }else{
                document.getElementById('my_over_all_status').value = "OVER";
                document.getElementById('my_over_all_status').style.color = "green";
            }

            if (!isNaN(reading_over_all)) {
                document.getElementById('b_a').innerHTML = ff(a_a * 1000);
                document.getElementById('b_b').innerHTML = ff(a_b * 500);
                document.getElementById('b_c').innerHTML = ff(a_c * 200);
                document.getElementById('b_d').innerHTML = ff(a_d * 100);
                document.getElementById('b_e').innerHTML = ff(a_e * 50);
                document.getElementById('b_f').innerHTML = ff(a_f * 20);
                document.getElementById('b_g').innerHTML = ff(a_g * 10);
                document.getElementById('b_h').innerHTML = ff(a_h * 5);
                document.getElementById('b_i').innerHTML = ff(a_i);

                document.getElementById('over_all_reading').value = reading_over_all;
            }else{
                document.getElementById('b_a').innerHTML = 0;
                document.getElementById('b_b').innerHTML = 0;
                document.getElementById('b_c').innerHTML = 0;
                document.getElementById('b_d').innerHTML = 0;
                document.getElementById('b_e').innerHTML = 0;
                document.getElementById('b_f').innerHTML = 0;
                document.getElementById('b_g').innerHTML = 0;
                document.getElementById('b_h').innerHTML = 0;
                document.getElementById('b_i').innerHTML = 0;

                document.getElementById('over_all_reading').value = 0;
            }
        }
    </script>

    <script type="text/javascript">
        $('#exp_date').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script>

</body>

</html>
