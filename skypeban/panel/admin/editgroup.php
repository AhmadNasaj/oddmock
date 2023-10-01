<?php
include("../includes/controller.php");

if (!$session->isAdmin()) {
    echo "You are no longer logged in.";
    exit;
} else {
    
if (!empty($_GET['log_id'])) {
    $logid = $_GET['log_id'];
    $groupinfo = $functions->returnGroupInfo($db, $logid);
    $adminfunctions = new Adminfunctions($db, $functions, $configs);
} else {
    header("Location: " . $configs->homePage());
    exit;
}
// Protect Administrators Group fron Non Super Admin
if (($_GET['log_id'] == '1') && !$session->isSuperAdmin()) {
    header("Location: " . $configs->homePage());
    exit;
}
?>

<script src="assets/js/jquery-1.10.2.js"></script>
            
<div class="modal-content" id="modal-content">
    <form action="adminprocess.php" class="form-horizontal" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Edit Group</h4>
        </div>
        <div class="modal-body" id="modal-body">                                   

            <div class="form-group">
                <label for="sitedesc" class="col-sm-3 control-label">Group Name : </label>
                <div class="col-md-9">
                    <input type="text" name="group_name" class="form-control" placeholder="Group Name" value="<?php echo $groupinfo['group_name']; ?>" <?php if ($groupinfo["group_name"] == 'Administrators') { echo 'disabled'; } ?> />
                </div>
            </div>
            <div class="form-group">
                <label for="sitedesc" class="col-sm-3 control-label">Group Level : </label>
                <div class="col-md-9">
                    <input type="text" name="group_level" class="form-control" placeholder="Group Level" value="<?php echo $groupinfo["group_level"]; ?>" <?php if ($groupinfo["group_level"] == '1') { echo 'disabled'; } ?> />
                </div>
            </div>
            
                <div class="panel panel-default" style="margin-top: 30px;">
                    <div class="panel-heading">
                        Members
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTable2">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th class='text-center'>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT users.username, users.id, users_groups.group_id FROM `users` INNER JOIN `users_groups` ON users.id=users_groups.user_id WHERE users_groups.group_id = '$logid' ORDER BY users.username ASC";
                                    $result = $db->prepare($sql);
                                    $result->execute();
                                    $stop = $adminfunctions->createStop($session->username, 'delete-groupmembership');
                                    while ($row = $result->fetch()) {
                                        echo "<tr><td>" . $row['username'] . "</td>";
                                        echo "<td class='text-center'><div class='btn-group btn-group-xs'>";
                                        if ($row['username'] != ADMIN_NAME) { echo "<a href='adminprocess.php?remove=" . $row['id'] . "&group_id=" . $row['group_id'] . "&stop=" . $stop . "&form_submission=remove_groupmember' title='Remove Group Member' class='btn btn-danger confirmation'>";
                                        echo "<i class='fa fa-times'></i></a></div>"; }
                                        echo "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <input type="hidden" name="form_submission" value="edit_group">
            <input type="hidden" name="group_id" value="<?php echo $logid; ?>"> 

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="submit" >Edit Group</button>
        </div>
    </form>
</div>

    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
    $(document).ready(function () {
    $('#dataTable2').dataTable();
    });
    </script>
    
    <script type="text/javascript">
    $('.confirmation').on('click', function () {
    return confirm('Are you sure you wish to delete the user from this group?');
    });
    </script> 
    
<?php } ?>