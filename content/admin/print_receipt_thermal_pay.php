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
        $amount = @$_GET['dir'];

        $getaccount=$link->query("Select * From `gy_accounts` Where `gy_acc_id`='$my_dir_value'");
        $accountrow=$getaccount->fetch_array();

        //vars
        $customer = $accountrow['gy_acc_name'];

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

        /* Print customer and order ID */
        $printer->feed();
        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text(date("M d, Y - g:i:s A")."\n");
        $printer->text("TRA Withdrawal Slip ");
        $printer->setEmphasis(true);
        $printer->text("--------------\n");
        $printer->setEmphasis(false);
        $printer->text("Customer: {$customer}\n");
        $printer->selectPrintMode();

        $printer->feed();
        $printer->setPrintLeftMargin(0);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setEmphasis(true);
        $printer->text(addSpaces('Item', 13) . addSpaces('Qty', 5) . addSpaces('S-Total', 12) . "\n");
        $printer->setEmphasis(false);
        $items = [];

        $items[1] = [
            "name" => "TRA PAYMENT",
            "qtyx_price" => "-1",
            "total_price" => number_format($amount,2),
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

        //total here
        $printer->text("________________________________");
        $printer->feed();
        $printer->setEmphasis(true);
        $printer->text(addSpaces('', 15) . addSpaces('Total ', 7) . addSpaces(@number_format($amount,2), 8) . "\n");
        $printer->setEmphasis(false);
        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("NOTICE: This is just a TRA Withdrawal Slip Not valid for claiming input tax this serves as your   proff of payment.\n");
        $printer->text("THANK YOU! COME AGAIN!\n");
        $printer->feed();
        $printer->feed();
        $printer->feed();
        $printer->feed();

        /* Cut the receipt */
        $printer -> cut();
        $printer -> close();

        //note here and redirect
        header("location: tra_accounts?cd=print_receipt");

    } catch (Exception $e) {
        header("location: tra_accounts?note=error_printer");
    }
    
?>
