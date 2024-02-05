<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    $productId = $_GET['productId'];
    $s_type = @$_GET['s_type'];
    $pn = @$_GET['pn'];
    $search_text = @$_GET['search_text'];

    if ($s_type == "normal") {
        $redirectTo = "album?pn=$pn";
    }else{
        $redirectTo = "albumSearch?pn=$pn&search_text=$search_text";
    }

    if (!empty($_FILES['productImage'])) {

        $productImage = imageUpload("productImage", "../../mrcoffeexpicturebox/");

        if ($productImage == "error") {

            header("location: " . $redirectTo . "&note=invalid");

        } else if ($productImage == "") {

            header("location: " . $redirectTo . "&note=empty");

        } else {

            $request = updateProductImage($productImage, $productId);

            if ($request == true) {    
                header("location: " . $redirectTo . "&note=updated");      
            }else{
                header("location: " . $redirectTo . "&note=error");
            }
        }
    }
?>