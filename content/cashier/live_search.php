<?php
 
	include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
 
	$product_search=$_GET['product_search'];

	if ($product_search == "") {
		echo "<option></option>";
	}else{
		$res=$link->query("SELECT * FROM gy_products WHERE gy_branch_id='$user_branch_id' AND CONCAT(gy_product_name,gy_product_code,gy_product_color) like '%$product_search%' ORDER BY gy_product_name ASC LIMIT 30");
	 	$count=$res->num_rows;

		if(!$res){
			echo mysqli_error($db);
		}else if ($count == 0) {
			echo "<option value='item not found'></option>";
		}else{
			while($row=$res->fetch_array()){
				echo "<option value='".$row['gy_product_code']."'>".$row['gy_product_name'];
			}
		}
	}
 
	
 
?>
</option>
