<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_project_header_title = "General Item Update";

    $my_notification = @$_GET['note'];

    if ($my_notification == "success") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Update Success";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "invalid") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Wrong File";
    }else if ($my_notification == "empty") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "No file selected";
    }else if ($my_notification == "pin") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $connect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $get_all_table_query = "SHOW TABLES";
    $statement = $connect->prepare($get_all_table_query);
    $statement->execute();
    $result = $statement->fetchAll();

    if(isset($_POST['export'])){

    $show_table_query = "SHOW CREATE TABLE `gy_update`";
    $statement = $connect->prepare($show_table_query);
    $statement->execute();
    $show_table_result = $statement->fetchAll();

    foreach($show_table_result as $show_table_row){
    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
    }

    for ($i=0; $i < 1; $i++) { 
        $output .= "\nINSERT INTO `gy_update`(`gy_product_id`, `gy_product_code`, `gy_convert_item_code`, `gy_convert_value`, `gy_supplier_code`, `gy_product_name`, `gy_product_cat`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_added_by`, `gy_update_code`, `gy_update_status`) Values";
    }
        $select_query = "SELECT `gy_product_id`, `gy_product_code`, `gy_convert_item_code`, `gy_convert_value`, `gy_supplier_code`, `gy_product_name`, `gy_product_cat`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_added_by`, `gy_update_code` FROM `gy_products`";
        $statement = $connect->prepare($select_query);
        $statement->execute();
        $total_row = $statement->rowCount();

    for($count=0; $count<$total_row; $count++){
       $single_result = $statement->fetch(PDO::FETCH_ASSOC);
       $table_column_array = array_keys($single_result);
       $table_value_array = array_values($single_result);
       $output .= "('" . implode("','", $table_value_array) . "', 0),\n";
    }

    $file_name = 'gy_update.sql';
    $file_handle = fopen($file_name, 'w+');
    fwrite($file_handle, $output."(0,'',0,0,'','','','',0,0,0,0,0,'','',0,'',0);");
    fclose($file_handle);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file_name));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        unlink($file_name);
    }

?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <?php include 'nav.php'; ?>

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-8">
                    <h3 class="page-header"><i class="fa fa-credit-card"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
                <div class="col-lg-4">
                    <!-- notification here -->
                    <div class="alert alert-<?php echo @$color_note; ?> alert-dismissable" id="my_note" style="margin-top: 12px; visibility: <?php echo @$the_note_status; ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo @$message; ?>.
                    </div>
                </div>

                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data" action="submit_update">
                        <div class="form-group">
                            <input type="file" name="database" required><br>
                            <button type="submit" name="import" class="btn btn-success" title="click to update ..."><i class="fa fa-upload"></i> Import Update</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" name="insert" class="btn btn-primary" title="click to insert ..." data-target="#insert" data-toggle="modal"><i class="fa fa-upload"></i> Import Insert</button>
                        </div>
                    </form>

                    <div class="modal fade" id="insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-key fa-fw"></i> Update Password </h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post" enctype="multipart/form-data" action="submit_update">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><i class="fa fa-lock fa-fw"></i> Update Secure PIN</label>
                                                    <input type="password" name="my_secure_pin" class="form-control" autofocus required>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-striped table-bordered table-hover">
                            <?php  
                                $getsame=$link->query("SELECT `gy_product_name`, COUNT(*) c FROM `gy_products` GROUP BY `gy_product_name` HAVING c > 1");
                                $counts=$getsame->num_rows;

                                /* SELECT a.gy_product_code,a.gy_product_name,a.gy_product_price_cap,a.gy_product_price_srp,a.gy_product_quantity,a.gy_product_unit FROM gy_products a INNER JOIN gy_products b On a.gy_product_name = b.gy_product_name Where a.gy_product_id <> b.gy_product_id Order By a.gy_product_name ASC*/
                            ?>
                            <thead>
                                <tr>
                                    <th colspan="3" style="color: red;"><center>DUPLICATE NAMES -> <?php echo 0+$counts; ?></center></th>
                                </tr>
                                <tr>
                                    <th><center>No.</center></th>
                                    <th><center>Description</center></th>
                                    <th><center>Duplicates</center></th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php  
                                    //view all duplicate product names
                                    $num=0;
                                    while ($samerow=$getsame->fetch_array()) {
                                        $num++;
                                ?>
                                <tr>
                                    <td><center><?php echo $num; ?></center></td>
                                    <td><center><?php echo $samerow['gy_product_name']; ?></center></td>
                                    <td><b><center><?php echo $samerow['c']; ?></center></b></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <table class="table table-striped table-bordered table-hover">
                            <?php  
                                $getupdates=$link->query("SELECT `gy_product_code`,`gy_product_name`,`gy_update_code`, COUNT(*) c FROM `gy_products` GROUP BY `gy_update_code` HAVING c > 1");
                                $upcounts=$getupdates->num_rows;

                                /*SELECT a.gy_product_code,a.gy_product_name,a.gy_product_price_cap,a.gy_product_price_srp,a.gy_product_quantity,a.gy_product_unit,a.gy_update_code FROM gy_products a INNER JOIN gy_products b On a.gy_update_code = b.gy_update_code Where a.gy_product_id <> b.gy_product_id Order By a.gy_product_name ASC*/
                            ?>
                            <thead>

                                <tr>
                                    <th colspan="2" style="color: red;"><center>DUPLICATE UPDATE CODES -> <?php echo 0+$upcounts; ?></center></th>
                                    <th><center><button type="submit" name="export" class="btn btn-danger" title="click to download update ..."><i class="fa fa-download"></i> Export</button></center></th>
                                    <th><center><a href="print_duplicates" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1280,height=720,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-success" title="click to print duplicate items ..."><i class="fa fa-print"></i> Print</button></a></center></th>
                                <tr>
                                    <th><center>Code</center></th>
                                    <th><center>Description</center></th>
                                    <th><center>Update Code</center></th>
                                    <th><center>Duplicates</center></th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php  
                                    //view all duplicate product names
                                    while ($updaterow=$getupdates->fetch_array()) {
                                ?>
                                <tr>
                                    <td><center><?php echo $updaterow['gy_product_code']; ?></center></td>
                                    <td><center><?php echo $updaterow['gy_product_name']; ?></center></td>
                                    <td><center><?php echo $updaterow['gy_update_code']; ?></center></td>
                                    <td><b><center><?php echo $updaterow['c']; ?></center></b></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>
 -->
</body>

</html>
