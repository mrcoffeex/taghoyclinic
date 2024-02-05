<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Sales Report";

    $my_notification = @$_GET['note'];

    if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty Search Input";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here.";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
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
                    <h3 class="page-header"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></h3>
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
                <div class="col-md-12">
                    <div class="row">
                        <form method="post" enctype="multipart/form-data" action="redirect_manager">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" name="my_cashier" required>
                                        <option value="">--Select Cashier--</option>
                                        <?php  
                                            //get cashiers
                                            $get_cashier_select=$link->query("Select * From `gy_user` Where `gy_user_type`='2'");
                                            while($cashier_select_row=$get_cashier_select->fetch_array()) {
                                        ?>
                                        <option value="<?php echo $cashier_select_row['gy_user_id']; ?>"><?php echo $cashier_select_row['gy_full_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="my_date_report_f" id="my_date_report1" style="border-radius: 0px;" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="my_date_report_t" id="my_date_report2" style="border-radius: 0px;" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select class="form-control" name="condition" required>
                                        <option value="0">Summary</option>
                                        <option value="1">Full Report</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" name="submit_sales_report_sales" class="btn btn-info" title="click to search"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>                      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
