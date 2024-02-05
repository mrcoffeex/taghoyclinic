<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Request Order Report Today";

    $my_notification = @$_GET['note'];

    $date_now = date("Y-m-d");

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Request Order Report is removed";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Empty Date Input";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $crqt=$link->query("Select DISTINCT `gy_rqt_code` From `gy_rqt` Where `gy_rqt_status`='1' AND date(`gy_rqt_date`)='$date_now' Order By `gy_rqt_date` DESC");
    $count_results=$crqt->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-check-square-o"></i> <?php echo $my_project_header_title; ?></h3>
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
                        <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="request_date_search_f" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="request_date_search_t" style="border-radius: 0px;" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="request_btn" class="btn btn-success" title="click to search"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </form>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Request Order Summary Data Table <b><?php echo 0+$count_results; ?></b> result(s)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Print</center></th>
                                            <th style="color: red;"><center>Code</center></th>
                                            <th><center>Date</center></th>
                                            <th><center>Request To</center></th>
                                            <th><center>Encoder</center></th>
                                            <th><center>Note</center></th>
                                            <th><center>Void</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  

                                        while ($count_row=$crqt->fetch_array()) {

                                            $rcode=words($count_row['gy_rqt_code']);
                                            $getrqt=$link->query("Select * From `gy_rqt` Where `gy_rqt_code`='$rcode' Order By `gy_rqt_date` DESC");
                                            $res_row=$getrqt->fetch_array();

                                            //get user info
                                            $cashier_identifier=$res_row['gy_rqt_by'];
                                            $get_user_info=$link->query("Select `gy_full_name` From `gy_user` Where `gy_user_id`='$cashier_identifier'");
                                            $user_info_row=$get_user_info->fetch_array();

                                            $encoder = $user_info_row['gy_full_name'];
                                    ?>

                                        <tr class="success">
                                            <td><center><a href="print_rqts?cd=<?php echo $rcode; ?>&encoder=<?php echo $encoder; ?>" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-success" title="click to void the restock summary ..."><i class="fa fa-print fa-fw"></i></button></a></center></td>
                                            <td style="font-weight: bold; color: blue;"><center><?php echo $res_row['gy_rqt_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo date("M d, Y g:i A", strtotime($res_row['gy_rqt_date'])); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $res_row['gy_rqt_branch']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $encoder; ?></center></td>
                                            <td><center><button type="button" class="btn btn-success" title="click to see view the note ..." data-target="#details_<?php echo $res_row['gy_rqt_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td><center><button type="button" class="btn btn-danger" title="click to void the restock summary ..." data-target="#void_<?php echo $res_row['gy_rqt_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="details_<?php echo $res_row['gy_rqt_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">NOTE</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <p style="text-align: justify;">
                                                                        <?php echo $res_row['gy_rqt_note']; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="void_<?php echo $res_row['gy_rqt_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void Request Order Data </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="void_request_summ?cd=<?php echo $res_row['gy_rqt_code']; ?>&sd=request_reports">
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

    <!-- <script type="text/javascript">
        $('#restock_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script> -->

</body>

</html>
