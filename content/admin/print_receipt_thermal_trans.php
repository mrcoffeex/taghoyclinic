<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    require __DIR__ . '\printer\autoload.php';
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

    try {

        $my_dir_value = @$_GET['cd'];

        $get_trans_details=$link->query("Select * From `gy_tra` Where `gy_trans_code`='$my_dir_value'");
        $trans_details=$get_trans_details->fetch_array();

        //vars
        $transaction = $trans_details['gy_trans_code'];
        $customer = $trans_details['gy_trans_custname'];
        $cashier = $user_info;
        $sales = $trans_details['gy_salesman'];

        $getsalesman=$link->query("SELECT `gy_full_name` From `gy_user` Where `gy_user_id`='$sales'");
        $salesrow=$getsalesman->fetch_array();

        $salesman = $salesrow['gy_full_name'];

        if ($trans_details['gy_trans_pay'] == 0) {
            $method = "Cash";
            $royal_fee = "";
            $my_change = $trans_details['gy_trans_change'];
            $my_check_amount = "";
        }else{
            $method = "Cheque";
            $royal_fee = number_format(0 + $trans_details['gy_trans_royal_fee'],2);
            $my_change = $sales_row['gy_trans_change'] - $trans_details['gy_trans_royal_fee'];
            $my_check_amount = number_format($trans_details['gy_trans_cash'], 2);
        }

        if (isset($_POST['my_secure_pin'])) {
            $my_secure_pin = $_POST['my_secure_pin'];
            $approved_info = by_pin_get_user($my_secure_pin, 'print');
            //get approved_by info
            $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
            $approved_row=$get_approved_by_info->fetch_array();

            //value here
            $approved_by = $approved_row['gy_full_name'];

            //get secure pin
            $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='print' AND `gy_user_id`='$approved_info'");
            $get_values=$get_secure_pin->fetch_array();

            if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

                // Enter the share name for your USB printer here
                //$connector = null;
                $connector = new WindowsPrintConnector("XP-58");

                /* Print a "Hello world" receipt" */
                $printer = new Printer($connector);

                function addSpaces($string = '', $valid_string_length = 0) {
                    if (strlen($string) < $valid_string_length) {
                        $spaces = $valid_string_length - strlen($string);
                        for ($index1 = 1; $index1 <= $spaces; $index1++) {
                            $string = $string . ' ';
                        }
                    }

                    return $string;
                }

                // open drawer
                // $printer->pulse();

                /* Print customer and order ID */
                $printer->feed();
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text(date("M d, Y - g:i:s A", strtotime($trans_details['gy_trans_date']))."\n");
                $printer->text("Withdrawal Slip ");
                $printer->setEmphasis(true);
                $printer->text("{$transaction}\n");
                $printer->setEmphasis(false);
                // $printer->text("Account #: {$account}\n");
                $printer->text("Customer: {$customer}\n");
                $printer->text("Cashier: {$cashier}\n");
                $printer->text("Salesman: {$salesman}\n");
                $printer->selectPrintMode();

                $printer->feed();
                $printer->setPrintLeftMargin(0);
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setEmphasis(true);
                $printer->text(addSpaces('Item', 13) . addSpaces('Qty', 5) . addSpaces('S-Total', 12) . "\n");
                $printer->setEmphasis(false);
                $items = [];

                // Select ordered items
                $sql_item_detail=$link->query("Select * From `gy_tra_details` LEFT JOIN `gy_products` ON `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$transaction' Order By `gy_tra_details`.`gy_product_price` DESC");
                $total = '';

                while ($row_item_detail=$sql_item_detail->fetch_array()) {
                    $item_name = $row_item_detail['gy_product_name'];
                    $quantity = $row_item_detail['gy_trans_quantity'];

                    $price = $row_item_detail['gy_product_price'];
                    $sub_total = $price * $row_item_detail['gy_trans_quantity'];
                    @$total += $sub_total;

                    $items[1] = [
                        "name" => "{$item_name}",
                        "qtyx_price" => "-{$quantity}",
                        "total_price" => "(".number_format($price,2).")".number_format($sub_total,2),
                    ];

                    foreach ($items as $item) {

                        //Current item ROW 1
                        $name_lines = str_split($item['name'], 13);
                        foreach ($name_lines as $k => $l) {
                            $l = trim($l);
                            $name_lines[$k] = addSpaces($l, 13);
                        }

                        $qtyx_price = str_split($item['qtyx_price'], 5);
                        foreach ($qtyx_price as $k => $l) {
                            $l = trim($l);
                            $qtyx_price[$k] = addSpaces($l, 5);
                        }

                        $total_price = str_split($item['total_price'], 12);
                        foreach ($total_price as $k => $l) {
                            $l = trim($l);
                            $total_price[$k] = addSpaces($l, 12);
                        }

                        $counter = 0;
                        $temp = [];
                        $temp[] = count($name_lines);
                        $temp[] = count($qtyx_price);
                        $temp[] = count($total_price);
                        $counter = max($temp);

                        for ($i = 0; $i < $counter; $i++) {
                            $line = '';
                            if (isset($name_lines[$i])) {
                                $line .= ($name_lines[$i]);
                            }
                            if (isset($qtyx_price[$i])) {
                                $line .= ($qtyx_price[$i]);
                            }
                            if (isset($total_price[$i])) {
                                $line .= ($total_price[$i]);
                            }
                            $printer->text($line . "\n");
                        }
                    }
                }

                //total here
                $printer->text("________________________________");
                $printer->feed();
                $printer->setEmphasis(true);
                $printer->text(addSpaces('', 15) . addSpaces('Total ', 7) . addSpaces(@number_format($total,2), 8) . "\n");
                $printer->setEmphasis(false);
                $printer->feed();
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("NOTICE: This is just a Withdrawal Slip Not valid for claiming input tax this serves as your   proff of payment.\n");
                $printer->text("THANK YOU! COME AGAIN!\n");
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->feed();

                // Select ordered items
                $total = '';
                $get_bodega_info=$link->query("Select * From `gy_tra_details` LEFT JOIN `gy_products` ON `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$transaction' AND `gy_products`.`gy_product_cat`='bodega' Order By `gy_tra_details`.`gy_product_price` DESC");
                $count_bodega_items=$get_bodega_info->num_rows;

                //counte delivery
        $get_delivery_info=$link->query("Select `gy_tra_details`.`gy_product_price` as `totaldelivery` From `gy_tra_details` LEFT JOIN `gy_products` ON `gy_tra_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_tra_details`.`gy_trans_code`='$transaction' AND `gy_products`.`gy_product_cat`='delivery' Order By `gy_tra_details`.`gy_product_price` DESC");
        $count_delivery=$get_delivery_info->num_rows;

        if ($count_bodega_items == 0) {

            if ($count_delivery > 0) {
                $printer->text("--------------------------------");
                $printer->feed();
                $printer->feed();

                @$deliverytotal=0;
                while ($delivery_row=$count_delivery->fetch_array()) {
                    @$deliverytotal += $delivery_row['totaldelivery'];
                }
                $printer->text("DELIVERY FEE: ".@number_format($deliverytotal,2));
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->text("HAVE A GREAT DAY!\n");
                $printer->feed();
            }else{
                $printer->text("");
            }

        }else{

            if ($count_delivery > 0) {
                $printer->text("--------------------------------");
                $printer->feed();
                $printer->feed();

                @$deliverytotal=0;
                while ($delivery_row=$count_delivery->fetch_array()) {
                    @$deliverytotal += $delivery_row['totaldelivery'];
                }
                $printer->text("DELIVERY FEE: ".@number_format($deliverytotal,2)."\n");
                $printer->feed();
                $printer->text("BODEGA ITEMS\n");
                $printer->text("Transaction Code: ");
                $printer->setEmphasis(true);
                $printer->text("{$transaction}\n");
                $printer->setEmphasis(false);
                $printer->feed();

                //vars

                while ($bodega_row=$get_bodega_info->fetch_array()) {
                    $printer->text( addSpaces($bodega_row['gy_trans_quantity']." ".$bodega_row['gy_product_unit'], 12) . addSpaces(" -> ".$bodega_row['gy_product_name']." ", 18) . "\n");
                }                         

                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->text("HAVE A GREAT DAY!\n");
                $printer->feed();
            }else{

                $printer->text("--------------------------------");
                $printer->feed();
                $printer->feed();
                $printer->text("BODEGA ITEMS\n");
                $printer->text("Transaction Code: ");
                $printer->setEmphasis(true);
                $printer->text("{$transaction}\n");
                $printer->setEmphasis(false);
                $printer->feed();

                //vars

                while ($bodega_row=$get_bodega_info->fetch_array()) {
                    $printer->text( addSpaces($bodega_row['gy_trans_quantity']." ".$bodega_row['gy_product_unit'], 12) . addSpaces(" -> ".$bodega_row['gy_product_name']." ", 18) . "\n");
                }                         

                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->feed();
                $printer->text("HAVE A GREAT DAY!\n");
                $printer->feed();
            }
        }

                /* Cut the receipt */
                $printer -> cut();
                $printer -> close();
                
                //note here and redirect
                $my_note_text = $approved_by." -> approved to Print Receipt Transaction Code: ".$transaction;
                my_notify($my_note_text,$user_info);
                header("location: trans_summary?cd=$transaction");
                
            }else{
                // pin out
                header("location: trans_summary?cd=$transaction&note=pin_out");
            }

        }

    } catch (Exception $e) {
        header("location: trans_summary?cd=$transaction&note=error_printer");
    }
    
?>
