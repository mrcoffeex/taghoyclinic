<!-- Navigation -->
<style type="text/css">
    #navi{
        color: #fff;
    }

    #navi:hover{
        color: #8a6d3b;
    }
</style>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0; background-color: #8a6d3b; color: #fff;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index"><span style="text-transform: uppercase; color: #fff;"><?php echo $my_project_name; ?> <?php echo $my_project_title; ?> - <?php echo $user_info ?></span></a>
    </div>
    <!-- /.navbar-header -->
    <div class="full-right">
    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" id="navi" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="" data-toggle="modal" data-target="#myModal"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a href="" data-toggle="modal" data-target="#changepass"><i class="fa fa-gear fa-fw"></i> Change Password</a>
                </li>
                <li class="divider"></li>
                <li><a href="logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
        </li>
    </ul>
    </div>
        <?php include("side-panel.php"); ?>
</nav>

<!-- unlock drawer modal here -->
<div class="modal fade" id="my_drawer_unlock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-unlock fa-fw"></i> Open Cash Drawer <small>Security PIN - <span style="color: blue"><?php echo $user_info; ?></span> &nbsp; </small> <span id="unlock_note"></span></h4>
            </div>
            <div class="modal-body">                    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group input-group">
                            <input type="password" name="my_secure_piner" id="my_secure_piner" class="form-control" autofocus required>
                            <span class="input-group-btn">
                                <button class="btn btn-success" type="button" title="click to open drawer ..." id="my_unlock_btn"><i class="fa fa-unlock"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>