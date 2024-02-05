<?php  
include("../../conf/conn.php");
include("../../conf/function.php");
include("session.php");
include("../../conf/my_project.php");

	$my_project_header_title = "Searching ...";	

	//search product
    if (isset($_POST['product_search'])) {
        $branch_product = words($_POST['branch_product']);
        $search_product = words($_POST['product_search']);

        if (ctype_space($search_product)) {
            header("location: search_product?br=$branch_product&search_text=mrcoffeex_only_space");
        }else if ($search_product == "0") {
            header("location: search_product?br=$branch_product&search_text=mrcoffeex_only_zero");
        }else{
            header("location: search_product?br=$branch_product&search_text=$search_product");
        }
    }

    //search transaction
    if (isset($_POST['search_trans'])) {
        $search_trans = words($_POST['search_trans']);

        if (ctype_space($search_trans)) {
            header("location: search_trans?search_text=mrcoffeex_only_space");
        }else if ($search_trans == "0") {
            header("location: search_trans?search_text=mrcoffeex_only_zero");
        }else if ($search_trans) {
            header("location: search_trans?search_text=$search_trans");
        }
    }

    //search suppliers
    if (isset($_POST['supplier_search'])) {
        $supplier_search = words($_POST['supplier_search']);

        if (ctype_space($supplier_search)) {
            header("location: search_suppliers?search_text=mrcoffeex_only_space");
        }else if ($supplier_search == "0") {
            header("location: search_suppliers?search_text=mrcoffeex_only_zero");
        }else if ($supplier_search) {
            header("location: search_suppliers?search_text=$supplier_search");
        }
    }

    //search sales
    if (isset($_POST['sales_search'])) {
        $sales_search = words($_POST['sales_search']);

        if (ctype_space($sales_search)) {
            header("location: search_sales?search_text=mrcoffeex_only_space");
        }else if ($sales_search == "0") {
            header("location: search_sales?search_text=mrcoffeex_only_zero");
        }else if ($sales_search) {
            header("location: search_sales?search_text=$sales_search");
        }
    }

    //search sales by date
    if (isset($_POST['sales_btn'])) {
        $sales_date_search_f = words($_POST['sales_date_search_f']);
        $sales_date_search_t = words($_POST['sales_date_search_t']);

        if ($sales_date_search_f == "" || $sales_date_search_t == "") {
            header("location: sales?note=empty_search");
        }else{
            header("location: search_sales_date?datef=$sales_date_search_f&datet=$sales_date_search_t");
        }
    }

    //search void sales
    if (isset($_POST['void_search'])) {
        $void_search = words($_POST['void_search']);

        if (ctype_space($void_search)) {
            header("location: search_void_trans?search_text=mrcoffeex_only_space");
        }else if ($void_search == "0") {
            header("location: search_void_trans?search_text=mrcoffeex_only_zero");
        }else if ($void_search) {
            header("location: search_void_trans?search_text=$void_search");
        }
    }

    //search void sales by date
    if (isset($_POST['void_btn'])) {
        $void_date_search_f = words($_POST['void_date_search_f']);
        $void_date_search_t = words($_POST['void_date_search_t']);

        if ($void_date_search_f == "" || $void_date_search_t == "") {
            header("location: void_trans?note=empty_search");
        }else{
            header("location: search_void_trans_date?datef=$void_date_search_f&datet=$void_date_search_t");
        }
    }

    //search sales report
    if (isset($_POST['submit_sales_report_sales'])) {
        $my_cashier = words($_POST['my_cashier']);
        $my_date_report_f = words($_POST['my_date_report_f']);
        $my_date_report_t = words($_POST['my_date_report_t']);
        $sales_condition = words($_POST['condition']);

        if ($my_cashier == "" || $my_date_report_f == "" || $my_date_report_t == "") {
            header("location: sales_report?note=empty_search");
        }else{
            header("location: search_sales_report?cd=$my_cashier&datef=$my_date_report_f&datet=$my_date_report_t&condition=$sales_condition");
        }
    }

    //search note custom
    if (isset($_POST['submit_notif_condition'])) {
        $my_condition = words($_POST['my_condition']);
        $my_date_from = words($_POST['my_date_from']);
        $my_date_to = words($_POST['my_date_to']);

        if ($my_condition == "" && $my_date_from == "" && $my_date_to == "") {
            echo "
                <script>
                    window.alert('Empty Search!');
                    window.location.href = 'notification'
                </script>
             ";
        }else{
            header("location: search_note_custom?condition=$my_condition&date_from=$my_date_from&date_to=$my_date_to");
        }
    }

    //search refund/replace date search
    if (isset($_POST['refund_btn'])) {
        $re_date_search_f = words($_POST['re_date_search_f']);
        $re_date_search_t = words($_POST['re_date_search_t']);

        if ($re_date_search_f == "" || $re_date_search_t == "") {
            header("location: refund_replace_alerts?note=empty_search");
        }else{
            header("location: refund_replace_alerts_date?datef=$re_date_search_f&datet=$re_date_search_t");
        }
    }

    //search expenses date
    if (isset($_POST['searchstock_exp'])) {
        $exp_date_f = words($_POST['exp_date_f']);
        $exp_date_t = words($_POST['exp_date_t']);

        if ($exp_date_f == "" || $exp_date_t == "") {
            header("location: expenses?note=empty_search");
        }else{
            header("location: search_exp?datef=$exp_date_f&datet=$exp_date_t");
        }
    }

    //search expenses cash date
    if (isset($_POST['search_exp'])) {
        $exp_cash_date_f = words($_POST['exp_cash_date_f']);
        $exp_cash_date_t = words($_POST['exp_cash_date_t']);

        if ($exp_cash_date_f == "" || $exp_cash_date_t == "") {
            header("location: expenses_cash?note=empty_search");
        }else{
            header("location: search_exp_cash?datef=$exp_cash_date_f&datet=$exp_cash_date_t");
        }
    }

    //search restock
    if (isset($_POST['restock_search'])) {
        $restock_search = words($_POST['restock_search']);

        if (ctype_space($restock_search)) {
            header("location: search_restock_alerts?search_text=mrcoffeex_only_space");
        }else if ($restock_search == "0") {
            header("location: search_restock_alerts?search_text=mrcoffeex_only_zero");
        }else if ($restock_search) {
            header("location: search_restock_alerts?search_text=$restock_search");
        }
    }

    //search restock alerts by category
    if (isset($_POST['restock_search_cat'])) {
        $restock_search_cat = words($_POST['restock_search_cat']);

        if (ctype_space($restock_search_cat)) {
            header("location: search_restock_alerts_cat?search_text=mrcoffeex_only_space");
        }else if ($restock_search_cat == "0") {
            header("location: search_restock_alerts_cat?search_text=mrcoffeex_only_zero");
        }else if ($restock_search_cat) {
            header("location: search_restock_alerts_cat?search_text=$restock_search_cat");
        }
    }

    //search stock receive summary by date
    if (isset($_POST['restock_btn'])) {
        $restock_date_search_f = words($_POST['restock_date_search_f']);
        $restock_date_search_t = words($_POST['restock_date_search_t']);

        if ($restock_date_search_f == "" || $restock_date_search_t == "") {
            header("location: restock_reports?note=empty_search");
        }else{
            header("location: search_restock_reports?datef=$restock_date_search_f&datet=$restock_date_search_t");
        }
    }

    //search stock receive summary by entry
    if (isset($_POST['restock_entry_search'])) {
        $restock_entry_search = words($_POST['restock_entry_search']);

        if (ctype_space($restock_entry_search)) {
            header("location: search_restock_entry_reports?search_text=mrcoffeex_only_space");
        }else if ($restock_entry_search == "0") {
            header("location: search_restock_entry_reports?search_text=mrcoffeex_only_zero");
        }else if ($restock_entry_search) {
            header("location: search_restock_entry_reports?search_text=$restock_entry_search");
        }
    }

    //search pullout summary by date
    if (isset($_POST['pullout_btn'])) {
        $pullout_date_search_f = words($_POST['pullout_date_search_f']);
        $pullout_date_search_t = words($_POST['pullout_date_search_t']);

        if ($pullout_date_search_f == "" || $pullout_date_search_t == "") {
            header("location: pullout_reports?note=empty_search");
        }else{
            header("location: search_pullout_reports?datef=$pullout_date_search_f&datet=$pullout_date_search_t");
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

    //search transfer summary by date
    if (isset($_POST['transfer_btn'])) {
        $transfer_date_search_f = words($_POST['transfer_date_search_f']);
        $transfer_date_search_t = words($_POST['transfer_date_search_t']);
        $transfer_branch_search = words($_POST['transfer_branch_search']);

        if ($transfer_date_search == "" && $transfer_date_search_t == "") {
            header("location: transfer_reports?note=empty_search");
        }else{
            header("location: search_transfer_reports?my_branch=$transfer_branch_search&datef=$transfer_date_search_f&datet=$transfer_date_search_t");
        }
    }

    //search transfer summary by entry
    if (isset($_POST['transfer_entry_search'])) {
        $transfer_entry_search = words($_POST['transfer_entry_search']);

        if (ctype_space($transfer_entry_search)) {
            header("location: search_transfer_entry_reports?search_text=mrcoffeex_only_space");
        }else if ($transfer_entry_search == "0") {
            header("location: search_transfer_entry_reports?search_text=mrcoffeex_only_zero");
        }else if ($transfer_entry_search) {
            header("location: search_transfer_entry_reports?search_text=$transfer_entry_search");
        }
    }

    //search tra summary by date
    if (isset($_POST['tra_date_search'])) {
        $tra_date_search = words($_POST['tra_date_search']);

        if (ctype_space($tra_date_search)) {
            header("location: search_tra_reports?search_text=mrcoffeex_only_space");
        }else if ($tra_date_search == "0") {
            header("location: search_tra_reports?search_text=mrcoffeex_only_zero");
        }else if ($tra_date_search) {
            header("location: search_tra_reports?search_text=$tra_date_search");
        }
    }

    //search tra summary by entry
    if (isset($_POST['tra_entry_search'])) {
        $tra_entry_search = words($_POST['tra_entry_search']);

        if (ctype_space($tra_entry_search)) {
            header("location: search_tra_entry_reports?search_text=mrcoffeex_only_space");
        }else if ($tra_entry_search == "0") {
            header("location: search_tra_entry_reports?search_text=mrcoffeex_only_zero");
        }else if ($tra_entry_search) {
            header("location: search_tra_entry_reports?search_text=$tra_entry_search");
        }
    }

    //search transfer summary by branch
    // if (isset($_POST['transfer_branch_search'])) {
    //     $transfer_branch_search = words($_POST['transfer_branch_search']);

    //     if (ctype_space($transfer_branch_search)) {
    //         header("location: search_transfer_branch_reports?search_text=mrcoffeex_only_space");
    //     }else if ($transfer_branch_search == "0") {
    //         header("location: search_transfer_branch_reports?search_text=mrcoffeex_only_zero");
    //     }else if ($transfer_branch_search) {
    //         header("location: search_transfer_branch_reports?search_text=$transfer_branch_search");
    //     }
    // }

    //search back-order summary by date
    if (isset($_POST['backorder_date_search'])) {
        $backorder_date_search = words($_POST['backorder_date_search']);

        if (ctype_space($backorder_date_search)) {
            header("location: search_back_order_reports?search_text=mrcoffeex_only_space");
        }else if ($backorder_date_search == "0") {
            header("location: search_back_order_reports?search_text=mrcoffeex_only_zero");
        }else if ($backorder_date_search) {
            header("location: search_back_order_reports?search_text=$backorder_date_search");
        }
    }

    //search back-order summary by entry
    if (isset($_POST['backorder_entry_search'])) {
        $backorder_entry_search = words($_POST['backorder_entry_search']);

        if (ctype_space($backorder_entry_search)) {
            header("location: search_back_order_entry_reports?search_text=mrcoffeex_only_space");
        }else if ($backorder_entry_search == "0") {
            header("location: search_back_order_entry_reports?search_text=mrcoffeex_only_zero");
        }else if ($backorder_entry_search) {
            header("location: search_back_order_entry_reports?search_text=$backorder_entry_search");
        }
    }

    if (isset($_POST['notif_search'])) {
        $notif_search = words($_POST['notif_search']);

        if (ctype_space($notif_search)) {
            header("location: search_note?search_text=mrcoffeex_only_space");
        }else if ($notif_search == "0") {
            header("location: search_note?search_text=mrcoffeex_only_zero");
        }else if ($notif_search) {
            header("location: search_note?search_text=$notif_search");
        }
    }

    //print masterlist
    if (isset($_POST['my_cat'])) {
        $my_cat = words($_POST['my_cat']);

        if (ctype_space($my_cat)) {
            header("location: masterlist?search_text=mrcoffeex_only_space");
        }else if ($my_cat == "0") {
            header("location: masterlist?search_text=mrcoffeex_only_zero");
        }else if ($my_cat) {
            header("location: masterlist?search_text=$my_cat");
        }
    }

    //search deposit
    if (isset($_POST['dep_submit'])) {
        $dep_from = words($_POST['dep_from']);
        $dep_to = words($_POST['dep_to']);

        if ($dep_from == "" || $dep_to == "") {
            header("location: deposit?cd=empty");
        }else{
            header("location: search_deposit_dates?datef=$dep_from&datet=$dep_to");
        }
    }

    if (isset($_POST['search_dep'])) {
        $search_dep = words($_POST['search_dep']);

        if (ctype_space($search_dep)) {
            header("location: search_deposit?search_text=mrcoffeex_only_space");
        }else if ($search_dep == "0") {
            header("location: search_deposit?search_text=mrcoffeex_only_zero");
        }else if ($search_dep) {
            header("location: search_deposit?search_text=$search_dep");
        }
    }

    //search deposit
    if (isset($_POST['search_master'])) {
        $search_master = words($_POST['search_master']);

        if (ctype_space($search_master)) {
            header("location: search_master?search_text=mrcoffeex_only_space");
        }else if ($search_master == "0") {
            header("location: search_master?search_text=mrcoffeex_only_zero");
        }else if ($search_master) {
            header("location: search_master?search_text=$search_master");
        }
    }

    //search request order
    if (isset($_POST['request_btn'])) {
        $rqt_from = words($_POST['request_date_search_f']);
        $rqt_to = words($_POST['request_date_search_t']);

        if ($rqt_from == "" || $rqt_to == "") {
            header("location: request_reports?cd=empty_search");
        }else{
            header("location: search_request_reports?datef=$rqt_from&datet=$rqt_to");
        }
    }

    //search deleted item
    if (isset($_POST['deleteitem_btn'])) {
        $d_from = words($_POST['delete_date_search_f']);
        $d_to = words($_POST['delete_date_search_t']);

        if ($d_from == "" || $d_to == "") {
            header("location: deleted_item?cd=empty_search");
        }else{
            header("location: search_delete_item?datef=$d_from&datet=$d_to");
        }
    }

    //search album
    if (isset($_POST['image_search'])) {
        $branch_product = words($_POST['branch_product']);
        $image_search = words($_POST['image_search']);

        if (ctype_space($image_search)) {
            header("location: albumSearch?br=$branch_product&search_text=mrcoffeex_only_space");
        }else if ($image_search == "0") {
            header("location: albumSearch?br=$branch_product&search_text=mrcoffeex_only_zero");
        }else{
            header("location: albumSearch?br=$branch_product&search_text=$image_search");
        }
    }

    //search sold
    if (isset($_POST['soldBtn'])) {
        $soldFrom = words($_POST['soldFrom']);
        $soldTo = words($_POST['soldTo']);

        if (empty($soldFrom) || empty($soldTo)) {
            header("location: sold?cd=empty_search");
        }else{
            header("location: soldSearch?datef=$soldFrom&datet=$soldTo");
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