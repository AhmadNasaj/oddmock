<?php
include("../includes/controller.php");
$adminfunctions = new Adminfunctions($db, $functions, $configs);

if(!$session->isAdmin()){
    header("Location: ".$configs->homePage());
    exit;
}
else{
$page = 'index';

$query3 = $db->query("SELECT username FROM ".TBL_USERS);
$total_users = $query3->rowCount();

?>﻿
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Angry Frog : Admin : Dashboard</title>
        <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
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
  Last access : <?php echo $adminfunctions->previousVisit($session->username); ?> &nbsp; <a href="../includes/process.php" class="btn btn-danger square-btn-adjust">Logout</a> 
  </div>
        </nav>   
        <?php include ('side_navigation.php'); ?>  
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h2>Admin Dashboard</h2>   
                        <h5>Welcome back <?php echo $session->username; ?><?php if(file_exists('../install/')) { echo '<strong> - Please remove the install folder as leaving this folder poses a security risk!!</strong>'; } ?></h5>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                <div class="row">
                    
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"> 
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                                <h3><?php echo $session->getNumMembers(); ?> Users</h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                            Registered to the Site 
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"> 
                        <div class="panel panel-primary text-center no-boder bg-color-red">
                            <div class="panel-body">
                                <i class="fa fa-laptop fa-5x"></i>
                                <h3><?php echo $functions->calcNumActiveUsers(); ?> Users</h3>
                            </div>
                            <div class="panel-footer back-footer-red">
                            Currently Online
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"> 
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-thumbs-up fa-5x"></i>
                                <h3><?php echo $adminfunctions->usersSince($session->username);  ?> New Users</h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                            Since Last Visit
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"> 
                        <div class="panel panel-primary text-center no-boder bg-color-red">
                            <div class="panel-body">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                                <h3><?php echo $configs->getConfig('record_online_users'); ?> Users Online </h3>
                            </div>
                            <div class="panel-footer back-footer-red">
                            <?php echo date('M j, Y, g:i a', $configs->getConfig('record_online_date')); ?> 
                            </div>
                        </div>
                    </div>

                    
                </div>
                 <!-- /. ROW  -->                
                
                <div class="row">
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Last 5 Visitors
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Status</th>
                                            <th>E-mail</th>
                                            <th>Last Visit</th>
                                            <th class='text-center'>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    <?php
                                    $sql = "SELECT * FROM users WHERE username != 'admin' ORDER BY timestamp DESC LIMIT 5";
                                    $result = $db->prepare($sql);
                                    $result->execute(); 
                                    while ($row = $result->fetch()) {
                                    $lastlogin = $adminfunctions->displayDate($row['timestamp']);
                                    $email = $row['email'];
                                    $email = strlen($email) > 25 ? substr($email,0,25)."..." : $email;
   
                                    echo "<tr>";
                                    echo "<td><a href='".$configs->getConfig('WEB_ROOT')."admin/adminuseredit.php?usertoedit=".$row['username']."'>".$row['username']."</a></td><td>" . $adminfunctions->displayStatus($row['username'])  . "</td>"
                                    ."<td><a href='mailto:".$row['email']."'>".$email."</a></td><td>".$lastlogin."</td>";
                                    echo "<td class='text-center'><div class='btn-group btn-group-xs'><a href='".$configs->getConfig('WEB_ROOT')."admin/adminuseredit.php?usertoedit=".$row['username']."' title='Edit' class='open_modal btn btn-default'><i class='fa fa-pencil'></i> View</a>";
                                    echo "</tr>";
                                    }
                                    ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Last 5 Registered Users
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Status</th>
                                            <th>E-mail</th>
                                            <th>Registered</th>
                                            <th class='text-center'>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    <?php
                                    $sql = "SELECT * FROM users WHERE username != 'admin' ORDER BY regdate DESC LIMIT 5";
                                    $result = $db->prepare($sql);
                                    $result->execute(); 
                                    while ($row = $result->fetch()) {
                                    $regdate = $row['regdate'];
                                    $reg = $adminfunctions->displayDate($row['regdate']);
                                    $email = $row['email'];
                                    $email = strlen($email) > 25 ? substr($email,0,25)."..." : $email;
   
                                    echo "<tr>";
                                    echo "<td><a href='".$configs->getConfig('WEB_ROOT')."admin/adminuseredit.php?usertoedit=".$row['username']."'>".$row['username']."</a></td><td>" . $adminfunctions->displayStatus($row['username'])  . "</td>";
                                    echo "<td><a href='mailto:".$row['email']."'>".$email."</a></td><td>".$reg."</td>";
                                    echo "<td class='text-center'><div class='btn-group btn-group-xs'><a href='".$configs->getConfig('WEB_ROOT')."admin/adminuseredit.php?usertoedit=".$row['username']."' title='Edit' class='open_modal btn btn-default'><i class='fa fa-pencil'></i> View</a>";
                                    echo "</tr>";
                                    }
                                    ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    </div>
    
                </div>
        <!-- /. ROW  -->
                
        
        <div class="row"> 
                          
                <!-- Chart -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                     
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            User Registrations By Month
                        </div>
                        <div class="panel-body">
                            <div id="myfirstchart"></div>
                        </div>
                    </div>            
                </div>
                
        </div>
                 <!-- /. ROW  -->           
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    
    <!-- SCRIPTS AT THE BOTTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
     <!-- MORRIS CHART SCRIPTS -->
     <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
    <?php 
    $sql = "SELECT FROM_UNIXTIME(`regdate`, '%m, %Y') AS `date`,
    COUNT(`users`.`id`) AS `count`
    FROM `users`
    GROUP BY `date` ORDER BY `regdate`";
    
    $result = $db->prepare($sql);
    $result->execute();
    
    ?>
    <script>new Morris.Bar({
  // ID of the element in which to draw the chart.
  element: 'myfirstchart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    <?php while ($row = $result->fetch()) {
    ?>
    { month: '<?php echo $row['date']; ?>', value: <?php echo $row['count']; ?> },
  <?php } ?>
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'month',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Users']
});</script>
    
   
</body>
</html>
<?php
}
?>