<?php
include("../includes/controller.php");

$adminfunctions = new Adminfunctions($db, $functions, $configs);

if(!$session->isSuperAdmin()){
    header("Location: ".$configs->homePage());
    exit;
}else{
$page = 'session';
$form = new Form();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Angry Frog : Admin : Security</title>
        <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Angry Frog</a> 
            </div>
  <div style="color: white; padding: 15px 50px 5px 50px; float: right;
       font-size: 16px;"> Last access : <?php echo $adminfunctions->previousVisit($session->username); ?> &nbsp; <a href="../includes/process.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
        <?php include ('side_navigation.php'); ?>  
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> 
                     
                    <!-- /. ROW  -->
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Session Settings</h3>
                            <p>
                           Session configurations such as the length of time a visitor is considered a guest or how long after a user is inactive is he or she logged off. Hover over the question marks for more info.
                            </p>
                        </div>
                    </div>
                    <!-- /. ROW  -->
                     
                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
               <div class="row">
                <div class="col-md-12">
                    
                    <!-- Form Elements -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <?php
                        if(Form::$num_errors > 0){ echo "<span style='color:#F00'>".Form::$num_errors." error(s) found</span>"; }
                        if(isset($_SESSION['configedit'])){unset($_SESSION['configedit']);echo "Details Updated!"; }
                        ?>
                        </div>
                        <div class="panel-body">
                            
        <form class="form-horizontal" role="form" action="adminprocess.php" method="POST">
            
            <div class="form-group <?php if(Form::error("timeout")) { echo 'has-error'; } ?>">
                <label for="user_timeout" class="col-lg-3 col-md-3 col-sm-4 control-label">User Inactivity Timeout: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="The time in minutes before an inactive user is logged out."></i></label>
                <div class="col-lg-2 col-sm-4">
                    <input id="user_timeout" class="form-control" type="text" name="user_timeout" class="form-control" value="<?php echo $configs->getConfig('USER_TIMEOUT'); ?>" />
                </div>
                <div class="col-sm-5">
                    <?php echo Form::error("timeout"); ?>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("guesttimeout")) { echo 'has-error'; } ?>">
                <label for="guest_timeout" class="col-lg-3 col-md-3 col-sm-4 control-label">Guest Timeout: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="The time in minutes before an inactive guest is no longer counted in the guest figures."></i></label>
                <div class="col-lg-2 col-sm-4">
                    <input id="guest_timeout" class="form-control" type="text" name="guest_timeout" value="<?php echo $configs->getConfig('GUEST_TIMEOUT'); ?>" />
                </div>
                <div class="col-sm-5">
                    <?php echo Form::error("guesttimeout"); ?>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("cookieexpiry")) { echo 'has-error'; } ?>">
                <label for="cookie_expiry" class="col-lg-3 col-md-3 col-sm-4 control-label">Cookie Expiry: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="The number of days before the remember me cookie expires."></i></label>
                <div class="col-lg-2 col-sm-4">
                    <input id="cookie_expiry" class="form-control" type="text" name="cookie_expiry" value="<?php echo $configs->getConfig('COOKIE_EXPIRE'); ?>" />
                </div>
                <div class="col-sm-5">
                    <?php echo Form::error("cookieexpiry"); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="cookie_path" class="col-lg-3 col-md-3 col-sm-4 control-label">Cookie Path: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="The cookie path defines the scope of the cookie - this tells the browser that cookies should only be sent back for the given path."></i></label>
                <div class="col-lg-2 col-sm-4">
                    <input id="cookie_path" class="form-control" type="text" name="cookie_path" value="<?php echo $configs->getConfig('COOKIE_PATH'); ?>" />
                </div>
                <div class="col-sm-5">
                    <?php echo Form::error("cookiepath"); ?>
                </div>
            </div>
            
            <p></p>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <?php echo $adminfunctions->stopField($session->username, 'session'); ?>
                    <input type="hidden" name="form_submission" value="session_edit">
                    <button type="submit" id="submit" name="submit" class="btn btn-default" ><i class=" fa fa-refresh "></i> Change Settings</button>
                    <button type="reset" id="reset" name="reset" class="btn btn-primary">Reset</button>
                </div>
            </div>
            
        </form>
                            
            </div>
                        <div class="panel-footer">
                        </div>
        </div>
        <!-- End Form Elements -->
                </div>
            </div>

    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
       </div>
     <!-- /. WRAPPER  -->
     
    <!-- SCRIPTS -AT THE BOTTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
<?php
}
?>