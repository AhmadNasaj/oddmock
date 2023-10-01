<?php
include("../includes/controller.php");
$adminfunctions = new Adminfunctions($db, $functions, $configs);

if (!$session->isAdmin()) {
    header("Location: " . $configs->homePage());
    exit;
} else {
    $form = new Form();
    $page = 'usergroups';
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Angry Frog : Admin : User Groups</title>
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
                                <h3>User Groups</h3>
                                <p>
                                    Create, view and edit user groups. Assign users to user groups.
                                </p>
                            </div>
                        </div>
                        <!-- /. ROW  -->
                        <hr />

                        <div class="form-group">        
                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                                Create New Group
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Groups
                                    </div>
                                    <div class="panel-body">

                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTable1">
                                                <thead>
                                                    <tr>
                                                        <th>Group Name</th>
                                                        <th>Group Level</th>
                                                        <th># of Members</th>
                                                        <th class='text-center'>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql = "SELECT * FROM `groups` WHERE group_id != '1'";
                                                    $result = $db->prepare($sql);
                                                    $result->execute();
                                                    $stop = $adminfunctions->createStop($session->username, 'delete-group');
                                                    // If SuperAdmin allow viewing of Administrators Group
                                                    if ($session->isSuperAdmin()) { 
                                                        echo "<tr><td>Administrators</td><td>1</td><td>" . $functions->checkGroupNumbers($db, 1) . "</td>"; 
                                                        echo "<td class='text-center'><div class='btn-group btn-group-xs'><a href='#' data-logid='1' data-target='#editGroups' data-toggle='modal' title='Edit' class='open_modal btn btn-default'><i class='fa fa-pencil'></i></a></td></tr>";
                                                    }
                                                    while ($row = $result->fetch()) {
                                                        echo "<tr><td>" . $row['group_name'] . "</td><td>" . $row['group_level'] . "</td><td>" . $functions->checkGroupNumbers($db, $row['group_id']) . "</td>";
                                                        echo "<td class='text-center'><div class='btn-group btn-group-xs'><a href='#' data-logid='" . $row['group_id'] . "' data-target='#editGroups' data-toggle='modal' title='Edit' class='open_modal btn btn-default'><i class='fa fa-pencil'></i></a>";
                                                        echo "<a href='adminprocess.php?delete=". $row['group_id']  ."&stop=". $stop ."&form_submission=delete_group' title='Delete' class='btn btn-danger confirmation'><i class='fa fa-times'></i></a></div></td></tr>";
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
                        
                        <!--  Modals -->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content" id="modal-content">
                                    <form action="adminprocess.php" class="form-horizontal" method="post">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Create a New Group</h4>
                                        </div>
                                        <div class="modal-body" id="modal-body">                                   
                                            <div class="form-group">
                                                <label for="sitedesc" class="col-sm-4 control-label">New Group Name : </label>
                                                <div class="col-md-8">
                                                    <input type="text" name="group_name" class="form-control" placeholder="Group Name" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="sitedesc" class="col-sm-4 control-label">Assign Group Level : </label>
                                                <div class="col-md-8">
                                                    <input type="text" name="group_level" class="form-control" placeholder="Group Level - Enter a number between 2 - 99" data-toggle="tooltip" data-placement="bottom" title="A Group Level is another means of protecting content. For example, protect pages from those in groups lower than level 5." />
                                                </div>
                                            </div>                                       
                                        </div>
                                        <div class="modal-footer">
                                            <input type="hidden" name="form_submission" value="group_creation">  
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" id="submit">Create Group</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editGroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" id="modal-info">
                                <!-- Content is dynamically pulled from editgroup.php -->
                            </div>
                        </div>
                        <!-- End Modals-->


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
            $('#dataTable1').dataTable();
            });
            </script>
            
            <script type="text/javascript">
            $('.confirmation').on('click', function () {
            return confirm('Are you sure you wish to delete this group? It will remove all users from the group.');
            });
            </script>
            
            <!-- This dynamically loads the Modal with the Group Info -->
            <script>
            $(document).on("click", ".open_modal", function () {
            var group_id = $(this).data('logid');
            $("#modal-info").load("editgroup.php?log_id=" + group_id);
            });
            </script>
            
            <script>
            $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            });
            </script>
            
            <!-- CUSTOM SCRIPTS -->
            <script src="assets/js/custom.js"></script>
            
        </body>
    </html>

    <?php
}
?>