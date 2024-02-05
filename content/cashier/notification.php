
<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Notifications";

    $query_one = "Select * From `gy_notification` Order By `gy_notif_id` DESC";

    $query_two = "Select COUNT(`gy_notif_id`) From `gy_notification` Order By `gy_notif_id` DESC";

    $query_three = "Select * From `gy_notification` Order By `gy_notif_id` DESC ";

    $my_num_rows = 50;

    include 'my_pagination.php';
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
                                    <input type="text" class="form-control" placeholder="Search here and press ENTER (yyyy-mm-dd) ..." name="notif_search" id="notif_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Notifications
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
