<?php
include("../includes/controller.php");
$adminfunctions = new Adminfunctions($db, $functions, $configs);

if(!$session->isAdmin()){
    header("Location: ".$configs->homePage());
    exit;
}else{
$page = 'about';
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
                            <h3>About</h3>
                            <p>
                           About the Angry Frog PHP Login Script. Contact Us about removing this page from your script.
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
                    
                        The Angry Frog PHP Login Script started out as a means to an end. In my early days of using PHP I was looking for a simple script that could assist me with protecting certain pages or sections of my website. It was then that I stumbled upon
                        jpmaster77's Login Script with Advanced Features at the Evolt website. Jpmaster77 had taken a lot of trouble to comment his code and make it very easy to read, decipher and ultimately use so as a PHP programmer only just starting out, it was a Godsend.
                        It's testament to how popular that script became that it is still online even to this day, ten years on. I came back to it a couple of times over the years and then when I had learnt a bit more PHP I decided it would be good to try and update 
                        the script, making it more compatible with newer versions of PHP and adding extra bits to it. Since then I have made free versions of it available online to thousands of people. It has evolved to be the script it is today. Thanks to the
                        kind visitors at the Angry Frog website and by sticking at it, I have continued to improve it, make it more secure and make the Admin GUI more professional. And the script you see before you is the sum of all that.<br><br>
                        
                        I hope you enjoy it and appreciate it and use it as much as I have enjoyed creating it and using it myself over the years.<br><br>
                        
                        Angry Frog
                        
                        <h4 style="margin-top:30px;">Disclaimer</h4>
                        This script is intended for general use and no warranty is implied for suitability to any given task. I hold no responsibility for your setup or any damage done while using/installing/modifying this script. 
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