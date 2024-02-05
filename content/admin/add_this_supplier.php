<?php  
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");
    
    //add member
    if (isset($_POST['auth_add_supplier'])) {
        //elements
        $my_name = words($_POST['my_name']);
        $my_desc = words($_POST['my_desc']);
        $my_address = words($_POST['my_address']);
        $my_contact = words($_POST['my_contact']);
        $date_now = date("Y-m-d H:i:s");

        //get supplier code
        $get_supp_code=$link->query("Select * From `gy_supplier` Order By `gy_supplier_code` DESC LIMIT 1");
        $supp_row=$get_supp_code->fetch_array();

        if ($supp_row['gy_supplier_code'] == 0) {
            $my_code = "10001";
        }else{
            $my_code = $supp_row['gy_supplier_code'] + 1;
        }

        //insert to database
        $insert_data=$link->query("Insert Into `gy_supplier`(`gy_supplier_code`, `gy_supplier_name`, `gy_supplier_desc`, `gy_supplier_address`, `gy_supplier_contact`, `gy_supplier_reg`) Values('$my_code','$my_name','$my_desc','$my_address','$my_contact','$date_now')");

        if ($insert_data) {    
            $my_note_text = $my_name." is added to suppliers";
            my_notify($my_note_text,$user_info);
            header("location: add_supplier?note=nice");
                    
        }else{
            header("location: add_supplier?note=error");
        }
    }
?>