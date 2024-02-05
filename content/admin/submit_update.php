<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    if (isset($_POST['import'])) {

    	$myfile = $_FILES['database']['name'];
    	$mytemp = $_FILES['database']['tmp_name'];

		$emptytable=$link->query("TRUNCATE TABLE `gy_update`");
    	
    	if ($myfile != '') {
    		$array = explode(".", $myfile);
    		$extension = end($array);

    		if ($extension == 'sql') {
    			$output = "";
    			$count = 0;
    			$file_data = file($mytemp); 

    			foreach ($file_data as $row) {
    				$start_char = substr(trim($row), 0, 2);

    				if ($start_char != '--' || $start_char != '/*' || $start_char != '//' || $row != '' ) {
    					$output = $output . $row;
    					$end_char = substr(trim($row), -1, 1);

    					if ($end_char == ';') {

    						if (!$link->query($output)) {
    							$count++;
    						}

    						$output = '';
    					}
    				}
    			}
                
                $deletezero=$link->query("DELETE FROM `gy_update` Where `gy_product_code`=''");

                if ($count > 0) {

                    $updatedata=$link->query("UPDATE gy_products a, gy_update b SET a.gy_product_code=b.gy_product_code, a.gy_product_name=b.gy_product_name,a.gy_product_cat=b.gy_product_cat,a.gy_product_desc=b.gy_product_desc,a.gy_product_unit=b.gy_product_unit,a.gy_product_price_cap=b.gy_product_price_cap,a.gy_product_price_srp=b.gy_product_price_srp,a.gy_product_discount_per=b.gy_product_discount_per,a.gy_product_restock_limit=b.gy_product_restock_limit,b.gy_update_status=1 WHERE a.gy_update_code=b.gy_update_code");

                    if ($updatedata) {

                        header("location: update_item?note=success");
                    }else{
                        header("location: update_item?note=error");
                    }
                    
                }else{
                    header("location: update_item?note=error");
                }

    		}else{
    			header("location: update_item?note=invalid");
    		}
    	}else{
    		header("location: update_item?note=empty");
    	}
    }

    if (isset($_POST['my_secure_pin'])) {

        $my_secure_pin = $_POST['my_secure_pin'];

        if ($my_secure_pin == "00000") {

            $getitems=$link->query("SELECT * From `gy_update` Where `gy_update_status`='0'");
            $countitems=$getitems->num_rows;

            if ($countitems == 0) {
                header("location: update_item?note=success");
            }else{
                while ($itemrow=$getitems->fetch_array()) {
                    //insert new records
                    $mycode = words($itemrow['gy_product_code']);
                    $myname = words($itemrow['gy_product_name']); 
                    $mycat = words($itemrow['gy_product_cat']);
                    $mydesc = words($itemrow['gy_product_desc']);
                    $myunit = words($itemrow['gy_product_unit']);
                    $mycap = words($itemrow['gy_product_price_cap']);
                    $mysrp = words($itemrow['gy_product_price_srp']);
                    $myper = words($itemrow['gy_product_discount_per']);
                    $mylimit = words($itemrow['gy_product_restock_limit']);
                    $myrestock = words($itemrow['gy_product_date_restock']);
                    $myreg = words($itemrow['gy_product_date_reg']);
                    $updatecode = words($itemrow['gy_update_code']);

                    $insertdata=$link->query("INSERT INTO `gy_products`(`gy_product_code`, `gy_product_name`, `gy_product_cat`, `gy_product_desc`, `gy_product_unit`, `gy_product_price_cap`, `gy_product_price_srp`, `gy_product_quantity`, `gy_product_discount_per`, `gy_product_restock_limit`, `gy_added_by`, `gy_product_date_restock`, `gy_product_date_reg`, `gy_update_code`) Values('$mycode','$myname','$mycat','$mydesc','$myunit','$mycap','$mysrp','0','$myper','$mylimit','$user_id','$myrestock','$myreg','$updatecode')");
                }

                if ($insertdata) {

                    $delete=$link->query("DELETE FROM `gy_update`");

                    header("location: update_item?note=success");
                }else{
                    header("location: update_item?note=error");
                }
            }
            
            
        }else{
            header("location: update_item?note=pin");
        }
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <title>Updating Data ...</title>
</head>
<body>
<div id="div">
    <h1>Please Wait..</h1>
</div>
</body>
</html>