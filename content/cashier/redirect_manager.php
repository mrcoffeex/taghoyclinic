<?php  
include("../../conf/conn.php");
include("../../conf/function.php");
include("session.php");
include("../../conf/my_project.php");

	$my_project_header_title = "Searching ...";	

	//search product
    if (isset($_POST['product_search_pull'])) {
        $search_product = words($_POST['product_search_pull']);

        if (ctype_space($search_product)) {
            header("location: search_product?search_text=mrcoffeex_only_space");
        }else if ($search_product == "0") {
            header("location: search_product?search_text=mrcoffeex_only_zero");
        }else if (search_product) {
            header("location: search_product?search_text=$search_product");
        }
    }

    //search transaction
    if (isset($_POST['search_trans'])) {
        $search_trans = words($_POST['search_trans']);

        if (ctype_space($search_trans)) {
            header("location: search_trans?search_text=mrcoffeex_only_space");
        }else if ($search_trans == "0") {
            header("location: search_trans?search_text=mrcoffeex_only_zero");
        }else if (search_trans) {
            header("location: search_trans?search_text=$search_trans");
        }
    }

    //search sales report
    if (isset($_POST['submit_sales_report_sales'])) {
        $search_value = words($_POST['search_value']);
        $search_return_date = words($_POST['search_return_date']);

        if ($search_value == "" && $search_return_date == "") {
            header("location: sales?note=empty_search");
        }else{
            header("location: search_sales?search_text=$search_value&returndate=$search_return_date");
        }
    }

    //search pullout summary by date
    if (isset($_POST['pullout_date_search'])) {
        $pullout_date_search = words($_POST['pullout_date_search']);

        if (ctype_space($pullout_date_search)) {
            header("location: search_pullout_reports?search_text=mrcoffeex_only_space");
        }else if ($pullout_date_search == "0") {
            header("location: search_pullout_reports?search_text=mrcoffeex_only_zero");
        }else if ($pullout_date_search) {
            header("location: search_pullout_reports?search_text=$pullout_date_search");
        }
    }

    //search pullout summary by entry
    if (isset($_POST['pullout_entry_search'])) {
        $pullout_entry_search = words($_POST['pullout_entry_search']);

        if (ctype_space($pullout_entry_search)) {
            header("location: search_pullout_entry_reports?search_text=mrcoffeex_only_space");
        }else if ($pullout_entry_search == "0") {
            header("location: search_pullout_entry_reports?search_text=mrcoffeex_only_zero");
        }else if ($pullout_entry_search) {
            header("location: search_pullout_entry_reports?search_text=$pullout_entry_search");
        }
    }

    //custom breakdown
    if (isset($_POST['overwrite'])) {
        $my_custom_date = words($_POST['my_custom_date']);
        $my_secure_pin = words($_POST['my_secure_pin']);

        $approved_info = by_pin_get_user($my_secure_pin, 'custom_breakdown');
        //get approved_by info
        $get_approved_by_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$approved_info'");
        $approved_row=$get_approved_by_info->fetch_array();

        //value here
        $approved_by = $approved_row['gy_full_name'];

        //get secure pin
        $get_secure_pin=$link->query("Select * From `gy_optimum_secure` Where `gy_sec_type`='custom_breakdown'");
        $get_values=$get_secure_pin->fetch_array();

        if (encryptIt($my_secure_pin) == $get_values['gy_sec_value']) {

            //redirect
            if ($my_custom_date == "0000-00-00") {
                $my_note_text = $approved_by." -> approved Custom Date Cash Breakdown";
                my_notify($my_note_text,$user_info);
                header("location: breakdown?note=empty_date");
            }else{
                header("location: custom_breakdown?search_text=$my_custom_date");
            }
            
        }else{
            header("location: breakdown?note=pin_out");
        }
    }

    //search sales
    if (isset($_POST['sales_search'])) {
        $sales_search = words($_POST['sales_search']);

        if (ctype_space($sales_search)) {
            header("location: search_receipt?search_text=mrcoffeex_only_space");
        }else if ($sales_search == "0") {
            header("location: search_receipt?search_text=mrcoffeex_only_zero");
        }else if ($sales_search) {
            header("location: search_receipt?search_text=$sales_search");
        }
    }

    //search sales by date
    if (isset($_POST['sales_date_search'])) {
        $sales_date_search = words($_POST['sales_date_search']);

        if (ctype_space($sales_date_search)) {
            header("location: search_receipt_date?search_text=mrcoffeex_only_space");
        }else if ($sales_date_search == "0") {
            header("location: search_receipt_date?search_text=mrcoffeex_only_zero");
        }else if ($sales_date_search) {
            header("location: search_receipt_date?search_text=$sales_date_search");
        }
    }

    //search patient
    if (isset($_POST['search_pat'])) {
        $search_pat = words($_POST['search_pat']);

        if (ctype_space($search_pat)) {
            header("location: patient?note=mrcoffeex_only_space");
        }else if ($search_pat == "0") {
            header("location: patient?note=mrcoffeex_only_zero");
        }else if ($search_pat) {
            header("location: search_patient?search_text=$search_pat");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $my_project_header_title; ?></title>
</head>
<body>
    <center><h1>Searching ...</h1></center>
</body>
</html>