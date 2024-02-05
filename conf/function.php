<?php  
	function encryptIt( $q ) {
	    $cryptKey  = 'Helper4webcall:9997772595';
	    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );

	}

	function decryptIt( $q ) {
	    $cryptKey  = 'Helper4webcall:9997772595';
	    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	    return( $qDecoded );
	}

	function words($value){

		include("conn.php");
		
		$not_fake = mysqli_real_escape_string($link , $value);

		return $not_fake;
	} 

    function stringLimit($name, $limit){

        if (strlen($name) > $limit){
            $name = substr($name, 0, $limit) . '...';
        }else{
            $name = $name;
        }

        return $name;
    }

    function proper_date($datetime){

        if ($datetime == "") {
            $res = "";
        }else{
            $res = date("Md Y", strtotime($datetime));
        }

        return $res;

    }

    function proper_time($datetime){

        if ($datetime == "") {
            $res = "";
        }else{
            $res = date("g:i A", strtotime($datetime));
        }

        return $res;

    }

	function get_curr_age($birthday){
        //values
        $date_now = strtotime(date("Y-m-d"));
        $value = strtotime($birthday);

        //subtract in seconds
        $date_diff = $date_now-$value;
        //convert in days
        $days = $date_diff / 86400;
        //convert in years
        $years = $days / 365.25;

        //result
        $result = floor($years);

        return $result;
    }

    function get_year_two_param($before, $later){
        //values
        $value_one = strtotime($later);
        $value_two = strtotime($before);

        //subtract in seconds
        $date_diff = $value_one-$value_two;
        //convert in days
        $days = $date_diff / 86400;
        //convert in years
        $years = $days / 365.25;

        //result
        $result = floor($years);

        return $result;
    }

    function get_timeage($basetime, $currenttime){
        $secs = $currenttime - $basetime;
        $days = $secs / 86400;

        if ($days < 1 ) {
            $age = 1;
        }else{
            $age = 1 + $days;
        }

        //classify weather day, month or year
        if ($age < 30.5) {
            $creditage = floor($age)." day(s)";
        }else if ($age >= 30.5 && $age < 365.25) {
            $creditage = floor(($age / 30.5))." month(s)";
        }else{
            $creditage = floor(($age / 265.25))." year(s)";
        }

        return $creditage;
    }

    function displayImage($image, $default, $directory){

        if (empty($image)) {
            $res = $default;
        }else{
            $res = $directory . "" . $image;
        }

        return $res;

    }

    function imageUpload($input, $location){

        $errors= array();
        $file_name = $_FILES[$input]['name'];

        if (empty($file_name)) {
            $res = "";
        } else {
            $file_size =$_FILES[$input]['size'];
            $file_tmp =$_FILES[$input]['tmp_name'];
            $file_type=$_FILES[$input]['type'];
            $file_extension = pathinfo($_FILES[$input]['name'], PATHINFO_EXTENSION);

            $final_filename = date("YmdHis")."_".$file_name;

            $extensions= array("jpeg","jpg","png","jfif");

            if(in_array($file_extension, $extensions)=== false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }

            if($file_size > 26000000){
                $errors[]='File size must be excately 25 MB';
            }

            $file_directory = $location."".$final_filename;

            if(empty($errors)==true){

                move_uploaded_file($file_tmp, $file_directory);
                $res = $final_filename;

            }else{

                if ($file_tmp == "") {
                    $res = "";
                }else{
                    $res = "error";
                }

            }
        }

        return $res;

    }

    function get_status($stat_val){
    	if ($stat_val == 1) {
    		$your_stat_val = "Member";
    	}else{
    		$your_stat_val = "Non-Member";
    	}

    	return $your_stat_val;
    }

    function my_notify($note_text,$user){

    	include("conn.php");

    	$note_now = date("Y-m-d H:i:s");
    	$my_notification_full = $note_text." by ".$user;
    	
    	//insert to database
    	$insert_data=$link->query("Insert Into `gy_notification`(`gy_notif_text`,`gy_notif_date`) Values('$my_notification_full','$note_now')");
    }

    function by_pin_get_user($my_pin, $my_type){

        include("conn.php");

        $my_en_pin = words(encryptIt($my_pin));
        
        //get the user id from 
        $get_id=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_value`='$my_en_pin' AND `gy_sec_type`='$my_type'");
        $get_pin_row=$get_id->fetch_array();

        $my_user_info_id = $get_pin_row['gy_user_id'];

        return $my_user_info_id;
    }

    function get_days($fromdate, $todate) {
        $fromdate = \DateTime::createFromFormat('Y-m-d', $fromdate);
        $todate = \DateTime::createFromFormat('Y-m-d', $todate);
        return new \DatePeriod(
            $fromdate,
            new \DateInterval('P1D'),
            $todate->modify('+1 day')
        );
    }

    // $datePeriod = get_days_in_two_dates($date1, $date2);
    // foreach($datePeriod as $date) {
    //     echo $date->format('d'), PHP_EOL;
    // }

    function data_verify($my_ver_data){
        if ($my_ver_data == "") {
            $my_ver_data_value = "No Data";
        }else{
            $my_ver_data_value = $my_ver_data;
        }

        return $my_ver_data_value;
    }

    function my_rand_str( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";   

        $str="";
        
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    function my_rand_int( $length ) {
        $chars = "0123456789";   

        $str="";
        
        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    function toAlpha($number){
        
        $alphabet = array('N', 'S', 'T', 'A', 'R', 'G', 'O', 'L', 'D', 'E');

        $count = count($alphabet);
        if ($number == 10){
            $alpha = "SN";
        } else if ($number <= $count) {
            return $alphabet[$number - 0];
        }
        $alpha = '';
        while ($number > 0) {
            $modulo = ($number - 0) % $count;
            $alpha  = $alphabet[$modulo] . $alpha;
            $number = floor((($number - $modulo) / $count));
        }
        return $alpha;
    }

    function latest_code($ltable, $lcolumn, $lfirstcount){

        include("conn.php");

        $getlatest=$link->query("SELECT `".$lcolumn."` FROM `".$ltable."` ORDER BY `".$lcolumn."` DESC LIMIT 1");
        $latestrow=$getlatest->fetch_array();
        $countl=$getlatest->num_rows;

        if ($countl == 0) {
            $mylatestcode = $lfirstcount;
        }else{
            $mylatestcode = $latestrow[$lcolumn] + 1;
        }

        return $mylatestcode;
    }

    function get_branch_name($branch_id){

        include("conn.php");

        $statement=$link->query("SELECT `gy_branch_name` From `gy_branch` Where `gy_branch_id`='$branch_id'");
        $row=$statement->fetch_array();
        $count=$statement->num_rows;

        if ($count > 0) {
            $result = $row['gy_branch_name'];
        }else{
            $result = "All";
        }

        return $result;
    }

    // categories

    function selectCategories(){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_category 
                                Order By 
                                gy_cat_id 
                                ASC");
        
        return $statement;

    }

    // products

    function countAlbum($limit){

        include 'conn.php';

        if (count($limit)) {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                gy_product_quantity != 0
                                Order By 
                                gy_product_code 
                                ASC");
        } else {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                gy_product_quantity != 0
                                Order By 
                                gy_product_code 
                                ASC
                                LIMIT $limit");
        }

        $count=$statement->num_rows;
        
        return $count;

    }

    function selectAlbum($limit){

        include 'conn.php';

        $statement=$link->query("SELECT 
                            * 
                            From 
                            gy_products 
                            Where
                            gy_product_quantity != 0
                            Order By 
                            gy_product_code 
                            ASC
                            LIMIT $limit");
        
        return $statement;

    }

    function countAlbumSearch($search_text, $limit){

        include 'conn.php';

        if (count($limit)) {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC");
        } else {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC
                                LIMIT $limit");
        }

        $count=$statement->num_rows;
        
        return $count;

    }

    function selectAlbumSearch($search_text, $limit){

        include 'conn.php';

        if (count($limit)) {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC");
        } else {
            $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_products 
                                Where
                                CONCAT
                                (
                                    gy_product_code,
                                    gy_product_name,
                                    gy_product_desc,
                                    gy_product_color,
                                    gy_product_cat
                                )
                                LIKE
                                '%$search_text%'
                                Order By 
                                gy_product_code 
                                ASC
                                LIMIT $limit");
        }
        
        return $statement;

    }

    function updateProductImage($image, $productId){

        include 'conn.php';

        $statement=$link->query("UPDATE
                                    gy_products
                                    SET
                                    gy_product_image = '$image'
                                    Where
                                    gy_product_id = '$productId'");
        if ($statement) {
            return true;
        } else {
            return false;
        }

    }

    function getProductCode($productId){

        include 'conn.php';

        $statement=$link->query("SELECT
                                gy_product_code
                                FROM
                                gy_products
                                Where
                                gy_product_id = '$productId'");
        $res=$statement->fetch_array();

        return $res['gy_product_code'];

    }

    // transactions

    function selectItemsSold($date1, $date2){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                DISTINCT(gy_trans_details.gy_product_id) as item_sold, gy_product_name, gy_product_cat, gy_product_color, gy_product_unit
                                FROM
                                gy_trans_details
                                LEFT JOIN
                                gy_products
                                ON
                                gy_trans_details.gy_product_id = gy_products.gy_product_id
                                Where
                                gy_transdet_date
                                BETWEEN
                                '$date1' AND '$date2'
                                Order By
                                gy_products.gy_product_name
                                ASC");
        return $statement;

    }

    function getItemSoldQty($date1, $date2, $productId){

        include 'conn.php';

        $statement=$link->query("SELECT
                                SUM(gy_trans_quantity) as total_sold
                                FROM
                                gy_trans_details
                                Where
                                gy_product_id = '$productId'
                                AND
                                gy_transdet_date
                                BETWEEN
                                '$date1' AND '$date2'");
        $res=$statement->fetch_array();

        return $res['total_sold'];

    }

    // sales

    function getSalesStats($date1, $date2){

        include 'conn.php';

        $statement=$link->query("SELECT DATE_FORMAT(date(gy_trans_date), '%b %e, %Y') as sales_date, SUM(gy_trans_total) AS sales
                                FROM gy_transaction
                                WHERE 
                                gy_user_id != 0 AND
                                date(gy_trans_date) 
                                BETWEEN
                                '$date1' AND '$date2' 
                                GROUP BY date(gy_trans_date)");

        return $statement;

    }

    function getLatestDate(){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                date(gy_trans_date) as latest_date 
                                From 
                                gy_transaction 
                                WHERE 
                                gy_user_id != 0
                                Order By
                                gy_trans_date
                                DESC
                                LIMIT 1");
        $res=$statement->fetch_array();

        return $res['latest_date'];

    }

    //expenses

    function selectExpenses($date1, $date2, $userId){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                * 
                                From 
                                gy_expenses 
                                WHERE 
                                gy_user_id = '$userId'
                                AND
                                date(gy_exp_date) 
                                BETWEEN
                                '$date1' AND '$date2'
                                Order By
                                gy_exp_date
                                ASC");

        return $statement;

    }

    function getTotalExpenses($date1, $date2, $userId){

        include 'conn.php';

        $statement=$link->query("SELECT 
                                SUM(gy_exp_amount) as total_exp 
                                From 
                                gy_expenses 
                                WHERE 
                                gy_user_id = '$userId'
                                AND
                                date(gy_exp_date) 
                                BETWEEN
                                '$date1' AND '$date2'");
        $res=$statement->fetch_array();

        return $res['total_exp'];

    }
    
?>