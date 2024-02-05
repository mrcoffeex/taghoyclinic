<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Patients";

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Patient is added";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Patient Info is Updated";
    }else if ($my_notification == "code_duplicate") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Account Code already exist! Try another code";
    }else if ($my_notification == "mrcoffeex_only_space") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "White Space is not allowed.";
    }else if ($my_notification == "mrcoffeex_only_zero") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Only ZERO is not allowed.";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "info";
        $message = "Patient successfully removed";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
    }

    $query_one = "Select * From `gy_patient` Order By `gy_pat_fullname` ASC";

    $query_two = "Select COUNT(`gy_pat_id`) FROM `gy_patient` Order By `gy_pat_fullname` ASC";

    $query_three = "Select * from `gy_patient` Order By `gy_pat_fullname` ASC ";

    $my_num_rows = 50;

    include 'my_pagination.php';

    $count_users=$link->query($query_one)->num_rows;
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
                    <h3 class="page-header"><i class="fa fa-user"></i> <?php echo $my_project_header_title; ?></h3>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="search_pat" class="form-control" placeholder="search patient name ..." autofocus required>
                            </div>   
                        </div>
                        </form>
                        <div class="col-md-6">
                            <!-- Buttons -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_user" title="click to add user ..."><i class="fa fa-plus fa-fw"></i> New Patient</button>
                        </div>
                        <hr>
                    </div>

                    <div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-plus fa-fw"></i> Add Patient</center></h4>
                                </div>
                                <div class="modal-body">
                                    <form method="post" enctype="multipart/form-data" action="add_patient" onsubmit="return validateForm(this);">

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Lastname</label>
                                                    <input type="text" name="lname" maxlength="255" class="form-control" autofocus autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Firstname</label>
                                                    <input type="text" name="fname" maxlength="255" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>M.I.</label>
                                                    <input type="text" name="mname" maxlength="5" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-control" required>
                                                        <option></option>
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Birthdate</label>
                                                    <input type="date" name="birthdate" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Contact #</label>
                                                    <input type="text" name="contact" maxlength="11" placeholder="Ex. 09123456789" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea class="form-control" name="address" placeholder="Ex. Rizal Ave. Digos City"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" name="submit" id="submit" class="btn btn-primary" title="click to add patient ...">Add <i class="fa fa-angle-right fa-fw"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Patients Data Table <span style="color: red;"><?php echo $count_users; ?> result(s)</span>
                            <span style="float: right;"> Press <span style="color: blue;">F5</span> to refresh results</span> 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Open</th>
                                            <th class="text-center">Patient</th>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Age</th>
                                            <th class="text-center">Contact #</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get patients
                                        //make pagination
                                        while ($pat_row=$query->fetch_array()) {
                                    ?>

                                        <tr>
                                            <td class="text-center"><a href="view_patient?cd=<?= $pat_row['gy_pat_id']; ?>"><button type="button" class="btn btn-info" title="click to open patient information ..."><i class="fa fa-user"></i></button></a></td>
                                            <td class="text-center"><b><?php echo $pat_row['gy_pat_fullname']; ?></b></td>
                                            <td class="text-center"><?php echo $pat_row['gy_pat_code']; ?></td>
                                            <td class="text-center"><?php echo get_curr_age($pat_row['gy_pat_birthdate']); ?></td>
                                            <td class="text-center"><?php echo $pat_row['gy_pat_contact']; ?></td>
                                            <td><?php echo $pat_row['gy_pat_address']; ?></td>
                                        </tr>
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
        function validateForm(formObj) {
      
            formObj.submit.disabled = true; 
            return true;  
      
        }  
    </script>

</body>

</html>
