<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $redirect = @$_GET['cd'];

    $getpatient=$link->query("SELECT * From `gy_patient` Where `gy_pat_id`='$redirect'");
    $pat_row=$getpatient->fetch_array();
    $countres=$getpatient->num_rows;

    if ($countres < 1) {
        echo "
            <script>
                window.close();
            </script>
        ";
    }

    //get last visit
    $getvisit=$link->query("SELECT `gy_pres_date` From `gy_pres` Where `gy_pat_id`='$redirect' ORDER By `gy_pres_date` DESC LIMIT 1");
    $countvisit=$getvisit->num_rows;
    $visit_row=$getvisit->fetch_array();

    if ($countvisit == 0) {
        $visit = "N/A";
    }else{
        $visit = date("M d, Y", strtotime($visit_row['gy_pres_date']));
    }

    $my_project_header_title = $pat_row['gy_pat_fullname'];

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Patient information updated";
        $note_display = "";
    }else if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
        $note_display = "";
    }else if ($my_notification == "nice_update") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Patient Info is Updated";
        $note_display = "";
    }else if ($my_notification == "case_added") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Case Added";
        $note_display = "";
    }else if ($my_notification == "case_update") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Case Updated";
        $note_display = "";
    }else if ($my_notification == "case_delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Case Deleted";
        $note_display = "";
    }else if ($my_notification == "delete") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "Patient successfully removed";
        $note_display = "";
    }else{
        $the_note_status = "hidden";
        $color_note = "default";
        $message = "";
        $note_display = "display: none;";
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>

    <style type="text/css">
        img{
            max-width:180px;
        }

        input[type=file]{
            padding:0px;
        }

        @media print{
            .no-print{
                display: none !important;
            }

            .my_hr{
                height: 5px;
                color: #000;
                background-color: #000;
                border: none;
            }

            td{
                background-color: rgba(255,255,255, 0.1);
            }
        }

        .my_hr{
            height: 5px;
            color: #000;
            background-color: #000;
            border: none;
        }

        td{
            background-color: rgba(255,255,255, 0.1);
            font-size: 14px;
        }
    </style>
<body>

    <div id="wrapper">

        <?php include 'nav.php'; ?>

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <p style="font-size: 20px;">
                               <center>
                                <span style="font-size: 20px; font-weight: bold;"><i class="fa fa-file-text-o"></i> <?php echo $my_project_header_title; ?></span><br>
                                </center>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 0px;">
                        <div class="col-md-4">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr class="<?= $color_note; ?>" style="visibility: <?= $the_note_status; ?>; <?= $note_display; ?>">
                                        <th colspan="2" class="text-center"><?= $message; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Options</th>
                                        <th class="text-center"><button type="button" data-toggle="modal" data-target="#edit" class="btn btn-info" title="click to edit patient profile ..."><i class="fa fa-edit"></i> Edit</button> <button type="button" data-toggle="modal" data-target="#delete" class="btn btn-danger" title="click to delete patient profile ..."><i class="fa fa-trash-o"></i> Delete</button></th>
                                    </tr>
                                    <tr>
                                        <th>Last Visit</th>
                                        <th class="text-center"><?= $visit; ?></th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center"><?= $pat_row['gy_pat_code']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <th class="text-center"><?= $pat_row['gy_pat_fullname']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <th class="text-center"><?= $pat_row['gy_pat_gender']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Age</th>
                                        <th class="text-center"><?= get_curr_age($pat_row['gy_pat_birthdate']); ?></th>
                                    </tr>
                                    <tr>
                                        <th>Contact #</th>
                                        <th class="text-center"><?= $pat_row['gy_pat_contact']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <th class="text-center"><?= $pat_row['gy_pat_address']; ?></th>
                                    </tr>
                                    <tr>
                                        <th>Date Reg.</th>
                                        <th class="text-center"><?= date("M d, Y g:i A", strtotime($pat_row['gy_pat_datereg'])); ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-striped table-bordered table-hover" style="width: 100%; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="text-center">Prescriptions <small>press <span style="color: blue; font-style: italic;">F5</span> to refresh</small></th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Details</th>
                                        <th class="text-center">Print</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        $pres=$link->query("SELECT * FROM `gy_pres` WHERE `gy_pres_id` IN (SELECT MAX(`gy_pres_id`) FROM `gy_pres` Group BY `gy_pres_code`) AND `gy_pat_id`='$redirect' Order By `gy_pres_date` DESC");

                                        while ($pres_row=$pres->fetch_array()) {

                                            $my_user = words($pres_row['gy_pres_by']);
                                            $get_my_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user'");
                                            $my_user_row=$get_my_user_info->fetch_array();

                                            $prescode = $pres_row['gy_pres_code'];
                                    ?>                  
                                    <tr>
                                        <td class="text-center"><?php echo date("M d, Y g:i A", strtotime($pres_row['gy_pres_date'])); ?></td>
                                        <td class="text-center" style="font-weight: bold; color: blue;"><?php echo $pres_row['gy_pres_code']; ?></td>
                                            <td><center><button type="button" class="btn btn-info" title="click to see prescriptions ..." data-target="#pres_<?php echo $pres_row['gy_pres_id']; ?>" data-toggle="modal"><i class="fa fa-medkit fa-fw"></i></button></center></td>
                                        <td><center><a href="print_prsummary?cd=<?= $pres_row['gy_pres_code']; ?>&mode=search" onclick="window.open(this.href, 'mywin',
'left=20,top=20,width=1366,height=768,toolbar=1,resizable=0'); return false;"><button type="button" class="btn btn-success" title="click to print ..."><i class="fa fa-print fa-fw"></i></button></center></td>
                                    </tr>

                                    <div class="modal fade" id="pres_<?php echo $pres_row['gy_pres_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel"><i class="fa fa-medkit"></i> Prescription</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info" style="border-radius: 0px;">
                                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                                            By <b><?php echo $my_user_row['gy_full_name']; ?></b>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <?php  
                                                                                //get prescriptions
                                                                                $getpres=$link->query("SELECT `gy_product_name`, `gy_product_desc`, `gy_product_unit`,`gy_pres_quantity` From `gy_pres` Where `gy_pres_code`='$prescode' Order By `gy_pres_id` ASC");
                                                                            ?>

                                                                            <ul class="list-unstyled">
                                                                                <?php while ($presrow=$getpres->fetch_array()) { ?>

                                                                                <li><a href="#"><?= $presrow['gy_product_name']." ".$presrow['gy_product_desc']; ?> <span class="pull-right"><?php echo $presrow['gy_pres_quantity']." ".$presrow['gy_product_unit']; ?></span></a></li>

                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
    </div>

    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><center><i class="fa fa-plus fa-fw"></i> Add Patient</center></h4>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="edit_patient?cd=<?= $redirect; ?>" onsubmit="return validateForm(this);">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Lastname</label>
                                    <input type="text" name="lname" maxlength="255" class="form-control" value="<?= $pat_row['gy_pat_lname']; ?>" autofocus autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Firstname</label>
                                    <input type="text" name="fname" maxlength="255" class="form-control" value="<?= $pat_row['gy_pat_fname']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>M.I.</label>
                                    <input type="text" name="mname" maxlength="5" class="form-control" value="<?= $pat_row['gy_pat_mname']; ?>" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option><?= $pat_row['gy_pat_gender']; ?></option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Birthdate</label>
                                    <input type="date" name="birthdate" class="form-control" value="<?= $pat_row['gy_pat_birthdate']; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact #</label>
                                    <input type="text" name="contact" maxlength="11" placeholder="Ex. 09123456789" class="form-control" value="<?= $pat_row['gy_pat_contact']; ?>" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" name="address" placeholder="Ex. Rizal Ave. Digos City"><?= $pat_row['gy_pat_address']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" name="submit" id="submit" class="btn btn-info" title="click to update patient information ...">Update <i class="fa fa-angle-right fa-fw"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o"></i> Delete Patient</h4>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this patient information?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href="delete_patient?cd=<?= $redirect; ?>"><button type="button" class="btn btn-danger">Delete</button></a>
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