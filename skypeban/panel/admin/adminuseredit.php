<?php
include("../includes/controller.php");

if(!$session->isAdmin() OR !isset($_GET['usertoedit']) OR $_GET['usertoedit'] == ADMIN_NAME){
   header("Location: ".$configs->homePage());
   exit;
}
$page = 'adminuseredit';
$adminfunctions = new Adminfunctions($db, $functions, $configs);
if (isset($_GET['usertoedit'])) { $usertoedit = $_GET['usertoedit']; }
if (!$functions->usernameTaken($usertoedit)) { header("Location: ".$configs->homePage()); exit; }
$req_user_info = $functions->getUserInfo($usertoedit);
$admin_regdate = $functions->getUserInfo($session->username);
$form = new Form();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Angry Frog : Admin : Admin User Edit</title>
        <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
        <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   
        <!-- Blocks -->
    <link href="assets/css/main.css" rel="stylesheet" />

    <style>.form-horizontal .control-label { padding-top: 9px; }</style>

        <!-- Chosen (Styled Select Boxes) -->
    <link rel="stylesheet" href="assets/css/chosen.css" />  
    
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
            <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;"> Last access : <?php echo $adminfunctions->displayDate($admin_regdate['previous_visit']); ?> &nbsp; <a href="../includes/process.php" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
        <?php include ('side_navigation.php'); ?>  
        
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Admin User Edit - <?php echo $usertoedit; ?></h3>
                                <p>Edit Individual User accounts.</p>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
                <div class="row">
                    <div class="col-md-12">             
                    <div class="block full tabbable">
                        <!-- Block Tabs Title -->
                        <div class="block-title">
                            <ul class="nav nav-tabs push">
                                <li class="active"><a href="#pane1" data-toggle="tab">General Info</a></li>
                                <li><a href="#pane2" data-toggle="tab">Edit Account</a></li>
                                <li><a href="#pane3" data-toggle="tab">Group Membership</a></li>
                                <li><a href="#pane4" data-toggle="tab">Other Admin Features</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
        <div id="pane1" class="tab-pane active">
                            
        <form class="form-horizontal" method="POST" role="form" action="adminprocess.php">
            
            <div class="form-group">
                <label for="username" class="col-sm-4 col-md-3 col-lg-2 control-label">Username:</label>
                <div class="col-sm-5 col-md-5">
                    <p class="form-control-static"><?php echo $usertoedit; ?></p>
                </div>
            </div> 
                        
            <div class="form-group">
                <label for="status" class="col-sm-4 col-md-3 col-lg-2 control-label">Status:</label>
                <div class="col-sm-5 col-md-5">
                    <p class="form-control-static"><?php echo $adminfunctions->displayStatus($usertoedit); ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="registered" class="col-sm-4 col-md-3 col-lg-2 control-label">Registered:</label>
                <div class="col-sm-5 col-md-5">
                    <p class="form-control-static"><?php echo $adminfunctions->displayDate($req_user_info['regdate']); ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="lastactivedate" class="col-sm-4 col-md-3 col-lg-2 control-label">Last Active:</label>
                <div class="col-sm-5 col-md-5">
                    <p class="form-control-static"><?php echo $adminfunctions->displayDate($req_user_info['timestamp']); ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="registeredfromip" class="col-sm-4 col-md-3 col-lg-2 control-label">Registered IP:</label>
                <div class="col-sm-5 col-md-5">
                    <p class="form-control-static"><?php echo $req_user_info['ip']; ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="lastactiveip" class="col-sm-4 col-md-3 col-lg-2 control-label">Last Active IP:</label>
                <div class="col-sm-5 col-md-5">
                    <p class="form-control-static"><?php echo $req_user_info['lastip']; ?></p>
                </div>
            </div>
                                    
            <div class="form-group">
                <label for="registeredfromip" class="col-sm-4 col-md-3 col-lg-2 control-label">First Name:</label>
                <div class="col-sm-4 col-md-4">
                    <p class="form-control-static"><?php echo $req_user_info['firstname']; ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <label for="lastactiveip" class="col-sm-4 col-md-3 col-lg-2 control-label">Last Name:</label>
                <div class="col-sm-4 col-md-4">
                    <p class="form-control-static"><?php echo $req_user_info['lastname']; ?></p>
                </div>
            </div>
            
        </form>
        </div>
                            
        <div id="pane2" class="tab-pane">

            <form class="form-horizontal" method="POST" role="form" action="adminprocess.php">
                
                <div class="form-group">
                    <div class="col-sm-4 col-md-3 col-lg-2">
                    </div>                    
                    <div class="col-sm-4 col-md-4">
                        <p class="form-control-static">Edit the User's Details</p>
                    </div>
                </div>
            
            <div class="form-group <?php if(Form::error("username")){ echo 'has-error'; } ?>">
                <label for="inputUsername" class="col-sm-4 col-md-3 col-lg-2 control-label">Username:</label>
                <div class="col-sm-4 col-md-4">
                    <input name="username" type="text" class="form-control" id="inputUsername" placeholder="Username" value="<?php if(Form::value("username") == ""){ echo $req_user_info['username']; } else { echo Form::value("username"); } ?>">                            
                </div>
                <div class="col-sm-4">
                    <small><?php echo Form::error("username"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("firstname")){ echo 'has-error'; } ?> ">
                <label for="inputFirstname" class="col-sm-4 col-md-3 col-lg-2 control-label">First Name:</label>
                <div class="col-sm-4 col-md-4">
                    <input type="text" name="firstname" class="form-control" id="inputFirstname" placeholder="First Name" value="<?php if(Form::value("firstname") == ""){ echo $req_user_info['firstname']; } else { echo Form::value("firstname"); } ?>">                             
                </div>
                <div class="col-sm-4">
                    <small><?php echo Form::error("firstname"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("lastname")){ echo 'has-error'; } ?>">
                <label for="inputLastname" class="col-sm-4 col-md-3 col-lg-2 control-label">Last Name:</label>
                <div class="col-sm-4 col-md-4">
                    <input type="text" name="lastname" class="form-control" id="inputLastname" placeholder="Last Name" value="<?php if(Form::value("lastname") == ""){ echo $req_user_info['lastname']; } else { echo Form::value("lastname"); }?>">
                </div>
                <div class="col-sm-4">
                    <small><?php echo Form::error("lastname"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("newpass")){ echo 'has-error'; } ?>">
                <label for="inputPassword" class="col-sm-4 col-md-3 col-lg-2 control-label">New Password:</label>
                 <div class="col-sm-4 col-md-4">
                    <input type="password" name="newpass" class="form-control" id="inputPassword" placeholder="New Password">
                </div>
                <div class="col-sm-4">
                    <small><?php echo Form::error("newpass"); ?></small>
                </div>
            </div>
            
            <div class="form-group <?php if(Form::error("conf_newpass")){ echo 'has-error'; } ?>">
                <label for="confirmPassword" class="col-sm-4 col-md-3 col-lg-2 control-label">Confirm Password:</label>
                <div class="col-sm-4 col-md-4">
                    <input type="password" name="conf_newpass" class="form-control" id="confirmPassword" placeholder="Confirm Password">
                </div>
                <div class="col-sm-4">
                    <small><?php echo Form::error("conf_newpass"); ?></small>
                </div>
            </div>
                        
            <div class="form-group <?php if(Form::error("email")){ echo 'has-error'; } ?>">
                <label for="email" class="col-sm-4 col-md-3 col-lg-2 control-label">E-mail:</label>
                <div class="col-sm-4 col-md-4">
                    <input name="email" type="text" id="email" class="form-control" value="<?php if(Form::value("email") == ""){ echo $req_user_info['email']; }else{ echo Form::value("email"); } ?>">
                </div>
                <div class="col-sm-4">
                    <small><?php echo Form::error("email"); ?></small>
                </div>
            </div>
            
            <p></p>
            <div class="form-group">
                <div class="col-sm-4 col-md-3 col-lg-2"></div>
                <div class="col-sm-4 col-md-4">
                    <?php echo $adminfunctions->stopField($session->username, 'edit-user'); ?>
                    <button type="submit" id="submit" name="button" value="Edit Account" class="btn btn-default"><i class=" fa fa-refresh "></i> Submit Changes</button>
                    <button type="reset" id="reset" name="reset" class="btn btn-primary">Reset </button>
                </div>
            </div>
            <input type="hidden" name="form_submission" value="edit_user">
            <input type="hidden" name="usertoedit" value="<?php echo $usertoedit; ?>">
        </form>
                    </div>
                            
                    <div id="pane3" class="tab-pane">
                        <form action="adminprocess.php" method="post" class="form-horizontal form-bordered">   

                            <?php
                            $userid = $req_user_info['id'];
                            $sql2 = "SELECT group_id FROM users_groups WHERE user_id = '$userid'";
                            $result2 = $db->prepare($sql2);
                            $result2->execute();
                            ?>
                            
                            <div class="form-group">
                                <div class="col-sm-4 col-md-3 col-lg-2">
                                </div>                    
                                <div class="col-sm-4 col-md-4">
                                    <p class="form-control-static">Edit the User's Group Membership</p>
                                    Click the box to add to more...
                                </div>
                            </div>
                            
                            <?php
                            // Instantiate array incase empty
                            $group_array = array();
                            while ($row2 = $result2->fetch()) {
                                $group_array[] = $row2['group_id'];
                            } 
                            ?>
                            
                            <div class="form-group">
                                <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="edit-group-membership">Current Groups</label>
                                <div class="col-md-3">
                                    <select name="groups[]" id="edit-group-membership" data-placeholder="Choose a Group..." class="chosen-select" multiple style="width:350px;" tabindex="4">
                                        <option value=""></option>
                                        <?php
                                        $sql = "SELECT * FROM `groups` WHERE group_id != '1'";
                                        $result = $db->prepare($sql);
                                        $result->execute();
                                        while ($row = $result->fetch()) {
                                            echo "<option value='" . $row['group_id'] . "'";
                                            if (in_array($row['group_id'], $group_array)) {
                                                echo " selected ";
                                            }
                                            echo ">" . $row['group_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-4 col-md-3 col-lg-2"></div>
                                <div class="col-sm-4 col-md-4">
                                    <?php echo $adminfunctions->stopField($session->username, 'edit-groups'); ?>
                                    <input type="hidden" name="form_submission" value="edit_group_membership">
                                    <input type="hidden" name="usertoedit" value="<?php echo $usertoedit; ?>">
                                    <button type="submit" id="submit" name="button" value="Change Groups" class="btn btn-default"><i class=" fa fa-refresh "></i> Submit Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                            
                    <div id="pane4" class="tab-pane">
                        <form class="form-horizontal" method="POST" role="form" action="adminprocess.php">
                            
                            <div class="form-group">
                                <div class="col-sm-4 col-md-3 col-lg-2">
                                </div>                    
                                <div class="col-sm-4 col-md-4">
                                    <p class="form-control-static">Other Admin Features</p>
                                </div>
                            </div>
                            
                        <div class="form-group">
                            <div class="col-sm-4 col-md-3 col-lg-2"></div>   
                            <div class="col-sm-6 col-md-6">
                                <?php echo $adminfunctions->stopField($session->username, 'delete-user'); ?>
                                <input type="hidden" name="form_submission" value="delete_user">
                                <input type="hidden" name="usertoedit" value="<?php echo $usertoedit; ?>">
                                <button type="submit" id="submit" name="button" <?php if(($functions->checkBanned($usertoedit))) { echo "value='unban User'"; } else { echo "value='Ban User'"; } ?><?php if(($functions->checkBanned($usertoedit))) { echo "class='btn btn-primary'";  } else { echo "class='btn btn-warning'"; } ?> ><i class=" fa fa-refresh "></i> <?php if(($functions->checkBanned($usertoedit))) { echo "UnBan User"; } else { echo "Ban User"; } ?></button>
                                <?php if ($session->isSuperAdmin()){ ?>
                                    <button type="submit" id="submit" name="button" <?php if ($functions->getUserInfoSingular('userlevel', $usertoedit) != '9') { echo "value='Promotetoadmin'"; } else { echo "value='Demotefromadmin'"; } ?> class="btn btn-default" onclick="return confirm ('Are you sure you want to promote or demote this user?\n\n' + 'Click OK to continue or Cancel to Abort!')"><i class=" fa <?php if ($functions->getUserInfoSingular('userlevel', $usertoedit) != '9') { echo "fa-arrow-up"; } else { echo "fa-arrow-down"; } ?> "></i> <?php if ($functions->getUserInfoSingular('userlevel', $usertoedit) != '9') { echo "Promote to Admin"; } else { echo "Demote from Admin"; } ?></button> 
                                <?php } ?>
                                <button type="submit" id="submit" name="button" value="Delete" class="btn btn-danger" onclick="return confirm ('Are you sure you want to delete this user, this cannot be undone?\n\n' + 'Click OK to continue or Cancel to Abort!')"><i class=" fa fa-times "></i> Delete User</button>
                            </div>
                        </div>
                        </form>
                    </div>
            </div>

        </div>
        <!-- End Form Elements -->

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

      <!-- Chosen (Stylised Select Boxes) -->
    <script src="assets/js/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
      var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
      }
      for (var selector in config) {
        $(selector).chosen(config[selector]);
      }
    </script>
    
</body>
</html>