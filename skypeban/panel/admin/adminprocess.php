<?php

include_once '../includes/controller.php';

/**
 * adminprocess.php
 * 
 * The AdminProcess class is meant to simplify the task of processing admin 
 * submitted forms from the admin center, these deal with member system adjustments.
 *
 * Last Updated: 6th January, 2014
 */
if (!$session->isAdmin()) {
    header("Location: " . $configs->homePage());
    exit;
}

$adminfunctions = new Adminfunctions($db, $functions, $configs);

$form_submission = (isset($_POST['form_submission']) ) ? $_POST['form_submission'] : $_GET['form_submission'];
switch ($form_submission) {

    case "activate_users" :
        activateUsers($db, $configs, $functions);
        break;
    case "admin_registration" :
        adminRegister($db, $session, $configs, $functions);
        break;
    case "delete_inactive" :
        deleteInactive($db, $configs);
        break;
    case "disallow_user" :
        disallowUsername($db, $configs, $functions);
        break;
    case "undisallow_user" :
        unDisallowUsername($db, $configs, $functions);
        break;
    case "group_creation" :
        groupCreation($db);
        break;
    case "edit_group" :
        editGroup($db);
        break;
    case "edit_group_membership" :
        editGroupMembership($db, $session, $adminfunctions, $functions, $configs);
        break;
    case "remove_groupmember" :
        removeFromGroup($db, $configs, $session, $adminfunctions);
        break;
    case "delete_group" :
        deleteGroup($db, $session, $adminfunctions, $configs);
        break;
    case "ban_ip" :
        banIp($db, $configs, $adminfunctions);
        break;
    case "unban_ip" :
        deleteBanIp($db, $configs, $adminfunctions);
        break;
    case "config_edit" :
        configEdit($adminfunctions, $configs, $session);
        break;
    case "registration_edit" :
        regConfigEdit($adminfunctions, $configs, $session);
        break;
    case "session_edit" :
        sessionConfigEdit($adminfunctions, $configs, $session);
        break;
    case "edit_user" :
        if (isset($_POST['button']) && ($_POST['button'] == "Edit Account")) {
            editAccount($adminfunctions, $db, $functions, $configs, $session);
        }
        break;
    case "delete_user" :
        if (isset($_POST['button']) && ($_POST['button'] == "Ban User")) {
            banUser($db, $configs, $functions);
        } else
        if (isset($_POST['button']) && ($_POST['button'] == "unban User")) {
            unBanUser($db, $configs, $functions);
        } else
        if (isset($_POST['button']) && ($_POST['button'] == "Promotetoadmin")) {
            promoteUserToAdmin($db, $configs, $functions, $session);
        } else
        if (isset($_POST['button']) && ($_POST['button'] == "Demotefromadmin")) {
            demoteUserFromAdmin($db, $configs, $functions, $session);
        } else
        if (isset($_POST['button']) && ($_POST['button'] == "Delete")) {
            deleteUser($db, $adminfunctions, $functions, $configs, $session);
        }
        break;
    default :
        header("Location: " . $configs->homePage());
}

/**
 * *************************************************************************
 * adminRegister - Admin process for creating users from the Admin Panel.
 * *************************************************************************
 */
function adminRegister($db, $session, $configs, $functions) {
    $registration = new Registration($db, $session, $configs, $functions);

    /* Convert username to all lowercase (by option) */
    if ($configs->getConfig('ALL_LOWERCASE') == 1) {
        $_POST['user'] = strtolower($_POST['user']);
    }

    $retval = $registration->register($_POST['user'], $_POST['firstname'], $_POST['lastname'], $_POST['pass'], $_POST['conf_pass'], $_POST['email'], $_POST['conf_email'], 1);

    /* Registration Successful */
    if ($retval == 0) {
        $_SESSION['reguname'] = $_POST['user'];
        $_SESSION['regsuccess'] = 0;
        header("Location: summary.php");
    }

    /* Error found with form */ else if ($retval == 1) {
        $_SESSION['reguname'] = $_POST['user'];
        $_SESSION['regsuccess'] = 2;
        header("Location: summary.php");
    }

    /* Registration attempt failed */ else if ($retval == 2) {
        $_SESSION['reguname'] = $_POST['user'];
        $_SESSION['regsuccess'] = 2;
        header("Location: summary.php");
    }
}

/**
 * ****************************************************************************************
 * deleteUser - If the submitted username is correct, the user is deleted from the database.
 * ****************************************************************************************
 */
function deleteUser($db, $adminfunctions, $functions, $configs, $session) {

    /* Username error checking */
    $user = $adminfunctions->checkUsername($_POST['usertoedit']);

    /* Errors exist, have user correct them */
    if (Form::$num_errors > 0) {
        $_SESSION['value_array'] = $_POST;
        $_SESSION['error_array'] = Form::getErrorArray();
        header("Location: " . $session->referrer);
    } else {
        /* Syncronizer Token Check */
        if (isset($_POST['delete-user'])) {
            $stop = $_POST['delete-user'];
        } else {
            $stop = '';
        }
        if ($adminfunctions->verifyStop($session->username, 'delete-user', $stop) == '2') {
            
            /* Delete from Groups First */
            $userid = $functions->getUserInfoSingular('id', $user);
            $query = $db->prepare("DELETE FROM users_groups WHERE user_id = '$userid'");
            $query->execute();
            
            /* Delete from banlist */
            $userid = $functions->getUserInfoSingular('id', $user);
            $query = $db->prepare("DELETE FROM banlist WHERE ban_userid = '$userid' LIMIT 1");
            $query->execute();
            
            /* Delete user from database */
            $stmt = $db->prepare("DELETE FROM " . TBL_USERS . " WHERE username = :username LIMIT 1");
            $stmt->execute(array(':username' => $user));
            
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
        } else {
            //Syncroniser Token does not match - log user off
            header("Location: " . $configs->getConfig('WEB_ROOT') . "/process.php");
        }
    }
}

/**
 * ********************************************************************************************************
 * deleteInactive - All inactive users are deleted from the database, not including administrators. 
 * Inactivity is defined by the number of days specified that have gone by that the user has not logged in.
 * ********************************************************************************************************
 */
function deleteInactive($db, $configs) {
    
    /* Syncronizer Token Check */
    if (isset($_POST['delete-inactive'])) {
        $stop = $_POST['delete-inactive'];
    } else {
        $stop = '';
    }
    
    if ($adminfunctions->verifyStop($session->username, 'delete-inactive', $stop) == '2') {
        
    $time = time();
    $inact_time = $time - $_POST['inactdays'] * 24 * 60 * 60;
    $sql = $db->prepare("DELETE FROM " . TBL_USERS . " WHERE timestamp < $inact_time AND userlevel != " . SUPER_ADMIN_LEVEL);
    $sql->execute();
    header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
    
    /* Syncroniser Token does not match - log user off */
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
}

/**
 * ***********************************************************************************************
 * banUser - If the submitted username is correct the user ID is added to the banned users table.
 * ***********************************************************************************************
 */
function banUser($db, $configs, $functions) {

    /* Username error checking */
    if(!empty($_POST['usertoedit'])){
        $banned_user = $_POST['usertoedit'];
        if ($functions->checkBanned($banned_user)) {
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
            exit;
        
        } else {

        /* Ban user from member system */
        $banuserid = $functions->getUserInfoSingular('id', $banned_user);
        $time = time();
        $sql = $db->prepare("INSERT INTO " . TBL_BANNED_USERS . " (ban_userid, timestamp) VALUES ('$banuserid', $time)");
        $sql->execute();
        header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/adminuseredit.php?usertoedit=" . $banned_user);
        }
    }
    header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
}

/* 
 * *****************************************
 * unBanUser - function that unbans users. 
 * *****************************************
 */
function unBanUser($db, $configs, $functions) {

    /* Username error checking */
    $banned_user = $_POST['usertoedit'];
    if (!$functions->checkBanned($banned_user)) {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
    }

    /* UnBan user from member system */
    $banuserid = $functions->getUserInfoSingular('id', $banned_user);
    $sql = $db->prepare("DELETE FROM " . TBL_BANNED_USERS . " WHERE ban_userid = '$banuserid'");
    $sql->execute();
    header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/adminuseredit.php?usertoedit=" . $banned_user);
}

/* 
 * *****************************************************
 * promoteUserToAdmin - Promote User to Admin Level 9. 
 * *****************************************************
 */
function promoteUserToAdmin($db, $configs, $functions, $session){
    
    if(!empty($_POST['usertoedit']) && $session->isSuperAdmin()){
        
        $user_to_promote = $_POST['usertoedit'];
        if ($functions->getUserInfoSingular('userlevel', $username_to_promote) == '9') {
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
        } else {
            // Update to Level 9
            $functions->updateUserField($user_to_promote, 'userlevel', '9');
            
            // Add to Administrators Group
            $user_id = $functions->getUserInfoSingular('id', $user_to_promote);
            $promote = $db->prepare("INSERT INTO users_groups (user_id, group_id) VALUES (:userid, '1')");
            $promote->execute(array(':userid' => $user_id));
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
        }
        
    }
    header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
}

/* 
 * *********************************************************************
 * demoteUserFromAdmin - Demote User and return them to registered user. 
 * *********************************************************************
 */
function demoteUserFromAdmin($db, $configs, $functions, $session){
    
    if(!empty($_POST['usertoedit'])&& $session->isSuperAdmin()){
        
        $user_to_demote = $_POST['usertoedit'];
        if ($functions->getUserInfoSingular('userlevel', $user_to_demote) != '9') {
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
        } else {
            // Update to Level 3
            $functions->updateUserField($user_to_demote, 'userlevel', '3');
            
            // Add to Administrators Group
            $user_id = $functions->getUserInfoSingular('id', $user_to_demote);
            $promote = $db->prepare("DELETE FROM users_groups WHERE user_id = :userid AND group_id = '1' LIMIT 1");
            $promote->execute(array(':userid' => $user_id));
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
        }
        
    }
    header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/useradmin.php");
}

/* 
 * *********************************************************
 * disallowUsername - Disallows a username from registration
 * *********************************************************
 */
function disallowUsername($db, $configs) {
    if (!empty($_POST['usernametoban'])) {
        $time = time();
        $usernametoban = $_POST['usernametoban'];
        $sql = $db->prepare("INSERT INTO banlist (ban_username, timestamp) VALUES ('$usernametoban', '$time')");
        $sql->execute();
    }
    header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/security.php"); // success
}

/* 
 * *****************************************************************************
 * unDisallowUsername - Removes a username from being disallowed at registration
 * *****************************************************************************
 */
function unDisallowUsername($db, $configs) {
    if (isset($_POST['username_tounban'])) {
        $ban_id = $_POST['username_tounban'];
        $sql = $db->prepare("DELETE FROM banlist WHERE ban_id = '$ban_id'");
        $sql->execute();
    }
    header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/security.php"); // success
}

/* 
 * ****************************************************************************
 * banIp - Adds an IP address to the banned ip list - checked by checkIPFormat
 * ****************************************************************************
 */
function banIp($db, $configs, $adminfunctions) {
    if (isset($_POST['ipaddress'])) {
        $ipaddress = $_POST['ipaddress'];
        if ($adminfunctions->checkIPFormat($ipaddress)) {
            $time = time();
            $sql = $db->prepare("INSERT INTO banlist (ban_ip, timestamp) VALUES ('$ipaddress', '$time')");
            $sql->execute();
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/security.php"); // success
        } else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = Form::getErrorArray();
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/security.php"); // failure
        }
    } header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/security.php"); // No IP, refresh page
}

/* 
 * ************************************************************
 * deleteBanIp - Removes an IP address from the banned ip list
 * ************************************************************ 
 */
function deleteBanIp($db, $configs, $adminfunctions) {
    if (isset($_POST['ipaddress'])) {
        $ipaddress = $_POST['ipaddress'];
        if ($adminfunctions->checkIPFormat($ipaddress)) {
            $sql = $db->prepare("DELETE FROM banlist WHERE ban_ip = '$ipaddress'");
            $sql->execute();
        } else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = Form::getErrorArray();
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/security.php"); // failure
        }
    } header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/security.php");
}

/**
 * *********************************************************************************************************
 * configEdit - function for updating the website configurations in the configuration table in the database.
 * *********************************************************************************************************
 */
function configEdit($adminfunctions, $configs, $session) {

    /* Syncronizer Token Check */
    if (isset($_POST['configs'])) {
        $stop = $_POST['configs'];
    } else {
        $stop = '';
    }
    if ($adminfunctions->verifyStop($session->username, 'configs', $stop) == '2') {

        /* Account edit attempt */
        $retval = $configs->editConfigs($_POST['sitename'], $_POST['sitedesc'], $_POST['emailfromname'], $_POST['adminemail'], $_POST['webroot'], $_POST['home_page'], $_POST['login_page'], $_POST['date_format']);

        /* Account edit successful */
        if ($retval) {
            $_SESSION['configedit'] = true;
            header("Location: ../admin/configurations.php");
        } else {
            /* Error found with form */
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = Form::getErrorArray();
            header("Location: ../admin/configurations.php");
        }
        /* Syncroniser Token does not match - log user off */
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
}

/**
 * *************************************************************************************************************************
 * regConfigEdit - function for updating the website registration configurations in the configuration table in the database.
 * *************************************************************************************************************************
 */
function regConfigEdit($adminfunctions, $configs, $session) {

    if (isset($_POST['registration'])) {
        $stop = $_POST['registration'];
    } else {
        $stop = '';
    }
    if ($adminfunctions->verifyStop($session->username, 'registration', $stop) == '2') {

        /* Account edit attempt */
        $retval = $configs->editRegConfigs($_POST['activation'], $_POST['limit_username_chars'], $_POST['min_user_chars'], $_POST['max_user_chars'], $_POST['min_pass_chars'], $_POST['max_pass_chars'], $_POST['send_welcome'], $_POST['enable_capthca'], $_POST['all_lowercase'], $_POST['allow_dupe_email']);

        /* Account edit successful */
        if ($retval) {
            $_SESSION['configedit'] = true;
            header("Location: ../admin/registration.php");
        } else {
            /* Error found with form */
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = Form::getErrorArray();
            header("Location: ../admin/registration.php");
        }

        /* Syncroniser Token does not match - log user off */
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
}

/**
 * ****************************************************************************************************************
 * sessionConfigEdit - function for updating the website Session configurations in the configuration table in the database.
 * ****************************************************************************************************************
 */
function sessionConfigEdit($adminfunctions, $configs, $session) {

    if (isset($_POST['session'])) {
        $stop = $_POST['session'];
    } else {
        $stop = '';
    }
    if ($adminfunctions->verifyStop($session->username, 'session', $stop) == '2') {

        /* Account edit attempt */
        $retval = $configs->editSessConfigs($_POST['user_timeout'], $_POST['guest_timeout'], $_POST['cookie_expiry'], $_POST['cookie_path']);

        /* Account edit successful */
        if ($retval) {
            $_SESSION['configedit'] = true;
            header("Location: ../admin/session.php");
        } else {
            /* Error found with form */
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = Form::getErrorArray();
            header("Location: ../admin/session.php");
        }
        /* Syncroniser Token does not match - log user off */
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
}

/**
 * ***************************************
 * groupCreation - create a new user group
 * ***************************************
 */
function groupCreation($db){
    $int_options = array("options"=>array("min_range"=>2, "max_range"=>99));
    if (filter_var($_POST['group_level'], FILTER_VALIDATE_INT, $int_options)){
       $grouplevel = $_POST['group_level']; 
    } else {
       header("Location: ../admin/usergroups.php");
       exit;
    }
    
    $groupname = (htmlspecialchars($_POST['group_name']));
    
    $sql = $db->prepare("INSERT INTO groups (group_name, group_level) VALUES (:groupname, :grouplevel)");
    $sql->execute(array(':groupname' => $groupname, ':grouplevel' => $grouplevel));
    header("Location: ../admin/usergroups.php");
}

/**
 * **************************************************
 * editGroup - edit a User Groups Name and User Level
 * **************************************************
 */
function editGroup($db){
    $group_id = $_POST['group_id'];
    $int_options = array("options"=>array("min_range"=>2, "max_range"=>256));
    if (filter_var($_POST['group_level'], FILTER_VALIDATE_INT, $int_options)){
       $grouplevel = $_POST['group_level']; 
    } else {
       header("Location: ../admin/usergroups.php");
       exit;
    }
    
    $groupname = (htmlspecialchars($_POST['group_name']));
    
    $sql = $db->prepare("UPDATE groups SET group_name = :groupname, group_level = :grouplevel WHERE group_id = '$group_id'");
    $sql->execute(array(':groupname' => $groupname, ':grouplevel' => $grouplevel));
    header("Location: ../admin/usergroups.php");
}

/**
 * ********************************************************
 * removeFromGroup - Remove an indiviudal user from a Group
 * ********************************************************
 */
function removeFromGroup($db, $configs, $session, $adminfunctions){
    
    // Stop Check
    if (isset($_GET['stop'])) {
        $stop = $_GET['stop'];
    } else {
        $stop = '';
    }
    
    if ($adminfunctions->verifyStop($session->username, 'delete-groupmembership', $stop) == '2') {
        
        $userid = $_GET['remove'];
        $group_id = $_GET['group_id'];
    
        $delete_user_from_group = $db->prepare("DELETE FROM users_groups WHERE group_id = :group_id AND user_id = :userid");
        $delete_user_from_group->execute(array(':group_id' => $group_id, ':userid' => $userid));

        header("Location: ../admin/usergroups.php");
 
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
}

/**
 * ****************************************************************************************
 * editGroupMembership - edits an individual users group membership from adminuseredit page
 * ****************************************************************************************
 */
function editGroupMembership($db, $session, $adminfunctions, $functions, $configs) {
    
    // Stop Check
    if (isset($_POST['edit-groups'])) {
        $stop = $_POST['edit-groups'];
    } else {
        $stop = '';
    }
    
    if ($adminfunctions->verifyStop($session->username, 'edit-groups', $stop) == '2') {
        
        $userid = $functions->getUserInfoSingular('id', $_POST['usertoedit']);

        // Delete user from all groups before adding him to selected ones
        $sql = $db->prepare("DELETE FROM users_groups WHERE user_id = '$userid'");
        $sql->execute();
        
        if (!empty($_POST['groups'])){
            
            foreach ($_POST['groups'] as $value) { 
            $application = $db->prepare("INSERT INTO users_groups (user_id, group_id) VALUES (:userid, '$value')");
            $application->execute(array(':userid' => $userid));
            }
            
        }
        
        header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/adminuseredit.php?usertoedit=" . $_POST['usertoedit']);
        
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
}

/**
 * *************************************
 * deleteGroup - create a new user group
 * *************************************
 */
function deleteGroup($db, $session, $adminfunctions, $configs) {

    if (isset($_GET['stop'])) {
        $stop = $_GET['stop'];
    } else {
        $stop = '';
    }

    if ($adminfunctions->verifyStop($session->username, 'delete-group', $stop) == '2') {

        $group_id = $_GET['delete'];

        $sql = $db->prepare("DELETE FROM groups WHERE group_id = :group_id");
        $done = $sql->execute(array(':group_id' => $group_id));
        if ($done) {
            $delete_users_from_group = $db->prepare("DELETE FROM users_groups WHERE group_id = :group_id");
            $delete_users_from_group->execute(array(':group_id' => $group_id));
        }
        header("Location: ../admin/usergroups.php");
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
}

/**
 * *******************************************************************************
 * activateUsers - function to activate users selected by Admin on the Admin Page.
 * *******************************************************************************
 */
function activateUsers($db, $configs, $functions) {
    $mailer = new Mailer($db, $configs);
    /* Account edit attempt */
    if (isset($_POST['user_name'])) {
        foreach ($_POST['user_name'] as $username) {
            $sql = $db->prepare("UPDATE " . TBL_USERS . " SET USERLEVEL = '3' WHERE username = '$username'");
            $sql->execute();
            $email = $functions->getUserInfoSingular('email', $username);
            $mailer->adminActivated($username, $email);
        }
        header("Location: ../admin/useradmin.php"); //success
    } else {
        header("Location: ../admin/useradmin.php");
    } //no user selected
}

/**
 * *********************************************
 * editAccount - Admin account editing function.
 * *********************************************
 */
function editAccount($adminfunctions, $db, $functions, $configs, $session) {

    // Stop Check
    if (isset($_POST['edit-user'])) {
        $stop = $_POST['edit-user'];
    } else {
        $stop = '';
    }
    if ($adminfunctions->verifyStop($session->username, 'edit-user', $stop) == '2') {

        $username = $_POST['username'];

        /* Account edit attempt */
        $retval = $adminfunctions->adminEditAccount($_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['newpass'], $_POST['conf_newpass'], $_POST['email'], $_POST['usertoedit']);

        /* Account edit successful */
        if ($retval) {
            $_SESSION['adminedit'] = true;
            $_SESSION['usertoedit'] = $_POST['usertoedit'];
            header("Location: " . $configs->getConfig('WEB_ROOT') . "admin/adminuseredit.php?usertoedit=" . $username);
        }
        /* Error found with form */ else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = Form::getErrorArray();
            header("Location: ../admin/adminuseredit.php?usertoedit=" . $_POST['usertoedit']);
        }

        //STOP - Syncroniser Token does not match - log user off
    } else {
        header("Location: " . $configs->getConfig('WEB_ROOT') . "includes/process.php");
    }
} // editAccount function end
    