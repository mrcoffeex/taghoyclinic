<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Pull-Out Summary Today";

    $my_notification = @$_GET['note'];

    $date_now = date("Y-m-d");

    if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Sale Report is removed";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "pin_out") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Incorrect PIN";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_pullout` Where date(`gy_pullout_date`)='$date_now' AND `gy_pullout_by`='$user_id' Order By `gy_pullout_date` DESC";

    $query_two = "Select COUNT(`gy_pullout_id`) From `gy_pullout` Where date(`gy_pullout_date`)='$date_now' AND `gy_pullout_by`='$user_id' Order By `gy_pullout_date` DESC";

    $query_three = "Select * From `gy_pullout` Where date(`gy_pullout_date`)='$date_now' AND `gy_pullout_by`='$user_id' Order By `gy_pullout_date` DESC ";

    $my_num_rows = 50;

    include 'my_pagination.php';
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
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Trasaction Code/Customer Name ..." name="sales_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                                    <input type="date" class="form-control" name="sales_date_search" id="sales_date_search" style="border-radius: 0px;" required>
                                </form>
                            </div>
                        </div>                      
                    </div> -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Sales Data Table 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Date</center></th>
                                            <th><center>Type</center></th>
                                            <th><center>Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Note</center></th>
                                            <th><center>User</center></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($pull_row=$query->fetch_array()) {
                                            //get product details
                                            $my_code = words($pull_row['gy_product_code']);
                                            $get_details=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_code'");
                                            $details_row=$get_details->fetch_array();

                                            //get user info
                                            $my_user = words($pull_row['gy_pullout_by']);
                                            $get_my_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user'");
                                            $my_user_row=$get_my_user_info->fetch_array();

                                            if ($pull_row['gy_pullout_type'] == "FOR_USE") {
                                                $my_type_pull = "FOR USE";
                                                $my_color = "success";
                                            }else if ($pull_row['gy_pullout_type'] == "STOCK_TRANSFER") {
                                                $my_type_pull = "STOCK TRANSFER/DR";
                                                $my_color = "warning";
                                            }else if ($pull_row['gy_pullout_type'] == "BACK_ORDER") {
                                                $my_type_pull = "BACK-ORDER";
                                                $my_color = "danger";
                                            }else{
                                                $my_type_pull = "UNKNOWN";
                                                $my_color = "default";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_color; ?>">
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i:s A",strtotime($pull_row['gy_pullout_date'])); ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $my_type_pull; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $pull_row['gy_product_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $details_row['gy_product_name']; ?></center></td>
                                            <td><center><?php echo $pull_row['gy_pullout_quantity']." ".$details_row['gy_product_unit']; ?></center></td>
                                            <td><center><button type="button" class="btn btn-<?php echo $my_color;?>" title="click to see notes ..." data-target="#note_<?php echo $pull_row['gy_pullout_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td><center><?php echo $my_user_row['gy_full_name']; ?></center></td>
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="note_<?php echo $pull_row['gy_pullout_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">Note</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <p style="text-align: justify;"><?php echo $pull_row['gy_pullout_note']; ?></p>
                                                        </div>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center"> 
                         <ul class="pagination">
                            <?php echo $paginationCtrls; ?>
                         </ul>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">
        $('#sales_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script>

</body>

</html>
