<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member

    if (isset($_POST['my_code'])) {
        //elements
        $my_code = words($_POST['my_code']);
        $my_name = words($_POST['my_name']);
        $my_category = words($_POST['my_category']);
        $my_color = words($_POST['my_color']);
        $my_branch = words($_POST['my_branch']);
        $my_desc = words($_POST['my_desc']);
        $my_supplier = 0;
        $my_price_cap = 0;
        $my_price_srp = words($_POST['my_price_srp']);
        $my_unit = words($_POST['my_unit']);
        $my_quantity = words($_POST['my_quantity']);
        $my_limit = words($_POST['my_limit']);
        $my_restock_limit = words($_POST['my_restock_limit']);
        $date_now = date("Y-m-d H:i:s");

        $productImage = imageUpload("my_image", "../../mrcoffeexpicturebox/");

        //check duplicate code
        $check_duplicate_code=$link->query("SELECT gy_product_id From gy_products Where gy_product_code='$my_code' AND gy_branch_id='$my_branch'");
        $count_duplicate=$check_duplicate_code->num_rows;

        //check duplicate update code
        $getupcode=$link->query("SELECT gy_update_code From gy_products Order By gy_update_code DESC LIMIT 1");
        $upcoderow=$getupcode->fetch_array();

        if ($upcoderow['gy_update_code'] == 0) {
            $randoms = "10001";        
        }else{
            $randoms = $upcoderow['gy_update_code'] + 1;
        }

        if ($count_duplicate > 0){
            header("location: add_product?note=duplicate");
        }else{
            //insert to database

            if ($productImage == "error") {

                header("location: add_product?note=invalid_upload");
    
            } else {
    
                $insert_data=$link->query("INSERT INTO gy_products(gy_product_code, gy_product_cat, gy_product_color, gy_product_image, gy_supplier_code, gy_product_name, gy_product_desc, gy_product_unit, gy_product_price_cap, gy_product_price_srp, gy_product_quantity, gy_product_discount_per, gy_product_restock_limit, gy_product_date_restock, gy_product_date_reg, gy_added_by,gy_update_code,gy_product_update_date,gy_branch_id) Values('$my_code','$my_category', '$my_color', '$productImage', '$my_supplier','$my_name','$my_desc','$my_unit','$my_price_cap','$my_price_srp','$my_quantity','$my_limit','$my_restock_limit','$date_now','$date_now','$user_id','$randoms','$date_now','$my_branch')");

                if ($insert_data) {    
                    $my_note_text = $my_name." is added to products";
                    my_notify($my_note_text,$user_info);
                    header("location: add_product?note=nice");
                            
                }else{
                    header("location: add_product?note=error");
                }
            }
            
        }
    }
?>