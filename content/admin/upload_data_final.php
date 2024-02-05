<?php  

		include("../../conf/conn.php");
	    include("../../conf/function.php");
	    include("session.php");
	    include("../../conf/my_project.php");

	  if (isset($_POST['upload'])) {

	    $file = $_FILES['my_file']['tmp_name'];

	    $handle = fopen($file, "r");

	    if ($file == NULL) {
	      echo "
	      	<script>
	      		window.alert('no file uploaded');
	      	</script>
	      ";
	    }else{
	    	
	      while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
	        {
	          $my_code = $filesop[0];
	          $my_name = $filesop[1];
	          $my_price_cap = $filesop[2];
	          $my_product_srp = $filesop[3];
	          $my_limit = $filesop[4];
	          $my_quantity = $filesop[5];
	          $restock = $filesop[6];     
	        
          $sql=$link->query("INSERT INTO `gy_products` SET
            `gy_product_code`='" . $my_code . "',
            `gy_product_name`='" . $my_name . "',
            `gy_product_price_cap`='" . $my_price_cap . "',
            `gy_product_price_srp`='" . $my_product_srp . "',
            `gy_product_quantity`='" . $my_quantity . "',
            `gy_product_discount_per`='" . $my_limit . "',
            `gy_product_restock_limit`='" . $restock . "'");
	        
	      }

	      if ($sql) {
	        echo "
	        	<script>
	        		window.alert('product transfered!');
	        	</script>
	        ";
	      } else {
	        echo "
	        	<script>
	        		window.alert('error!');
	        	</script>
	        ";
	        }
	    }
	  }
	//form_submit($name, $label) Renders the submit button of a form
	//form_file($name, $label) Renders a form file box

	 // return page_with_title("Import Data", array(
	 //   msg(),
	 //  div('row', array(
	 //          div('col-md-12', array(
	 //              form(array(
	 //                form_file('csv_file', _("Import user data from a csv file")),
	 //                form_submit('upload', _("Import"))
	 //              ))
	 //          ))
	 //      ))
	 //  ));
?>