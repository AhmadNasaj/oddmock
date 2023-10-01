<?php
include("../includes/controller.php");

$adminfunctions = new Adminfunctions($db, $functions, $configs);
if(!$session->isSuperAdmin()){
    header("Location: ".$configs->homePage());
    exit;
}
else{
$page = 'security';
$form = new Form();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Angry Frog : Security Settings</title>
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
    <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"> 
    Last access : <?php echo $adminfunctions->previousVisit($session->username); ?> &nbsp; <a href="../includes/process.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
            <!-- /. NAV TOP  -->
            <?php include ('side_navigation.php'); ?>  
            <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> 
                     
                    <!-- /. ROW  -->
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Security Settings</h3>
                            <p>
                            Change the settings regarding disallowing usernames from registering and banning IP addresses from both registering and logging on.
                            </p>
                        </div>
                    </div>
                    <!-- /. ROW  -->
                     
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr />

                <div class="col-md-12">
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <?php
                        if(Form::$num_errors > 0){ echo "<span style='color:#F00'>".Form::$num_errors." error(s) found</span>"; }
                        if(isset($_SESSION['configedit'])){unset($_SESSION['configedit']);echo "Details Updated!"; }
                        ?>
                        </div>
                        <div class="panel-body">
                            
                        <form class="form-horizontal" role="form" action="adminprocess.php" method="POST">
                               
                        <div class="form-group <?php if(Form::error("username_toban")) { echo 'has-error'; } ?>">
                            <label for="sitedesc" class="col-sm-3 control-label">Disallow Username : </label>
                            <div class="col-sm-4">
                                <input id="sitedesc" class="form-control" type="text" name="usernametoban" value="<?php if(Form::value("username_toban") !== ""){ echo Form::value("username_toban"); } ?>" />
                            </div>
                            <div class="col-sm-5"></div>
                        </div>
                            
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <?php echo $adminfunctions->stopField($session->username, 'configs'); ?>
                                <input type="hidden" name="form_submission" value="disallow_user">
                                <button type="submit" id="submit" name="submit" class="btn btn-default" ><i class=" fa fa-refresh "></i> Add Username</button>
                            </div>
                        </div>
                            
                        </form>
                            
                        <form class="form-horizontal" role="form" action="adminprocess.php" method="POST">
                            
                        <div class="form-group <?php if(Form::error("ban_username")) { echo 'has-error'; } ?>">
                            <label for="ban_username" class="col-sm-3 control-label">Disallowed Usernames : </label>
                            <div class="col-sm-4">
                                <select name="username_tounban" class="form-control" multiple="multiple">
                                <?php
                                $sql = "SELECT ban_username, ban_id FROM banlist WHERE ban_username != ''";
                                $result = $db->prepare($sql);
                                $result->execute();
                                while ($row = $result->fetch()) {
                                $username = $row['ban_username'];
                                $ban_id = $row['ban_id'];
                                echo "<option value='$ban_id'>$username</option>";
                                }
                                ?>
                                </select> 
                            </div>
                            <div class="col-sm-5"></div>
                        </div>
                            
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <?php echo $adminfunctions->stopField($session->username, 'configs'); ?>
                                <input type="hidden" name="form_submission" value="undisallow_user">
                                <button type="submit" id="submit" name="submit" class="btn btn-default" ><i class=" fa fa-refresh "></i> Remove Disallowed Usernames</button>
                            </div>
                        </div>
                            
                        </form>
                            
                        </div>
                        
                        <div class="panel-footer">
                        </div>
                        
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <?php
                        if(Form::$num_errors > 0){ echo "<span style='color:#F00'>".Form::$num_errors." error(s) found</span>"; }
                        if(isset($_SESSION['configedit'])){unset($_SESSION['configedit']);echo "Details Updated!"; }
                        ?>
                        </div>
                        <div class="panel-body">
                            
                        <form class="form-horizontal" role="form" action="adminprocess.php" method="POST">
                               
                        <div class="form-group <?php if(Form::error("ip_address")) { echo 'has-error'; } ?>">
                            <label for="ip_address" class="col-sm-3 control-label">Ban IP Address : </label>
                            <div class="col-sm-4">
                                <input id="ip_address" class="form-control" type="text" name="ipaddress" value="<?php echo Form::value("ipaddress"); ?>" />
                            </div>
                            <div class="col-sm-5">
                                <?php echo Form::error("ip_address"); ?>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <?php echo $adminfunctions->stopField($session->username, 'configs'); ?>
                                <input type="hidden" name="form_submission" value="ban_ip">
                                <button type="submit" id="submit" name="submit" class="btn btn-default" ><i class=" fa fa-refresh "></i> Add IP Address </button>
                            </div>
                        </div>
                            
                        </form> 
                            
                        <form class="form-horizontal" role="form" action="adminprocess.php" method="POST">
                            
                        <div class="form-group <?php if(Form::error("ip_address")) { echo 'has-error'; } ?>">
                            <label for="sitedesc" class="col-sm-3 control-label">Banned IP Addresses : </label>
                            <div class="col-sm-4">
                                <select name="ipaddress" class="form-control" multiple="multiple">
                                <?php
                                $sql = "SELECT ban_ip FROM banlist WHERE ban_ip != ''";
                                $result = $db->prepare($sql);
                                $result->execute();
                                while ($row = $result->fetch()) {
                                $ipaddress = $row['ban_ip'];
                                echo "<option value='$ipaddress'>$ipaddress</option>";
                                }
                                ?>
                                </select> 
                            </div>
                            <div class="col-sm-5">
                                <?php echo Form::error("ip_address"); ?>
                            </div>
                        </div>
                            
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <?php echo $adminfunctions->stopField($session->username, 'configs'); ?>
                                <input type="hidden" name="form_submission" value="unban_ip">
                                <button type="submit" id="submit" name="submit" class="btn btn-default" ><i class=" fa fa-refresh "></i> Remove Banned IP Addresses</button>
                            </div>
                        </div>
                            
                        </form>
                            
                        </div>

                        <div class="panel-footer">
                        </div>
                        
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
<?php } ?>