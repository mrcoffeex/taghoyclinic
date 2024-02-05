<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_project_header_title = "Select Branch";

    $redirect = @$_GET['cd'];

    if ($redirect == "") {
        header("location: 404");
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
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-building"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                                <?php  
                                    //get branches
                                    $getbranch=$link->query("SELECT * From `gy_branch` Order By `gy_branch_id` ASC");
                                    while ($branches=$getbranch->fetch_array()) {
                                ?>

                                <tr class="info">
                                    <td class="text-uppercase"><center><?= $branches['gy_branch_name']; ?></center></td>
                                    <td><center><a href="<?= $redirect.'?br_id='.$branches['gy_branch_id']; ?>" title="click to show details ..."><button type="button" class="btn btn-primary">Select Branch <i class="fa fa-arrow-circle-right"></i></button></a></center></td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
