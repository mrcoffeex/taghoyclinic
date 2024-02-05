
<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");


    $my_project_header_title = "Error 404 - Page not Found";
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
                    <h3 class="page-header"><i class="fa fa-warning fa-fw"></i> Error 404</h3>
                </div>

                <div class="col-md-12">
                    <center>
                        <h3><i class="fa fa-cogs fa-fw"></i> Error! | This page is under construction!</h3><br>
                        <a href="index.php" title="click to go back to homepage" style="text-decoration: none; text-transform: uppercase;"><h4>Keep Out!</h4></a>
                    </center>
                </div>
            </div>
        </div>

            
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>
