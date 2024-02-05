
<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Add New Supplier";

    //for notification

    $my_notification = @$_GET['note'];

    if ($my_notification == "nice") {
        $the_note_status = "visible";
        $color_note = "success";
        $message = "New Supplier is added.";
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
                    <h3 class="page-header"><i class="fa fa-plus"></i> <?php echo $my_project_header_title; ?></h3>
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
                <form method="post" enctype="multipart/form-data" action="add_this_supplier">

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input type="text" class="form-control" maxlength="100" name="my_name" autofocus required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Supplier Description</label>
                                    <textarea class="form-control" rows="2" maxlength="200" name="my_desc" placeholder="Enter supplier details and supplier agents ..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Supplier Address</label>
                                    <textarea class="form-control" rows="2" maxlength="200" name="my_address" placeholder="text here ..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Contact #</label>
                            <input type="text" class="form-control" name="my_contact" maxlength="11" placeholder="Ex. 09xxxxxxxxx" required>
                        </div>
                    </div>

                    <!-- Submit Button Here -->
                    <div class="col-md-9">
                        <button type="submit" name="auth_add_supplier" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Save</button>
                        <button type="reset" class="btn btn-warning"><i class="fa fa-edit fa-fw"></i> New / Reset</button>
                        <a href="suppliers"><button type="button" class="btn btn-danger"><i class="fa fa-times fa-fw"></i> Exit</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
