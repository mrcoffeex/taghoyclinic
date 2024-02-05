<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Items Sold Today";

    $my_notification = @$_GET['note'];

    if ($my_notification == "error") {
        $the_note_status = "visible";
        $color_note = "danger";
        $message = "Theres something wrong here";
    }else if ($my_notification == "empty_search") {
        $the_note_status = "visible";
        $color_note = "warning";
        $message = "Empty Date Input";
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
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="soldFrom" id="soldFrom" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="soldTo" id="soldTo" style="border-radius: 0px;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" name="soldBtn" class="btn btn-success" title="click to search ..."><i class="fa fa-search"></i> Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>                   
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Sold List
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Code</th>
                                            <th>Description</th>
                                            <th class="text-center">Category / Color</th>
                                            <th class="text-center">Qty Sold</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                        $getSold=selectItemsSold($dateNow, $dateNow);
                                        while ($sold=$getSold->fetch_array()) {
                                    ?>

                                        <tr>
                                            <td class="text-center text-bold"><?= getProductCode($sold['item_sold']) ?></td>
                                            <td><?= $sold['gy_product_name'] ?></td>
                                            <td class="text-center"><?= $sold['gy_product_cat'] . " / " . $sold['gy_product_color'] ?></td>
                                            <td class="text-center text-bold"><?= getItemSoldQty($dateNow, $dateNow, $sold['item_sold']) . " " . $sold['gy_product_unit'] ?></td>
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
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
