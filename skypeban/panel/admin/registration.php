<?php
include("../includes/controller.php");

if(!$session->isSuperAdmin()){
    header("Location: ".$configs->homePage());
    exit;
} else {
$page = 'registration';
$form = new Form();
$adminfunctions = new Adminfunctions($db, $functions, $configs);
$admin_regdate = $functions->getUserInfo($session->username);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Angry Frog : Admin : Registration Settings</title>
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
font-size: 16px;"> Last access : <?php echo $adminfunctions->displayDate($admin_regdate['previous_visit']); ?> &nbsp; <a href="../includes/process.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
        <?php include ('side_navigation.php'); ?>  
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> 
                     
                    <!-- /. ROW  -->
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Registration Settings</h3>
                            <p>
                            Change the settings regarding registration to the site including turning Captcha on and off. Hover over the question marks for more info.
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
            
            <div class="form-group">
                <label for="accountactivation" class="col-lg-3 col-md-3 col-sm-4 control-label">Account Activation:</label>
                    <div class="col-sm-5">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="activation" id="optionsRadios1" value="4" <?php if($configs->getConfig('ACCOUNT_ACTIVATION') == 4) { echo "checked='checked'"; } ?> />Disable Registration
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="activation" id="optionsRadios2" value="1" <?php if($configs->getConfig('ACCOUNT_ACTIVATION') == 1) { echo "checked='checked'"; } ?> />No activation (immediate access)
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="activation" id="optionsRadios3" value="2" <?php if($configs->getConfig('ACCOUNT_ACTIVATION') == 2) { echo "checked='checked'"; } ?> />By user (e-mail verification)
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="activation" id="optionsRadios4" value="3" <?php if($configs->getConfig('ACCOUNT_ACTIVATION') == 3) { echo "checked='checked'"; } ?> />Admin Activation 
                                    </label>
                                </div>
                    </div>
                <div class="col-sm-4">
                </div>
            </div>
            
            <div class="form-group">
                <label for="limit_username_chars" class="col-lg-3 col-md-3 col-sm-4 control-label">Limit Username Characters:</label>
                <div class="col-lg-3 col-md-4 col-sm-5">
                        <select class="form-control" name="limit_username_chars" id="limit_username_chars">
                            <option value="any_chars" <?php if ($configs->getConfig('USERNAME_REGEX') == 'any_chars') { echo "selected='selected'"; }?>>Any Chars</option>
                            <option value="alphanumeric_only" <?php if ($configs->getConfig('USERNAME_REGEX') == 'alphanumeric_only') { echo "selected='selected'"; }?>>Alphanumeric Only</option>
                            <option value="alphanumeric_spacers" <?php if ($configs->getConfig('USERNAME_REGEX') == 'alphanumeric_spacers') { echo "selected='selected'"; }?>>Alphanumeric Spacers</option>
                            <option value="any_letter_num" <?php if ($configs->getConfig('USERNAME_REGEX') == 'any_letter_num') { echo "selected='selected'"; }?>>Any Letter Num</option>
                            <option value="letter_num_spaces" <?php if ($configs->getConfig('USERNAME_REGEX') == 'letter_num_spaces') { echo "selected='selected'"; }?>>Letter Num and Spaces</option>
                        </select>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("max_user_chars") || (Form::error("min_user_chars"))){ echo 'has-error'; } ?>">
                <label for="user_chars" class="col-lg-3 col-md-3 col-sm-4 control-label">Username Length:</label>
                <div class="col-lg-1 col-md-2 col-sm-3">
                    <div class="input-group input-group-sm">
                        <label>Min</label>
                        <input type="text" name="min_user_chars" class="form-control input-sm" value="<?php if(Form::value("min_user_chars") == ""){ echo $configs->getConfig('min_user_chars'); } else { echo Form::value("min_user_chars"); } ?>" placeholder="Min">
                    </div>
                    <div class="input-group input-group-sm">        
                        <label>Max</label>
                        <input type="text" name="max_user_chars" class="form-control input-sm" value="<?php if(Form::value("max_user_chars") == ""){ echo $configs->getConfig('max_user_chars'); } else { echo Form::value("max_user_chars"); } ?>" placeholder="Max">
                    </div>
                </div>
                <div><?php echo Form::error("min_user_chars"); ?><?php echo Form::error("max_user_chars"); ?></div>
            </div>
            
            <div class="form-group <?php if(Form::error("max_pass_chars") || (Form::error("min_pass_chars"))){ echo 'has-error'; } ?>">
                <label for="password_chars" class="col-lg-3 col-md-3 col-sm-4 control-label">Password Length:</label>
                <div class="col-lg-1 col-md-2 col-sm-3">
                    <div class="input-group input-group-sm">
                        <label>Min</label>
                        <input type="text" name="min_pass_chars" class="form-control input-sm" value="<?php if(Form::value("min_pass_chars") == ""){ echo $configs->getConfig('min_pass_chars'); } else { echo Form::value("min_pass_chars"); } ?>" placeholder="Min">
                    </div>
                    <div class="input-group input-group-sm">
                        <label>Max</label>
                        <input type="text" name="max_pass_chars" class="form-control input-sm" value="<?php if(Form::value("max_pass_chars") == ""){ echo $configs->getConfig('max_pass_chars'); } else { echo Form::value("max_pass_chars"); } ?>" placeholder="Max">
                    </div>
                </div>
                <div><?php echo Form::error("min_pass_chars"); ?><?php echo Form::error("max_pass_chars"); ?></div>
            </div>
            
            <script>
            $('[data-toggle="popover"]').popover({
            trigger: 'hover',
            'placement': 'top'
            });
            </script>
            
            <div class="form-group">
                <label for="send_welcome" class="col-lg-3 col-md-3 col-sm-4 control-label">Send Welcome E-mail: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="Send a welcome e-mail to newly registered users."></i>
    </label>
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label class="radio-inline"> <input type="radio" name="send_welcome" id="send_welcome" value="1" <?php if($configs->getConfig('EMAIL_WELCOME') == 1) { echo "checked='checked'"; } ?>> Yes </label>
                    <label class="radio-inline"> <input type="radio" name="send_welcome" id="send_welcome" value="0" <?php if($configs->getConfig('EMAIL_WELCOME') == 0) { echo "checked='checked'"; } ?>> No </label>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
            
            <div class="form-group">
                <label for="enable_capthca" class="col-lg-3 col-md-3 col-sm-4 control-label">Enable Captcha: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="Enables or disables captcha system at registration."></i></label>
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label class="radio-inline"> <input type="radio" name="enable_capthca" id="enable_capthca" value="1" <?php if($configs->getConfig('ENABLE_CAPTCHA') == 1) { echo "checked='checked'"; } ?>> Yes </label>
                    <label class="radio-inline"> <input type="radio" name="enable_capthca" id="enable_capthca" value="0" <?php if($configs->getConfig('ENABLE_CAPTCHA') == 0) { echo "checked='checked'"; } ?>> No </label>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
            
            <div class="form-group">
                <label for="all_lowercase" class="col-lg-3 col-md-3 col-sm-4 control-label">Username Lowercase: <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="Makes submitted username all lowercase."></i></label>
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label class="radio-inline"> <input type="radio" name="all_lowercase" id="all_lowercase" value="1" <?php if($configs->getConfig('ALL_LOWERCASE') == 1) { echo "checked='checked'"; } ?>> Yes </label>
                    <label class="radio-inline"> <input type="radio" name="all_lowercase" id="all_lowercase" value="0" <?php if($configs->getConfig('ALL_LOWERCASE') == 0) { echo "checked='checked'"; } ?>> No </label>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
            
            <div class="form-group">
                <label for="allow_dupe_email" class="col-lg-3 col-md-3 col-sm-4 control-label">Allow Duplicate E-mail:  <i class="glyphicon glyphicon-question-sign" style="cursor: help;" title="Duplicates E-mails allowed / disallowed."></i></label>
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <label class="radio-inline"> <input type="radio" name="allow_dupe_email" id="allow_dupe_email" value="1" <?php if($configs->getConfig('ALLOW_DUPE_EMAIL') == 1) { echo "checked='checked'"; } ?>> Yes </label>
                    <label class="radio-inline"> <input type="radio" name="allow_dupe_email" id="allow_dupe_email" value="0" <?php if($configs->getConfig('ALLOW_DUPE_EMAIL') == 0) { echo "checked='checked'"; } ?>> No </label>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
            
            <p></p>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <?php echo $adminfunctions->stopField($session->username, 'registration'); ?>
                    <input type="hidden" name="form_submission" value="registration_edit">
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