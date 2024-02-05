<?php  
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");

    $my_dir_value = @$_GET['cd'];
    $my_mode = @$_GET['mode'];
    $my_multiplier = @$_GET['multiplier'];

    //get general transaction info
    $gettrans=$link->query("Select * From `gy_transaction` Where `gy_trans_code`='$my_dir_value'");
    $transrow=$gettrans->fetch_array();

    $cardcent=$transrow['gy_trans_cardcent'];

    function cardcentmultiplier($orig,$pers){
    	$getmutiplier = $pers / 100;
    	$midprocess = $orig * $getmutiplier;
    	$judgement = $orig + $midprocess;

    	return floor($judgement);
    }

    if ($my_dir_value != "") {
    	if ($my_mode == 0) {
    		//update to the original price and update the cardcent to 0
    		if ($cardcent == 0) {
    			//back to cashier
    			header("location: cashier?cd=$my_dir_value&mode=0");
    		}else{
    			//update the price to original and the cardpert

    			//identify items
    			$getitems=$link->query("Select * From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");
    			while ($itemrow=$getitems->fetch_array()) {
    				$origprice=words($itemrow['gy_product_origprice']);
    				$itemid=words($itemrow['gy_transdet_id']);
    				//update items
    				$updateitems=$link->query("Update `gy_trans_details` SET `gy_product_price`='$origprice' Where `gy_transdet_id`='$itemid'");
    			}

    			//update cardcent
    			$updatetrans=$link->query("Update `gy_transaction` SET `gy_trans_cardcent`='0' Where `gy_trans_code`='$my_dir_value'");

    			header("location: cashier?cd=$my_dir_value&note=price_updated&mode=0");
    			
    		}
    	}else if ($my_mode == 1) {
    		//update to the original price and update the cardcent to 0
    		if ($cardcent == 0) {
    			//back to cashier
    			header("location: cashier?cd=$my_dir_value&mode=1");
    		}else{
    			//update the price to original and the cardpert

    			//identify items
    			$getitems=$link->query("Select * From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");
    			while ($itemrow=$getitems->fetch_array()) {
    				$origprice=words($itemrow['gy_product_origprice']);
    				$itemid=words($itemrow['gy_transdet_id']);
    				//update items
    				$updateitems=$link->query("Update `gy_trans_details` SET `gy_product_price`='$origprice' Where `gy_transdet_id`='$itemid'");
    			}

    			//update cardcent
    			$updatetrans=$link->query("Update `gy_transaction` SET `gy_trans_cardcent`='0' Where `gy_trans_code`='$my_dir_value'");

    			header("location: cashier?cd=$my_dir_value&note=price_updated&mode=1");
    			
    		}
    	}else if ($my_mode == 2) {
    		//update to the original price and update the cardcent to 0
    		if ($cardcent == 0) {
    			//default by 4 percent raise
    			$getitems=$link->query("Select * From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");
    			while ($itemrow=$getitems->fetch_array()) {
    				$origprice=words(cardcentmultiplier($itemrow['gy_product_origprice'],4));
    				$itemid=words($itemrow['gy_transdet_id']);
    				//update items
    				$updateitems=$link->query("Update `gy_trans_details` SET `gy_product_price`='$origprice' Where `gy_transdet_id`='$itemid'");
    			}

    			//update cardcent
    			$updatetrans=$link->query("Update `gy_transaction` SET `gy_trans_cardcent`=4 Where `gy_trans_code`='$my_dir_value'");
    			header("location: cashier?cd=$my_dir_value&note=price_updated&mode=2&multiplier=4");
    		}else{
    			//update the price to original and the cardpert
    			$getitems=$link->query("Select * From `gy_trans_details` Where `gy_trans_code`='$my_dir_value'");
    			while ($itemrow=$getitems->fetch_array()) {
    				$origprice=words(cardcentmultiplier($itemrow['gy_product_origprice'],$my_multiplier));
    				$itemid=words($itemrow['gy_transdet_id']);
    				//update items
    				$updateitems=$link->query("Update `gy_trans_details` SET `gy_product_price`='$origprice' Where `gy_transdet_id`='$itemid'");
    			}

    			//update cardcent
    			$updatetrans=$link->query("Update `gy_transaction` SET `gy_trans_cardcent`='$my_multiplier' Where `gy_trans_code`='$my_dir_value'");
    			header("location: cashier?cd=$my_dir_value&note=price_updated&mode=2&multiplier=$my_multiplier");
    			
    		}
    	}
    }else{
    	header("location: cashier?cd=$my_dir_value&note=invalid_act&mode=0");
    }


?>