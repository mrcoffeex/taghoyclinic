<?php

    include 'conf/conn.php';
    include 'conf/my_project.php';

    session_start();

	if (isset($_SESSION['fus_user_id'])) {
        if($_SESSION['fus_user_type'] == "0"){
            header("location: content/admin/");
        }else if($_SESSION['fus_user_type'] == "1"){
            header("location: content/inventory/");
        }else if($_SESSION['fus_user_type'] == "2"){
            header("location: content/cashier/");
		}else if($_SESSION['fus_user_type'] == "3"){
            header("location: content/moderator/");
        }else if($_SESSION['fus_user_type'] == "4"){
            header("location: content/preview/");
        }
	}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title><?php echo $my_project_name; ?></title>
	<link rel = "shortcut icon" href = "img/logo.ico">
	<link href="login/css/kent.css" rel="stylesheet" />
    <link href="login/css/bootstrap.css" rel="stylesheet" />
    <link href="login/css/font-awesome.min.css" rel="stylesheet" />
    <link href="login/css/style.css" rel="stylesheet" />
    <link href='login/css/fonts.css' rel='stylesheet' type='text/css' />
	
    <!--FONTS-->
    <link href="https://fonts.googleapis.com/css?family=Abel|Oswald" rel="stylesheet">
    <!--FONTS-->


	<!--<style type = "text/css">
		.addadd{
			background-color: rgba(152, 33, 33, 0.44);
		}
	</style-->

    <script type="text/javascript">
        function contact(){
            window.alert("Kindly Call this number: 09121610673, Thank you!");
        }
    </script>

    <style>
    .no-js #loader { display: none;  }
    .js #loader { display: block; position: absolute; left: 100px; top: 0; }
    .se-pre-con {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url(loader/images/loader-128x/Preloader_3.gif) center no-repeat #fff;
    }
    </style>

</head>
<body>

    <div class="container">
        <div class="row text-center pad-top ">
            <div class="col-md-12">
                <h2 class = "blabla" style="color: #5a422d;"><?php echo $my_project_name; ?></h2>
                <b>
                    <center>
                    <span id="tick2" style="color: #5a422d;">      
                        <script>
                            function show2(){
                            if (!document.all&&!document.getElementById)
                            return
                            thelement=document.getElementById? document.getElementById("tick2"): document.all.tick2
                            var Digital=new Date()
                            var hours=Digital.getHours()
                            var minutes=Digital.getMinutes()
                            var seconds=Digital.getSeconds()
                            var dn="PM"
                            if (hours<12)
                            dn="AM"
                            if (hours>12)
                            hours=hours-12
                            if (hours==0)
                            hours=12
                            if (minutes<=9)
                            minutes="0"+minutes
                            if (seconds<=9)
                            seconds="0"+seconds
                            var ctime=hours+":"+minutes+":"+seconds+" "+dn
                            thelement.innerHTML=ctime
                            setTimeout("show2()",1000)
                            }
                            window.onload=show2
                            //-->
                        </script>
                    </span> &nbsp;|&nbsp;<span style="color: #5a422d;">Today is <?php $date = new DateTime(); echo $date->format('l, F jS, Y'); ?></span>
                    </center>
                </b>
            </div>
        </div>
         <div class="row pad-top">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <div class="panel addadd panel-default">
                    <div class="panel-heading">
                        <center><strong style="color: #fff;">Enter Account Information</strong></center>
                    </div>
                    <div class="panel-body">
                        <form method="post" enctype="multipart/form-data" action="conf/login_conf">
                            <br/>
                            <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                                    <input type="text" class="form-control" name="username" placeholder="Your Username" autocomplete="off" autofocus required/>
                                </div>
                            <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password"  placeholder="Your Password" required/>
                            </div>
                             
                            <input type="submit" value="Login Now" name="login" class="btn btn-primary btn-block">
                            <br/>
                            <label class = "whity"> Forgot Password?</label> <a href="#" onclick="contact()" style="color: #5a422d; font-size:12px;">click here ... </a>  
                            <br/>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <p style="position: absolute; bottom: 0; right: 0; color: white; margin: 7px;">
            Powered By: <a href="https://www.facebook.com/krazyappsph" target="_NEW">KrazyAppsPH</a>
        </p>
    </div>

    <script src="login/plugins/jquery-1.10.2.js"></script>
    <script src="login/plugins/bootstrap.js"></script>
   
</body>
</html>
