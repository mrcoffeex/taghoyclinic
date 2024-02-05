<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_header_tite = "Product Image";

    $productId = @$_GET['productId'];

    $getProduct=$link->query("SELECT * From gy_products Where gy_product_id = '$productId'");
    $product=$getProduct->fetch_array();
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
            font-size: 12px;
        }
    </style>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row justify-items-center" style="margin-top: 50px;">
                <div class="col-md-12 text-center">
                    <div class="panel panel-default">
                        <div class="panel-heading">Product Image</div>
                        <div class="panel-body">
                            <p><?= $product['gy_product_name'] ?></p>
                            <img src="<?= displayImage($product['gy_product_image'], '../../img/no_image.jpg', '../../mrcoffeexpicturebox/') ?>" class="img-fluid" alt="image ...">
                            <br>
                            <br>
                            <p>
                                <?= $product['gy_product_quantity'] . " " . $product['gy_product_unit']?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>