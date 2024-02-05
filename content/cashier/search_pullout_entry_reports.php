<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("session.php");
    include("../../conf/my_project.php");

    $search_text = @$_GET['search_text'];

    if ($search_text == "mrcoffeex_only_space") {
         echo "
            <script>
                window.alert('White Spaces is not allowed!');
                window.location.href = 'pullout_reports'
            </script>
         ";
    }else if ($search_text == "mrcoffeex_only_zero") {
        echo "
            <script>
                window.alert('Only Zero is not allowed!');
                window.location.href = 'pullout_reports'
            </script>
         ";
    }else{

        $my_project_header_title = "Pull-Out Summary Report Search: <span style='color: blue;'>".$search_text."</span>";

        $query_one = "Select * From `gy_pullout` Where `gy_pullout_code` LIKE '%$search_text%' AND `gy_pullout_by`='$user_id' Order By `gy_pullout_date` DESC";

        $query_two = "Select COUNT(`gy_pullout_id`) From `gy_pullout` Where `gy_pullout_code` LIKE '%$search_text%' AND `gy_pullout_by`='$user_id' Order By `gy_pullout_date` DESC";

        $query_three = "Select * From `gy_pullout` Where `gy_pullout_code` LIKE '%$search_text%' AND `gy_pullout_by`='$user_id' Order By `gy_pullout_date` DESC ";

        $my_num_rows = 50;

        include 'my_pagination_search.php';

        $count_results=$link->query($query_one)->num_rows;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>
<body>

    <div id="wrapper">

        <?php include 'nav.php'; ?>

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-search"></i> <?php echo $my_project_header_title; ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" action="redirect_manager">
                                    <input type="text" class="form-control" placeholder="Search for Pull-Out Code ..." name="pullout_entry_search" style="border-radius: 0px;" autofocus required>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <form method="post" enctype="multipart/form-data" id="my_form" action="redirect_manager">
                                    <input type="date" class="form-control" name="pullout_date_search" id="pullout_date_search" style="border-radius: 0px;" required>
                                </form>
                            </div>
                        </div>                      
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Pull-Out Data Table <span style="color: green;">FOR USE</span> / <span style="color: red;">BACK ORDER</span> / <span style="color: #009fb1;">DAMAGED ITEM</span> / <span style="color: orange;">TRA</span> <b><?php echo 0+$count_results; ?></b> result(s)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><center>Date</center></th>
                                            <th style="color: blue;"><center>Code</center></th>
                                            <th><center>Type</center></th>
                                            <th><center>Product Code</center></th>
                                            <th><center>Description</center></th>
                                            <th><center>Quantity</center></th>
                                            <th><center>Note</center></th>
                                            <th><center>User</center></th>
                                            <!-- <th><center>Void</center></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  
                                        //get products
                                        //make pagination
                                        while ($pull_row=$query->fetch_array()) {
                                            //get product details
                                            $my_code = words($pull_row['gy_product_code']);
                                            $get_details=$link->query("Select * From `gy_products` Where `gy_product_code`='$my_code'");
                                            $details_row=$get_details->fetch_array();

                                            //get user info
                                            $my_user = words($pull_row['gy_pullout_by']);
                                            $get_my_user_info=$link->query("Select * From `gy_user` Where `gy_user_id`='$my_user'");
                                            $my_user_row=$get_my_user_info->fetch_array();

                                            if ($pull_row['gy_pullout_type'] == "FOR_USE") {
                                                $my_type_pull = "FOR USE";
                                                $my_color = "success";
                                            }else if ($pull_row['gy_pullout_type'] == "DAMAGE") {
                                                $my_type_pull = "DAMAGED ITEM";
                                                $my_color = "info";
                                            }else if ($pull_row['gy_pullout_type'] == "BACK_ORDER") {
                                                $my_type_pull = "BACK-ORDER";
                                                $my_color = "danger";
                                            }else if ($pull_row['gy_pullout_type'] == "TRA") {
                                                $my_type_pull = "TRA";
                                                $my_color = "warning";
                                            }else{
                                                $my_type_pull = "UNKNOWN";
                                                $my_color = "default";
                                            }
                                    ?>

                                        <tr class="<?php echo $my_color; ?>">
                                            <td style="font-weight: bold;"><center><?php echo date("F d, Y g:i:s A",strtotime($pull_row['gy_pullout_date'])); ?></center></td>
                                            <td style="font-weight: bold; color: blue;"><center><?php echo $pull_row['gy_pullout_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $my_type_pull; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $pull_row['gy_product_code']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo $pull_row['gy_product_name']; ?></center></td>
                                            <td style="font-weight: bold;"><center><?php echo "<span style='color: blue;'>".$pull_row['gy_pullout_quantity']."</span> ".$details_row['gy_product_unit']; ?></center></td>
                                            <td><center><button type="button" class="btn btn-<?php echo $my_color;?>" title="click to see notes ..." data-target="#note_<?php echo $pull_row['gy_pullout_id']; ?>" data-toggle="modal"><i class="fa fa-list fa-fw"></i></button></center></td>
                                            <td><center><?php echo $my_user_row['gy_full_name']; ?></center></td>
                                            <!-- <td><center><button type="button" class="btn btn-danger" title="click to void the pull-out summary ..." data-target="#void_<?php #echo $pull_row['gy_pullout_id']; ?>" data-toggle="modal"><i class="fa fa-trash-o fa-fw"></i></button></center></td> -->
                                        </tr>

                                        <!-- Transaction Details -->
                                        
                                        <div class="modal fade" id="note_<?php echo $pull_row['gy_pullout_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <center><h4 class="modal-title" id="myModalLabel">Note</h4></center>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="panel-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-<?php echo $my_color;?>" style="border-radius: 0px;">
                                                                        <div class="panel-heading" style="border-radius: 0px;">
                                                                            Pulled-Out By: <b><?php echo $my_user_row['gy_full_name']; ?></b>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <p style="text-align: justify;">
                                                                                <?php echo $pull_row['gy_pullout_note']; ?>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete -->

                                        <div class="modal fade" id="void_<?php echo $pull_row['gy_pullout_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash-o fa-fw"></i> Void Pull-Out Summary <small style="color: #337ab7;">(press TAB to type/press ENTER to process)</small></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="void_pullout_summ?cd=<?php echo $pull_row['gy_pullout_id']; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Delete Secure PIN</label>
                                                                        <input type="password" name="my_secure_pin" class="form-control" autofocus required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center"> 
                         <ul class="pagination">
                            <?php echo $paginationCtrls; ?>
                         </ul>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript">
        $('#pullout_date_search').change(function(){
            console.log('Submiting form');                
            $('#my_form').submit();
        });
    </script>

</body>

</html>
