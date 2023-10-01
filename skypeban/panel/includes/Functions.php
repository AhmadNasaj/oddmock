<?php

/**
 * Functions - Some functions used across multiple objects/classes.
 */
class Functions {

    public $db;
    public $login_attempts;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /*
     * getUserInfo - Returns the result array from an sql query asking for all 
     * information stored regarding the given username. If query fails, NULL is returned.
     */
    public function getUserInfo($username) {
        $query = "SELECT * FROM " . TBL_USERS . " WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':username' => $username));
        $dbarray = $stmt->fetch();
        /* Error occurred, return given name by default */
        $result = count($dbarray);
        if (!$dbarray || $result < 1) {
            return NULL;
        }
        /* Return result array */
        return $dbarray;
    }

    /*
     * getUserInfoSingular - Returns the single user's info.
     */
    public function getUserInfoSingular($asset, $username) {
        $asset = strip_tags($asset);
        $query = "SELECT $asset FROM " . TBL_USERS . " WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':username' => $username));
        return $usersinfo = $stmt->fetchColumn();
    }

    /**
     * usernameTaken - Returns true if the username has been taken by another user, false otherwise.
     */
    public function usernameTaken($username) {
        $result = $this->db->query("SELECT username FROM " . TBL_USERS . " WHERE username = '$username'");
        $count = $result->rowCount();
        return ($count > 0);
    }

    /**
     * ipDisallowed - Returns true if the ip address has been disallowed.
     */
    function ipDisallowed($ip) {
        $query = "SELECT ban_id FROM banlist WHERE ban_ip = :ip_address";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':ip_address' => $ip));
        $count = $stmt->rowCount();
        return ($count > 0);
    }

    /*
     * updateUserField - Updates a field, specified by the field parameter, in the 
     * user's row of the database.
     */

    public function updateUserField($username, $field, $value) {
        $query = "UPDATE " . TBL_USERS . " SET " . $field . " = :value WHERE username = :username";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(array(':username' => $username, ':value' => $value));
    }

    /*
     * addLastVisit - Updates the database with the users previous visit timestamp.
     */

    public function addLastVisit($username) {
        $admin_details = $this->getUserInfo($username);
        $admin_lastvisit = $admin_details['timestamp'];
        $this->updateUserField($username, "previous_visit", $admin_lastvisit);
    }

    /**
     * checkBanned - Returns true if the username has been banned by the administrator.
     */
    function checkBanned($username) {
        $userid = $this->getUserInfoSingular('id', $username);
        $query = "SELECT ban_userid FROM " . TBL_BANNED_USERS . " WHERE ban_userid = :userid";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':userid' => $userid));
        $count = $stmt->rowCount();
        return ($count > 0);

    }

    /*
     * addActiveUser - Updates username's last active timestamp in the database, and 
     * also adds him to the table of active users, or updates timestamp if already there.
     */

    public function addActiveUser($username, $time) {
        $configs = new Configs($this->db);
        $query = "SELECT * FROM " . TBL_USERS . " WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':username' => $username));
        $dbarray = $stmt->fetch();

        $db_timestamp = $dbarray['timestamp'];
        $timeout = time() - $configs->getConfig('USER_TIMEOUT') * 60;

        // Logs off if inactive for too long (unless remember me set)
        if ($db_timestamp < $timeout && !isset($_COOKIE['cookname']) && !isset($_COOKIE['cookid'])) {
            header("Location:" . $configs->getConfig('WEB_ROOT') . INCLUDES . "process.php");
        }

        $query = "UPDATE " . TBL_USERS . " SET timestamp = :time WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':username' => $username, ':time' => $time));

        if (!$configs->getConfig('TRACK_VISITORS')) {
            return;
        }
        $query = "REPLACE INTO " . TBL_ACTIVE_USERS . " VALUES (:username, :time)";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':username' => $username, ':time' => $time));
        $this->calcNumActiveUsers();
    }

    /*
     * calcNumActiveUsers - Finds out how many active users are viewing site and 
     * sets class variable accordingly. (used for viewactive????)
     */

    public function calcNumActiveUsers() {
        /* Calculate number of USERS at site */
        $sql = $this->db->query("SELECT * FROM " . TBL_ACTIVE_USERS);
        return $num_active_users = $sql->rowCount();
    }

    /**
     * usernameRegex - checks which regex is needed - returns false if the username
     * fails the selected regex. The regex is set in the configuration table
     * in the database.
     */
    public function usernameRegex($subuser) {
        $configs = new Configs($this->db);
        $option = $configs->getConfig('USERNAME_REGEX');
        switch ($option) {
            case 'any_chars':
                $regex = '.+';
                break;

            case 'alphanumeric_only':
                $regex = '[A-Za-z0-9]+';
                break;

            case 'alphanumeric_spacers':
                $regex = '[A-Za-z0-9-[\]_+ ]+';
                break;

            case 'any_letter_num':
                $regex = '[a-zA-Z0-9]+';
                break;

            case 'letter_num_spaces':
            default:
                $regex = '[-\]_+ [a-zA-Z0-9]+';
                break;
        }
        if (preg_match('#^' . $regex . '$#u', $subuser)) {
            return 1;
        }
    }

    // Checks firstname & lastname fields
    function nameCheck($name, $field, $fullname, $min, $max) {
        if (!$name) {
            Form::setError($field, "* " . $fullname . " not entered");
        } else {

            /* Check if field is too short */
            if (strlen($name) < $min) {
                Form::setError($field, "* " . $fullname . " too short");
            }
            /* Check if field is too long */ else if (strlen($name) > $max) {
                Form::setError($field, "* " . $fullname . " too long");
            }
            /* Check if field is not alphanumeric */ else if (!preg_match("#^[A-Za-z0-9-[\]_+ ]+$#u", ($name = trim($name)))) {
                Form::setError($field, "* " . $fullname . " not alphanumeric");
            }
        }
    }

    /**
     * Functions to do with Group administration.
     */
    function checkGroupNumbers($db, $groupid) {
        $sql = $this->db->query("SELECT COUNT(group_id) FROM users_groups WHERE group_id = '$groupid'");
        $count = $sql->fetchColumn();
        return $count;
    }
    
    function getGroupId($db, $groupname) {
        $sql = $this->db->query("SELECT users_groups.group_id FROM groups INNER JOIN `users_groups` ON groups.group_id = users_groups.group_id WHERE group_name = '$groupname' LIMIT 1");
        return $group_id = $sql->fetchColumn();
    }
    
    function returnGroupInfo($db, $id) {
        $sql = $this->db->query("SELECT * FROM `groups` WHERE group_id = $id");
        return $groupinfo = $sql->fetch();
    }
    
    function returnGroupMembers($db, $id) {
        $sql = $this->db->query("SELECT users.username, users_groups.group_id FROM `users` INNER JOIN `users_groups` ON users.id=users_groups.user_id WHERE users_groups.group_id = '$id'");
        return $groupinfo = $sql->fetch();
    }

    /**
     * generateRandID - Generates a string made up of randomized letters (lower 
     * and upper case) and digits and returns the md5 hash of it to be used as a userid.
     */
    public static function generateRandID() {
        return md5(self::generateRandStr(16));
    }

    /**
     * generateRandStr - Generates a string made up of randomized letters (lower 
     * and upper case) and digits, the length is a specified parameter.
     */
    public static function generateRandStr($length) {
        $randstr = "";
        for ($i = 0; $i < $length; $i++) {
            $randnum = mt_rand(0, 61);
            if ($randnum < 10) {
                $randstr .= chr($randnum + 48);
            } else if ($randnum < 36) {
                $randstr .= chr($randnum + 55);
            } else {
                $randstr .= chr($randnum + 61);
            }
        }
        return $randstr;
    }

}
