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

        $get_trans_details=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
        $trans_details=$get_trans_details->fetch_array();

        //vars
        $transaction = $trans_details['gy_trans_code'];
        $customer = $trans_details['gy_trans_custname'];
        $cashier = $user_info;

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
        $printer->pulse();

        /* Print customer and order ID */
        $printer->feed();
        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Withdrawal Slip ");
        $printer->setEmphasis(true);
        $printer->text("{$transaction}\n");
        $printer->setEmphasis(false);
        // $printer->text("Account #: {$account}\n");
        $printer->text("Customer: {$customer}\n");
        $printer->text("Cashier: {$cashier}\n");
        $printer->selectPrintMode();

        $printer->feed();
        $printer->setPrintLeftMargin(0);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setEmphasis(true);
        $printer->text(addSpaces('Item', 17) . addSpaces('Qty', 5) . addSpaces('S-Total', 8) . "\n");
        $printer->setEmphasis(false);
        $items = [];

        // Select ordered items
        $sql_item_detail=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` ON `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$transaction' Order By `gy_trans_details`.`gy_product_price` DESC");
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
                "total_price" => number_format($sub_total,2),
            ];

            foreach ($items as $item) {

                //Current item ROW 1
                $name_lines = str_split($item['name'], 17);
                foreach ($name_lines as $k => $l) {
                    $l = trim($l);
                    $name_lines[$k] = addSpaces($l, 17);
                }

                $qtyx_price = str_split($item['qtyx_price'], 5);
                foreach ($qtyx_price as $k => $l) {
                    $l = trim($l);
                    $qtyx_price[$k] = addSpaces($l, 5);
                }

                $total_price = str_split($item['total_price'], 8);
                foreach ($total_price as $k => $l) {
                    $l = trim($l);
                    $total_price[$k] = addSpaces($l, 8);
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
        $printer->text(addSpaces('', 15) . addSpaces('Cash ', 7) . addSpaces(@number_format($trans_details['gy_trans_cash'],2), 8) . "\n");
        $printer->text(addSpaces('', 15) . addSpaces('Change ', 7) . addSpaces(@number_format($trans_details['gy_trans_change'],2), 8) . "\n");
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
        $get_bodega_info=$link->query("Select * From `gy_trans_details` LEFT JOIN `gy_products` ON `gy_trans_details`.`gy_product_code`=`gy_products`.`gy_product_code` Where `gy_trans_details`.`gy_trans_code`='$transaction' AND `gy_products`.`gy_product_cat`='bodega' Order By `gy_trans_details`.`gy_product_price` DESC");
        $count_bodega_items=$get_bodega_info->num_rows;

        if ($count_bodega_items == 0) {
            $printer->text("");
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

        /* Cut the receipt */
        $printer -> cut();
        $printer -> close();

        //note here and redirect
        $my_note_text = $approved_by." -> approved to Print Receipt Transaction Code: ".$transaction;
        my_notify($my_note_text,$user_info);
        header("location: trans_summary?cd=$transaction");

    } catch (Exception $e) {
        header("location: trans_summary?cd=$transaction&note=error_printer");
    }
    
?>
