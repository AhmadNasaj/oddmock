<?php
include("../includes/controller.php");
$adminfunctions = new Adminfunctions($db, $functions, $configs);

if(!$session->isAdmin() || !isset($_SESSION['regsuccess'])){
    header("Location: ".$configs->homePage());
    exit;
}
else{
$page = 'index';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Angry Frog : Admin User Creation Summary</title>
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
                <a class="navbar-brand" href="index.html">Angry Frog</a> 
            </div>
    <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"> 
    Last access : <?php echo $adminfunctions->previousVisit($session->username); ?> &nbsp; <a href="../includes/process.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
            <!-- /. NAV TOP  -->
            <?php include ('side_navigation.php'); ?>  
            <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">

        <?php 
        if($_SESSION['regsuccess']==0 || $_SESSION['regsuccess']==5){
        echo "<div class='login'><h1>Registered!</h1>";
        echo "<p>Thank you Admin, <b>".$_SESSION['reguname']."</b> has been added to the database.</p></div>";
        }
        /* Registration failed */
        else if ($_SESSION['regsuccess']==2){
        echo "<div class='login'><h1>Registration Failed</h1>";
        echo "<p>We're sorry, but an error has occurred and your registration for the username <b>".$_SESSION['reguname']."</b> "
            ."could not be completed.<br>Please try again.</p></div>";
        }
        unset($_SESSION['regsuccess']);
        unset($_SESSION['reguname']);
        ?>
                 
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
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
<?php } ?>