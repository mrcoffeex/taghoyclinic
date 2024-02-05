<?php
    include("../../conf/conn.php");
    include("../../conf/function.php");
    include("../../conf/my_project.php");
    include("session.php");

    $my_dir_value = @$_GET['cd'];
    $my_dir_mode = @$_GET['mode'];
    $pat = @$_GET['pat'];

    $my_project_header_title = "PRESCRIPTION SLIP #".$my_dir_value;
    $my_query = "SELECT * From `gy_pres` Where `gy_pres_code`='$my_dir_value' AND `gy_pres_status`='1' Order By `gy_pres_date` ASC";

    $presrow=$link->query($my_query)->fetch_array();

    $mypatient=words($presrow['gy_pat_id']);

    //get patient info
    $patient=$link->query("SELECT * From `gy_patient` Where `gy_pat_id`='$mypatient'");
    $patrow=$patient->fetch_array();
?>

<!DOCTYPE html>
<html lang="en">
    <?php include 'head.php'; ?>

    <style type="text/css">
        img{
            max-width:180px;
        }

        input[type=file]{
            padding:0px;
        }

        @media print{
            .no-print{
                display: none !important;
            }

            .my_hr{
                height: 5px;
                color: #000;
                background-color: #000;
                border: none;
            }

            td{
                background-color: rgba(255,255,255, 0.1);
            }

            .signature{
                 position: fixed; bottom: 0px; left: 0px;
            }
        }

        .my_hr{
            height: 5px;
            color: #000;
            background-color: #000;
            border: none;
        }

        td{
            background-color: rgba(255,255,255, 0.1);
            font-size: 12px;
        }

        hr { display: block; height: 2px;
    border: 0; border-top: 2px solid black;
    margin: 0px; padding: 0; width: 99%;}
    </style>

    <script type="text/javascript">
        window.print();
    </script>
<body>

    <div id="wrapper">

        <!-- Modals -->
        <?php include('modal.php');?>
        <?php include('modal_password.php');?> 

        <div id="page-wrapper" style="margin-left: 0px;">

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 no-print">
                            <?php 
                            if ($my_dir_mode == "trans") {
                                //value here ...
                            
                            ?>
                            <a href="pres_counter?pat=<?= $pat; ?>"><button type="button" class="btn btn-success" title="click to back to pull-out counter ..."><i class="fa fa-chevron-circle-left fa-fw"></i> Back</button></a>
                            <?php 
                                }else{
                                    //empty
                                } 
                            ?>
                            <button type="button" onclick="window.print();" class="btn btn-primary" title="click to print ..."><i class="fa fa-print fa-fw"></i> Print Result</button>
                        </div>
                        <div class="col-md-12">
                            <h2 style="font-weight: bold; margin-bottom: -10px; margin-top: 0px; text-align: center; text-transform: uppercase;">emerson r. taghoy m.d. fpsms, fpcp</h2>

                            <p>
                               <center>
                                <img src="../../img/doctor.png" style="width: 30px; height: 40px;" alt="logo">
                                <span style="font-size: 23px; text-transform: uppercase;">internal medicine</span>
                                <img src="../../img/doctor.png" style="width: 30px; height: 40px;" alt="logo">
                                <br>
                                <span style="font-size: 21px; text-transform: uppercase; font-weight: bold;">hospital affiliates</span>
                                </center>
                            </p>
                        </div>

                        <hr style="margin:0px;">

                        <div style="width: 48%; float: left; font-weight: bold; font-size: 16px;">
                            <p class="text-center" style="text-transform: uppercase;">
                                digos doctor's hospital <br>
                                mcdc <br>
                                llanos hospital <br>
                                dominican hospital <br>
                            </p>
                        </div>

                        <div style="width: 48%; float: left; font-weight: bold; font-size: 16px;">
                            <p class="text-center" style="text-transform: uppercase;">
                                sunga hospital <br>
                                st. john of the cross hospital <br>
                                southern philippines medical center<br>
                                gonzales maranan hospital <br>
                            </p>
                        </div>

                        <hr style="margin:0px;">

                        <div style="width: 58%; float: left; font-weight: bold; font-size: 18px; margin-left: 10px;">
                            <p>
                                Clinic hours: 11:00AM Monday <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2:30PM Wednesday<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;11:00AM - 2:00PM Thurs. - Sat.<br>
                            </p>
                        </div>

                        <div style="width: 38%; float: left; font-weight: bold; font-size: 18px;">
                            <p class="pull-right">
                                Cell #: 09973840354 <br>
                            </p>
                        </div>
                    </div>

                    <div class="row" style="margin-left: 10px; margin-right: 5px;">
                        <div style="width: 98%; float: left; font-size: 18px;">
                            <p style="width: 10%; display: inline;">PATIENT: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p> <p style="width: 43%; position: fixed; border-bottom: 1px solid black; display: inline;">&nbsp;&nbsp;&nbsp;<span style="text-transform: uppercase; font-weight: bold;"><?= $patrow['gy_pat_fullname']; ?></span></p>
                            <p style="width: 10%; display: inline; margin-left: 47%;">AGE: </p> <p style="width: 13%; position: fixed; border-bottom: 1px solid black; display: inline;">&nbsp;&nbsp;&nbsp;<b><?= get_curr_age($patrow['gy_pat_birthdate']); ?></b></p>
                            <p style="width: 10%; display: inline; margin-left: 15%;">SEX:&nbsp;</p> <p style="width: 12%; position: fixed; border-bottom: 1px solid black; display: inline;">&nbsp;&nbsp;&nbsp;<b><?= $patrow['gy_pat_gender']; ?></b></p>
                        </div>

                        <div style="width: 98%; float: left; font-size: 18px; ">
                            <p style="width: 10%; display: inline;">ADDRESS: &nbsp;&nbsp;</p> <p style="width: 55%; position: fixed; border-bottom: 1px solid black; display: inline;">&nbsp;&nbsp;&nbsp;<span><?= $patrow['gy_pat_address']; ?></span></p>
                            <p style="width: 10%; display: inline; margin-left: 59%;">DATE: &nbsp;</p> <p style="width: 18.5%; position: fixed; border-bottom: 1px solid black; display: inline;">&nbsp;&nbsp;&nbsp;<b><?= date("M-d-Y"); ?></b></p>
                        </div>
                    </div>

                    <br>

                    <div class="row" style="margin-left: 10px; margin-right: 10px;">
                        <div style="width: 48%; float: left; font-weight: bold; font-size: 25px; font-family: 'Courier New';">
                            <p style="text-transform: uppercase;">
                                Description
                            </p>
                        </div>

                        <div style="width: 48%; float: left; font-weight: bold; font-size: 25px; font-family: 'Courier New';">
                            <p style="text-transform: uppercase;" class="pull-right">
                                Qty
                            </p>
                        </div>

                        <?php
                            // Select ordered items
                            $sql_item_detail=$link->query($my_query);

                            while ($pull_row=$sql_item_detail->fetch_array()) {
                        ?>

                        <div style="width: 65%; float: left; font-weight: bold; font-size: 25px; margin-bottom: -5px; font-family: 'Courier New';">
                            <p style="text-transform: uppercase;">
                                <?php echo $pull_row['gy_product_name']; ?>
                            </p>
                        </div>

                        <div style="width: 33%; float: left; font-weight: bold; font-size: 25px; margin-bottom: -5px; font-family: 'Courier New';">
                            <p style="text-transform: uppercase;" class="pull-right">
                                <?php echo "<span style='color: blue;'>".$pull_row['gy_pres_quantity']."</span> ".$pull_row['gy_product_unit']; ?>
                            </p>
                        </div>

                        <div style="width: 98%; float: left; font-weight: bold; font-size: 25px; margin-bottom: -10px; font-family: 'Courier New';">
                            <p style="text-transform: uppercase;">
                                (<?php echo $pull_row['gy_product_desc']; ?>)
                            </p>
                        </div>

                        <div style="width: 98%; float: left; font-weight: bold; font-size: 25px; margin-bottom: -10px; font-family: 'Courier New';">
                            <p style="text-transform: uppercase;">
                                SIG:
                            </p>
                        </div>

                        <div style="width: 98%; float: left; font-weight: bold; font-size: 25px; margin-bottom: 30px; font-family: 'Courier New';">
                            <p style="text-transform: uppercase;">
                                &nbsp;&nbsp;&nbsp;<?php echo "<span style='color: blue;'>".$pull_row['gy_pres_note']."</span> "; ?>
                            </p>
                        </div>

                        <?php } ?>
                    </div>

                    <div class="signature" style="width: 98%; float: left; font-weight: bold; font-size: 20px;">
                        <p class="pull-right">
                            <span style="text-transform: uppercase;">emerson r. taghoy, m.d.</span><br>
                            License No.: 112969<br>
                            PTR No.: 2972036<br>
                            S2 No.: <br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>




</html>