<?php
include("../includes/controller.php");
$adminfunctions = new Adminfunctions($db, $functions, $configs);

if (!$session->isAdmin()) {
    header("Location: " . $configs->homePage());
    exit;
} else {
    $form = new Form();
    $page = 'useradmin';
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Angry Frog : Admin : User Admin</title>
            <!-- BOOTSTRAP STYLES-->
            <link href="assets/css/bootstrap.css" rel="stylesheet" />
            <!-- FONTAWESOME STYLES-->
            <link href="assets/css/font-awesome.css" rel="stylesheet" />
            <!-- CUSTOM STYLES-->
            <link href="assets/css/custom.css" rel="stylesheet" />
            <!-- GOOGLE FONTS-->
            <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
            <!-- TABLE STYLES-->
            <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
                    <div style="color: white;
                         padding: 15px 50px 5px 50px;
                         float: right;
                         font-size: 16px;"> Last access : <?php echo $adminfunctions->previousVisit($session->username); ?> &nbsp; <a href="../includes/process.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
                </nav>   
                <?php include ('side_navigation.php'); ?>
                <div id="page-wrapper" >
                    <div id="page-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>User Admin</h3>
                                <p>
                                    Check and edit users and those waiting for activation. Admin can also create users manually.
                                </p>
                            </div>
                        </div>
                        <!-- /. ROW  -->
                        <hr />

                        <!--  Modals-->

                        <div class="form-group">        
                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                                Create New User
                            </button>
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="adminprocess.php" method="post">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Create New User</h4>
                                            </div>
                                            <div class="modal-body">                                    

                                                <p>
                                                    <input type="text" name="user" placeholder="Username" value="<?php echo Form::value("user"); ?>" />
                                                    <?php echo Form::error("user"); ?>
                                                </p>
                                                <p>
                                                    <input type="text" name="firstname" placeholder="First Name" value="<?php echo Form::value("firstname"); ?>" />
                                                    <?php echo Form::error("firstname"); ?>
                                                </p>
                                                <p>
                                                    <input type="text" name="lastname" placeholder="Last Name" value="<?php echo Form::value("lastname"); ?>" />
                                                    <?php echo Form::error("lastname"); ?>
                                                </p>
                                                <p>
                                                    <input type="password" name="pass" placeholder="Password" value="<?php echo Form::value("pass"); ?>" />
                                                    <?php echo Form::error("pass"); ?>
                                                </p>
                                                <p>
                                                    <input type="password" name="conf_pass" placeholder="Confirm Password" value="<?php echo Form::value("conf_pass"); ?>" />
                                                    <?php echo Form::error("pass"); ?>
                                                </p>
                                                <p>
                                                    <input type="text" name="email" placeholder="E-mail Address" value="<?php echo Form::value("email"); ?>" />
                                                    <?php echo Form::error("email"); ?>
                                                </p>
                                                <p>
                                                    <input type="text" name="conf_email" placeholder="Confirm E-mail Address" value="<?php echo Form::value("conf_email"); ?>" />
                                                    <?php echo Form::error("email"); ?>
                                                </p>

                                                <input type="hidden" name="form_submission" value="admin_registration">                                         

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" id="submit" >Create New User</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- End Modals-->

                        <div class="row">
                            <div class="col-md-12">
                                <!-- Advanced Tables -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Users
                                    </div>
                                    <div class="panel-body">

                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Status</th>
                                                        <th>E-mail</th>
                                                        <th>Registered</th>
                                                        <th>Last Login</th>
                                                        <th class='text-center'>View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM users WHERE username != '" . ADMIN_NAME . "'";
                                                    $result = $db->prepare($sql);
                                                    $result->execute();
                                                    while ($row = $result->fetch()) {
                                                        $email = $row['email'];
                                                        $email = strlen($email) > 25 ? substr($email,0,25)."..." : $email;
                                                        $lastlogin = $adminfunctions->displayDate($row['timestamp']);
                                                        $regdate = $row['regdate'];
                                                        $reg = $adminfunctions->displayDate($row['regdate']);

                                                        echo "<tr><td><a href='" . $configs->getConfig('WEB_ROOT') . "admin/adminuseredit.php?usertoedit=" . $row['username'] . "'>" . $row['username'] . "</a></td><td>" . $adminfunctions->displayStatus($row['username'])  . "</td>"
                                                        . "<td><div class='shorten'><a href='mailto:" . $row['email'] . "'>" . $email . "</a></div></td><td>" . $reg . "</td><td>" . $lastlogin . "</td>"
                                                        . "<td class='text-center'><div class='btn-group btn-group-xs'><a href='".$configs->getConfig('WEB_ROOT')."admin/adminuseredit.php?usertoedit=".$row['username']."' title='Edit' class='open_modal btn btn-default'><i class='fa fa-pencil'></i> View</a>";
                                                        echo"</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <!--End Advanced Tables -->
                            </div>
                        </div>

                        <!-- /. ROW  -->

                        <?php
                        $orderby = 'regdate';
                        $result = $adminfunctions->displayAdminActivation($orderby);
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <!--   Kitchen Sink -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Users Awaiting Activation
                                    </div>
                                    <div class="panel-body">

                                        <form class="form-horizontal" role="form" action="adminprocess.php" method="POST">

                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="dataTable2">
                                                    <thead>
                                                        <tr>
                                                            <th>Username</th>
                                                            <th>Status</th>
                                                            <th>E-mail</th>
                                                            <th>Registered</th>
                                                            <th class='text-center'>View</th>
                                                            <th><input type="checkbox" class="checkall"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = $result->fetch()) {
                                                            $regdate = $row['regdate'];
                                                            $reg = date("j M, y - g:i a", $regdate);
                                                            $email = $row['email'];
                                                            $email = strlen($email) > 25 ? substr($email,0,25)."..." : $email;
                                                            echo "<tr><td><a href='" . $configs->getConfig('WEB_ROOT') . "admin/adminuseredit.php?usertoedit=" . $row['username'] . "'>" . $row['username']
                                                            . "</a></td><td>". $adminfunctions->displayStatus($row['username'])  ."</td>"
                                                            . "<td><div class='shorten'><a href='mailto:" . $row['email'] . "'>" . $email . "</a></div></td>"
                                                            . "<td>" . $reg. "</td>"
                                                            . "<td class='text-center'><div class='btn-group btn-group-xs'><a href='".$configs->getConfig('WEB_ROOT')."admin/adminuseredit.php?usertoedit=".$row['username']."' title='Edit' class='open_modal btn btn-default'><i class='fa fa-pencil'></i> View</a>"
                                                            . "<td><input name='user_name[]' type='checkbox' value='" . $row['username'] . "' /></td>"
                                                            . "</tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <input type="hidden" name="form_submission" value="activate_users">
                                                <button type="submit" id="submit" name="submit" class="btn btn-default"><i class=" fa fa-refresh "></i> Activate Users</button>
                                        </form>

                                    </div>
                                </div>
                                <!-- End  Kitchen Sink -->
                            </div>
                        </div>
                        <!-- /. ROW  -->

                    </div>       
                </div>
                <!-- /. PAGE INNER  -->
            </div>
            <!-- /. PAGE WRAPPER  -->
            <!-- /. WRAPPER  -->
            
            <!-- SCRIPTS -AT THE BOTTOM TO REDUCE THE LOAD TIME-->
            <!-- JQUERY SCRIPTS -->
            <script src="assets/js/jquery-1.10.2.js"></script>
            <!-- BOOTSTRAP SCRIPTS -->
            <script src="assets/js/bootstrap.min.js"></script>
            <!-- METISMENU SCRIPTS -->
            <script src="assets/js/jquery.metisMenu.js"></script>
            <!-- DATA TABLE SCRIPTS -->
            <script src="assets/js/dataTables/jquery.dataTables.js"></script>
            <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
            <script>
                $(document).ready(function () {
                    $('#dataTable').dataTable();
                });

                $(document).ready(function () {
                    $('#dataTable2').dataTable();
                 });
            </script>
            
            <!-- Check all (set for closest table) -->
            <script>
            $(function () {
            $('.checkall').on('click', function () {
            $(this).closest('table').find(':checkbox').prop('checked', this.checked);
            });
            });
            </script>
            
            <!-- CUSTOM SCRIPTS -->
            <script src="assets/js/custom.js"></script>

        </body>
    </html>
    <?php
}
?>