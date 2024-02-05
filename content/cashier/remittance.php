<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Remittance Today";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Remittance is added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Remittance Info successfully removed";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $date_now = words(date("Y-m-d"));

    $my_query=$link->query("Select * From `gy_remittance` Where date(`gy_remit_date`)='$date_now' AND `gy_user_id`='$user_id' Order By `gy_remit_date` ASC");

    $count_results=$my_query->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-check"></i> <?php echo $my_project_header_title; ?></h3>
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
                <form method="post" enctype="multipart/form-data" action="add_remittance" onsubmit="return validateForm(this);">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Type (PARTIAL / FULL)</label>
                                    <select name="my_type" class="form-control" required>
                                        <option></option>
                                        <option value="0">PARTIAL REMITTANCE</option>
                                        <option value="2">CHECK DATED REMITTANCE</option>
                                        <option value="1">FULL REMITTANCE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" name="my_amount" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fa fa-lock fa-fw"></i> ADMIN PIN</label>
                                    <input type="password" name="my_secure_pin" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="submit_remit" id="submit_remit" class="btn btn-lg btn-success" title="click to submit remittance ..." style="border-radius: 0px;"><i class="fa fa-check fa-fw"></i> Submit Remittance</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="col-md-7">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="color: blue"><?php echo $user_info; ?></span> Remittance Data Table <b><?php echo $count_results; ?> results(s)</b>
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Time</center></th>
                                            <th><center>Type</center></th>
                                            <th><center>Amount</center></th>
                                            <th><center>Approved By</center></th>
                                            <th><center>Void</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        $total_amount="";
                                        while ($remit_row=$my_query->fetch_array()) {

                                            $my_user_info=words($remit_row['gy_approved_by']);

                                            //get user info
                                            $get_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user_info'");
                                            $user_info_row=$get_user_info->fetch_array();

                                            @$total_amount += $remit_row['gy_remit_value'];

                                            //type
                                            if ($remit_row['gy_remit_type'] == 0) {
                                                $my_remit_type = "PARTIAL REMITTANCE";
                                            }else if ($remit_row['gy_remit_type'] == 1) {
                                                $my_remit_type = "FULL REMITTANCE";
                                            }else if ($remit_row['gy_remit_type'] == 2) {
                                                $my_remit_type = "CHEQUE DATED REMITTANCE";
                                            }
                                    ?>

                                        <tr>
                                            <td><center><?php echo date("g:i:s A", strtotime($remit_row['gy_remit_date'])); ?></center></td>
                                            <td><center><?php echo $my_remit_type; ?></center></td>
                                            <td><center><?php echo number_format($remit_row['gy_remit_value'],2); ?></center></td>
                                            <td><center><?php echo $user_info_row['gy_full_name']; ?></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to void remittance ..." data-target="#delete_<?php echo $remit_row['gy_remit_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Delete -->

                                        <div class="modal fade" id="delete_<?php echo $remit_row['gy_remit_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void Remittance <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="delete_remit?cd=<?php echo $remit_row['gy_remit_id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label><i class="fa fa-lock fa-fw"></i> Delete Secure PIN</label>
                                                                        <input type="password" name="my_secure_pin" class="form-control" autofocus required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>
                                    <tr>
                                        <td></td>
                                        <td><b><center>Total Amount</center></b></td>
                                        <td><center><b><?php echo @number_format($total_amount,2); ?></center></b></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">  
        function validateForm(formObj) {
      
            formObj.submit_remit.disabled = true; 
            return true;  
      
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
