<?php  

    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    require __DIR__ . '\printer\autoload.php';
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

    try {
        //compare

        if ($_POST['action'] == "00000" && $user_codex == "60899184") {
            $data = array();

            // Enter the share name for your USB printer here
            $connector = new WindowsPrintConnector("XP-58");

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);

            /* open the drawer */
            $printer -> pulse();
            $printer -> close();

            $data['status'] = 'ok';
        }else{
            $data['status'] = 'error';
        }

        echo json_encode($data);

    } catch (Exception $e) {
        echo $e;
    }


?>