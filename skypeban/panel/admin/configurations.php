<?php
include("../includes/controller.php");
$adminfunctions = new Adminfunctions($db, $functions, $configs);

if(!$session->isSuperAdmin()){
    header("Location: ".$configs->homePage());
    exit;
}else{
$page = 'configurations';
$form = new Form();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Angry Frog : Admin : General Settings</title>
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
                            <h3>General Site Settings</h3>
                            <p>
                            Change general site configurations such as the website name and the e-mail address from which your users will receive administrative e-mails. Hover over the question marks for more info.
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
            
            <div class="form-group <?php if(Form::error("sitename")) { echo 'has-error'; } ?>">
                <label for="sitename" class="col-sm-4 col-md-3 control-label">Site Name:</label>
                <div class="col-sm-4">
                    <input id="sitename" class="form-control" type="text" name="sitename" class="form-control" value="<?php if(Form::value("sitename") == ""){ echo $configs->getConfig('SITE_NAME'); } else { echo Form::value("sitename"); } ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                    <small><?php echo Form::error("sitename"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("sitedesc")) { echo 'has-error'; } ?>">
                <label for="sitedesc" class="col-sm-4 col-md-3 control-label">Site Description: </label>
                <div class="col-sm-4 col-md-4">
                    <input id="sitedesc" class="form-control" type="text" name="sitedesc" value="<?php if(Form::value("sitedesc") == ""){ echo $configs->getConfig('SITE_DESC'); } else { echo Form::value("sitedesc"); } ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                    <small><?php echo Form::error("sitedesc"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("emailfromname")) { echo 'has-error'; } ?>">
                <label for="emailfromname" class="col-sm-4 col-md-3 control-label">E-mail From Name: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="The From Name in any outgoing email from the admin e-mail address."></i></label>
                <div class="col-sm-4 col-md-4">
                    <input id="emailfromname" class="form-control" type="text" name="emailfromname" value="<?php if(Form::value("emailfromname") == ""){ echo $configs->getConfig('EMAIL_FROM_NAME'); } else { echo Form::value("emailfromname"); } ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                    <small><?php echo Form::error("emailfromname"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("adminemail")) { echo 'has-error'; } ?>">
                <label for="adminemail" class="col-sm-4 col-md-3 control-label">Site E-mail Address: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="The outgoing email address such as when a user gets an activation email."></i></label>
                <div class="col-sm-4 col-md-4">
                    <input id="adminemail" class="form-control" type="text" name="adminemail" value="<?php if(Form::value("adminemail") == ""){ echo $configs->getConfig('EMAIL_FROM_ADDR'); } else { echo Form::value("adminemail"); } ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                    <small><?php echo Form::error("adminemail"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("webroot")) { echo 'has-error'; } ?>">
                <label for="siteroot" class="col-sm-4 col-md-3 control-label">Site Root: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="Please include full URL and subfolder (with trailing forward-slash) eg, http://www.mysite.com/login/"></i></label>
                <div class="col-sm-4 col-md-4">
                    <input id="webroot" class="form-control" type="text" name="webroot" value="<?php if(Form::value("webroot") == ""){ echo $configs->getConfig('WEB_ROOT'); } else { echo Form::value("webroot"); } ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                    <small><?php echo Form::error("webroot"); ?></small>
                </div>
            </div>
            
            <div class="form-group">
                <label for="home_page" class="col-sm-4 col-md-3 control-label">Home Page: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="eg, index.php - This page among other things will be the page users are redirected to after logging off."></i></label>
                <div class="col-sm-4 col-md-4">
                    <input id="home_page" class="form-control" type="text" name="home_page" value="<?php echo $configs->getConfig('home_page'); ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                </div>
            </div>
            
            <div class="form-group">
                <label for="login_page" class="col-sm-4 col-md-3 control-label">Login Page: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="eg, index.php - where users will be directed to after a successful login."></i></label>
                <div class="col-sm-4 col-md-4">
                    <input id="login_page" class="form-control" type="text" name="login_page" value="<?php echo $configs->getConfig('login_page'); ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                </div>
            </div>
            
            <div class="form-group">
                <label for="date_format" class="col-sm-4 col-md-3 control-label">Date Format: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="eg, 14th Aug 2015 or 08/14/2015 or 14/08/15 in PHP Date format. More info at the PHP website."></i></label>
                <div class="col-sm-4 col-md-4">
                    <input id="date_format" class="form-control" type="text" name="date_format" value="<?php echo $configs->getConfig('date_format'); ?>" />
                </div>
                <div class="col-sm-4 col-md-5">
                </div>
            </div>
            
            <p></p>
            <div class="form-group">
                <div class="col-sm-4 col-md-3"></div>
                <div class="col-sm-8 col-md-9">
                    <?php echo $adminfunctions->stopField($session->username, 'configs'); ?>
                    <input type="hidden" name="form_submission" value="config_edit">
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
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
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