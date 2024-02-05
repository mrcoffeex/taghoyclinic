
<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Notifications";

    $query_one = "Select * From `gy_notification` Order By `gy_notif_id` DESC";

    $query_two = "Select COUNT(`gy_notif_id`) From `gy_notification` Order By `gy_notif_id` DESC";

    $query_three = "Select * From `gy_notification` Order By `gy_notif_id` DESC ";

    $my_num_rows = 100;

    include 'my_pagination.php';

    $count_results=$link->query($query_one)->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'head.php'; ?>

<body>

    <div id="wrapper">

        <?php include 'nav.php'; ?>

        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-globe fa-fw"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Search Engine -->
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search here and press ENTER ..." name="notif_search" id="notif_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select type="text" class="form-control" name="my_condition" style="border-radius: 0px;">
                                                <option></option>
                                                <option>Update</option>
                                                <option>Discount</option>
                                                <option>Approved</option>
                                                <option>Stock-Transfer Alert</option>
                                                <option>Pull-Out Alert</option>
                                                <option>Restock Alert</option>
                                                <option>Void</option>
                                                <option>Removed</option>
                                                <option>Added</option>
                                                <option>Cash</option>
                                                <option>Cheque</option>
                                                <option>Card</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="my_date_from" id="my_date" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="my_date_to" id="my_date" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="submit" name="submit_notif_condition" class="btn btn-info" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Notifications - <b><?php echo $count_results; ?></b> result(s)
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover responsive">
                                    <thead class="ulo_lamisa4">
                                        <tr>
                                            <th>Notification</th>
                                            <th>Date and Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                            while ($data_row=$query->fetch_array()) {

                                        ?>
                                        <tr class="odd gradeX" id="rowy4">
                                            <td><?php echo $data_row['gy_notif_text']; ?></td>
                                            <td><span style="color: blue;"><?php echo date("F d, Y g:i:s A", strtotime($data_row['gy_notif_date'])); ?></span></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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

</body>

</html>
