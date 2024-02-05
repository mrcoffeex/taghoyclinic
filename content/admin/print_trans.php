<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    require __DIR__ . '\printer\autoload.php';
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

    $my_dir_value = $_GET['cd'];
    //get info 
    $total="";
    $get_info=$link->query("Select * From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");
    while ($get_info_row=$get_info->fetch_array()) {

        $my_final_price = $get_info_row['gy_product_price'] - $get_info_row['gy_product_discount'];

        $total += $my_final_price * $get_info_row['gy_trans_quantity'];
    }

    // Enter the share name for your USB printer here
    //$connector = null;
    $connector = new WindowsPrintConnector("POS-58-Series");

    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector);
    $printer -> setTextSize(1,1);
    $printer -> text("\n");
    $printer -> text("\n");
    $printer -> text("Transaction Code:\n");
    $printer -> setTextSize(2,2);
    $printer -> text($my_dir_value."\n");
    $printer -> setTextSize(1,1);
    $printer -> text("Grand Total: ".number_format($total,2)."\n");
    $printer -> text("\n");
    $printer -> text("\n");
    $printer -> text("\n");
    $printer -> text("\n");
    $printer -> text("\n");
    $printer -> cut();
    
    /* Close printer */
    $printer -> close();

    header("location: sales_counter?note=submit");

?>
