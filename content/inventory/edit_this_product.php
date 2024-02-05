<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $my_dir_value = words($_GET['cd']);
    $br = @$_GET['br'];
    $s_type = @$_GET['s_type'];
    $pagenum = @$_GET['pn'];
    $search_text = @$_GET['search_text'];

    //get product data
    $get_product_info=$link->query("Select * From gy_products Where gy_product_id='$my_dir_value'");
    $product_row=$get_product_info->fetch_array();

    $my_product_code = $product_row['gy_product_code'];
    $my_product_name = $product_row['gy_product_name'];
    $my_product_cat = $product_row['gy_product_cat'];
    $my_product_desc = $product_row['gy_product_desc'];
    $my_supplier_code = $product_row['gy_supplier_code'];
    $my_product_price_srp = $product_row['gy_product_price_srp'];
    $my_product_unit = $product_row['gy_product_unit'];
    $my_product_quantity = $product_row['gy_product_quantity'];
    $my_product_discount_per = $product_row['gy_product_discount_per'];
    $my_product_restock_limit = $product_row['gy_product_restock_limit'];
    $my_product_color = $product_row['gy_product_color'];
    $my_product_image = $product_row['gy_product_image'];

    function compare_update($old_data , $new_data , $type_data){
        if ($old_data != $new_data) {
            $my_data_res = $type_data.": ".$old_data." -> ".$new_data." , ";
        }else{
            $my_data_res = "";
        }

        return $my_data_res;
    }

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
        $my_price_srp = words($_POST['my_price_srp']);
        $my_unit = words($_POST['my_unit']);
        $my_quantity = words($_POST['my_quantity']);
        $my_limit = words($_POST['my_limit']);
        $my_restock_limit = words($_POST['my_restock_limit']);
        $date_now = date("Y-m-d H:i:s");

        $productImage = imageUpload("my_image", "../../mrcoffeexpicturebox/");

        if (productImage == "error") {
            header("location: edit_product?note=invalid_upload&cd=$my_dir_value&pn=$pagenum&s_type=$s_type&br=$br&search_text=$search_text");
        } else {

            if ($productImage == "") {
                $productImage = $product_row['gy_product_image'];
            } else {
                $productImage = $productImage;
            }
            
            //check duplicate code
            $check_duplicate_code=$link->query("SELECT * From gy_products Where gy_product_code='$my_code' AND gy_branch_id='$my_bbranch'");
            $count_duplicate=$check_duplicate_code->num_rows;

            if ($my_product_code == $my_code){
                //insert to database
                $update_data=$link->query("UPDATE 
                                        gy_products 
                                        SET 
                                        gy_product_code='$my_code',
                                        gy_product_cat='$my_category',
                                        gy_product_color='$my_color',
                                        gy_product_image='$productImage',
                                        gy_supplier_code='$my_supplier',
                                        gy_product_name='$my_name',
                                        gy_product_desc='$my_desc',
                                        gy_product_unit='$my_unit',
                                        gy_product_price_srp='$my_price_srp',
                                        gy_product_quantity='$my_quantity',
                                        gy_product_discount_per='$my_limit',
                                        gy_product_restock_limit='$my_restock_limit',
                                        gy_product_update_date='$date_now' 
                                        Where 
                                        gy_product_id='$my_dir_value'");

                if ($update_data) {

                    //data here
                    $my_a = compare_update($my_product_code , $my_code , "Product Code");
                    $my_b = compare_update($my_product_name , $my_name , "Product Description");
                    $my_c = compare_update($my_product_cat , $my_category , "Product Category");
                    $my_d = compare_update($my_product_desc , $my_desc , "Product Details");
                    $my_e = compare_update($my_supplier_code , $my_supplier , "Supplier System ID");
                    $my_g = compare_update($my_product_price_srp , $my_price_srp , "Product SRP");
                    $my_h = compare_update($my_product_unit , $my_unit , "Product Unit");
                    $my_i = compare_update($my_product_quantity , $my_quantity , "Quantity");
                    $my_j = compare_update($my_product_discount_per , $my_limit , "Discount Limit");
                    $my_k = compare_update($my_product_restock_limit , $my_restock_limit , "Restock Limit");
                    $my_l = compare_update($my_product_color , $my_color , "Product Color");
                    $my_m = compare_update($my_product_image , $productImage , "Product Image");

                    $note_text = $my_product_name." Product Update -> ".$my_a."".$my_b."".$my_c."".$my_d."".$my_e."".$my_g."".$my_h."".$my_i."".$my_j."".$my_k."".$my_l."".$my_m;
                    my_notify($note_text,$user_info);
                    header("location: edit_product?note=nice_update&cd=$my_dir_value&pn=$pagenum&s_type=$s_type&br=$br&search_text=$search_text");
                }else{
                    header("location: edit_product?note=error&cd=$my_dir_value&pn=$pagenum&s_type=$s_type&br=$br&search_text=$search_text");
                }
            }else if ($count_duplicate > 0){
                header("location: edit_product?note=duplicate&cd=$my_dir_value&pn=$pagenum&s_type=$s_type&br=$br&search_text=$search_text");
            }else{
                //insert to database
                $update_data=$link->query("UPDATE 
                                        gy_products 
                                        SET 
                                        gy_product_code='$my_code',
                                        gy_product_cat='$my_category',
                                        gy_product_color='$my_color',
                                        gy_product_image='$productImage',
                                        gy_supplier_code='$my_supplier',
                                        gy_product_name='$my_name',
                                        gy_product_desc='$my_desc',
                                        gy_product_unit='$my_unit',
                                        gy_product_price_srp='$my_price_srp',
                                        gy_product_quantity='$my_quantity',
                                        gy_product_discount_per='$my_limit',
                                        gy_product_restock_limit='$my_restock_limit',
                                        gy_product_update_date='$date_now' 
                                        Where 
                                        gy_product_id='$my_dir_value'");

                if ($update_data) {
                    //data here
                    $my_a = compare_update($my_product_code , $my_code , "Product Code");
                    $my_b = compare_update($my_product_name , $my_name , "Product Description");
                    $my_c = compare_update($my_product_cat , $my_category , "Product Category");
                    $my_d = compare_update($my_product_desc , $my_desc , "Product Details");
                    $my_e = compare_update($my_supplier_code , $my_supplier , "Supplier System ID");
                    $my_g = compare_update($my_product_price_srp , $my_price_srp , "Product SRP");
                    $my_h = compare_update($my_product_unit , $my_unit , "Product Unit");
                    $my_i = compare_update($my_product_quantity , $my_quantity , "Quantity");
                    $my_j = compare_update($my_product_discount_per , $my_limit , "Discount Limit");
                    $my_k = compare_update($my_product_restock_limit , $my_restock_limit , "Restock Limit");
                    $my_l = compare_update($my_product_color , $my_color , "Product Color");
                    $my_m = compare_update($my_product_image , $productImage , "Product Image");

                    $note_text = $my_product_name." Product Update -> ".$my_a."".$my_b."".$my_c."".$my_d."".$my_e."".$my_g."".$my_h."".$my_i."".$my_j."".$my_k."".$my_l."".$my_m;
                    my_notify($note_text,$user_info);
                    header("location: edit_product?note=nice_update&cd=$my_dir_value&pn=$pagenum&s_type=$s_type&br=$br&search_text=$search_text");
                }else{
                    header("location: edit_product?note=error&cd=$my_dir_value&pn=$pagenum&s_type=$s_type&br=$br&search_text=$search_text");
                }
            }

        }

        
    }
?>