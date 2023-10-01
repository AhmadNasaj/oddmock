<?php

class Adminfunctions {

    private $db;
    public $functions;
    public $configs;
    public $stop_life = '86400'; //24 hours

    public function __construct($db, $functions, $configs) {
        $this->db = $db;
        $this->functions = $functions;
        $this->configs = $configs;
    }

    /**
     * checkLevel - Returns the userlevel - used by displayStatus function
     */
    function checkLevel($username) {
        $query = "SELECT userlevel FROM " . TBL_USERS . " WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':username' => $username));
        return $row = $stmt->fetchColumn();
    }

    /**
     * checkIPFormat - Returns true if the username has been banned by the administrator.
     */
    function checkIPFormat($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $field = "ip_address";
            Form::setError($field, "* Incorrect IP Address format");
        } else {
            return true;
        }
    }

    /**
     * displayStatus
     */
    function displayStatus($username) {
        $level = $this->checkLevel($username);
        if ($level == 1) {
            return $status = '<span style="color:blue;">Awaiting E-mail Activation</span>';
        }
        if ($level == 2) {
            return $status = '<span style="color:blue;">Awaiting Admin Activation</span>';
        }
        if (($level == 3) && (!$this->functions->checkBanned($username))) {
            return $status = '<span style="color:green;">Registered User</span>';
        }
        if ($this->functions->checkBanned($username)) {
            return $status = '<span style="color:red;">Banned</span>';
        }
        if ($level == ADMIN_LEVEL) { 
            return $status = 'Admin';
        }
    }

    /**
     * displayDate - returns a variable formatted in the date format pulled from the configs
     * eg echo displayDate(time()); // echos 14th march 2014
     */
    public function displayDate($date_toedit) {
        if (isset($date_toedit)) {
            $date = $this->configs->getConfig('DATE_FORMAT');
            return date("$date", $date_toedit);
        }
    }

    /**
     * displayAdminActivation
     */
    public function displayAdminActivation($orderby) {
        $sql = $this->db->query("SELECT username, regdate, email, userlevel FROM " . TBL_USERS . " WHERE userlevel = " . ADMIN_ACT . " OR userlevel = " . ACT_EMAIL . " ORDER BY $orderby DESC");
        return $sql;
    }

    /**
     * adminEditAccount - function for admin to edit the user's account details.
     */
    public function adminEditAccount($subusername, $subfirstname, $sublastname, $subnewpass, $subconfnewpass, $subemail,/* $subuserlevel,*/ $subusertoedit) {
        /* New password entered */
        if ($subnewpass) {
            /* New Password error checking */
            $field = "newpass";  //Use field name for new password

            /* check length */
            if (strlen($subnewpass) < $this->configs->getConfig('min_pass_chars')) {
                Form::setError($field, "* New Password too short");
            }
            /* Check if password is not alphanumeric */ else if (!preg_match("#^[A-Za-z0-9-[\]_+ ]+$#u", ($subnewpass = trim($subnewpass)))) {
                Form::setError($field, "* New Password not alphanumeric");
            }
            /* Check if passwords match */ else if ($subnewpass != $subconfnewpass) {
                Form::setError($field, "* Passwords do not match");
            }
        }

        if (($subconfnewpass) && (!$subnewpass)) {
            $field = "conf_newpass";
            Form::setError($field, "* You've only entered one new password");
        }

        /* New username entered */
        if ($subusername) {
            /* Username error checking */
            $field = "username";  //Use field name for userlevel
            if (!$this->functions->usernameRegex($subusername)) {
                Form::setError($field, "* Username not alphanumeric");
            }
            /* Check if username is reserved */ else if (strcasecmp($subusername, GUEST_NAME) == 0) {
                Form::setError($field, "* Username reserved word");
            }
            /* Check if username is already in use */ else if ($subusertoedit !== $subusername && $this->functions->usernameTaken($subusername)) {
                Form::setError($field, "* Username already in use");
            }
        }

        /* Firstname error checking */
        $this->functions->nameCheck($subfirstname, 'firstname', 'First Name', 2, 30);

        /* Lastname error checking */
        $this->functions->nameCheck($sublastname, 'lastname', 'Last Name', 2, 30);

        /* Email error checking */
        $field = "email";  //Use field name for email
        if ($subemail && strlen($subemail = trim($subemail)) > 0) {
            /* Check if valid email address */
            if (!filter_var($subemail, FILTER_VALIDATE_EMAIL)) {
                Form::setError($field, "* Email invalid");
            }
        }

        /* Errors exist, have user correct them */
        if (Form::$num_errors > 0) {
            return false;  //Errors with form
        }

        /* Update firstname since there were no errors */
        if ($subfirstname) {
            $this->functions->updateUserField($subusertoedit, "firstname", $subfirstname);
        }

        /* Update lastname since there were no errors */
        if ($sublastname) {
            $this->functions->updateUserField($subusertoedit, "lastname", $sublastname);
        }

        /* Update password since there were no errors */
        if ($subnewpass) {
            $usersalt = Functions::generateRandStr(8);
            $this->functions->updateUserField($subusertoedit, "usersalt", $usersalt);
            $this->functions->updateUserField($subusertoedit, "password", sha1($usersalt . $subnewpass));
        }

        /* Change Email */
        if ($subemail) {
            $this->functions->updateUserField($subusertoedit, "email", $subemail);
        }

        /* Update username - this MUST GO LAST otherwise the username 
         * will change and subsequent changes like e-mail will not be changed.
         */
        if ($subusername) {
            $this->functions->updateUserField($subusertoedit, "username", $subusername);
        }

        /* Success! */
        return true;
    }

    /**
     * checkUsername - Helper function for the above processing, it makes sure the 
     * submitted username is valid, if not, it adds the appropritate error to the form.
     */
    public function checkUsername($username) {

        /* Username error checking */
        $subuser = $username;
        $field = 'user';  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            Form::setError($field, "* Username not entered<br>");
        } else {
            /* Make sure username is in database */
            if (strlen($subuser) < $this->configs->getConfig('min_user_chars') ||
                strlen($subuser) > $this->configs->getConfig('max_user_chars') ||
                (!$this->functions->usernameRegex($subuser)) ||
                (!$this->functions->usernameTaken($subuser))) {
                    Form::setError($field, "* Username does not exist<br>");
            }
        }
        return $subuser;
    }

    /**
     * The following 3 functions are responsible for checking the validty of sensitive admin operations in 
     * an attempt at preventing CSRF attacks. They generate unique hashed ids that are passed from the 
     * POST or GET string requesting the sensitive change, to the script carrying out the change. 
     * If the IDs do not match the change is not carried out. 
     */
    function createStop($admin, $name) {
        $req_user_info = $this->functions->getUserInfo($admin);
        if (isset($req_user_info)) {
            $userid = $req_user_info['userid'];
            $stoptick = ceil(time() / ( $this->stop_life / 2 ));
            return md5($stoptick . $userid . $name);
        }
    }

    function verifyStop($admin, $name, $stop) {
        $req_user_info = $this->functions->getUserInfo($admin);
        if (isset($req_user_info)) {
            $userid = $req_user_info['userid'];
            $stoptick = ceil(time() / ( $this->stop_life / 2 ));
            if ((md5($stoptick . $userid . $name)) == $stop) {
                return 2;
            }
        }
    }

    function stopField($admin, $name) {
        $stop_field = '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . $this->createStop($admin, $name) . '" />';
        return $stop_field;
    }

    /* Returns the Previous Visit date of the submitted username */
    function previousVisit($username) {
        $lastvisit = $this->functions->getUserInfoSingular('previous_visit', $username);
        return $this->displayDate($lastvisit);
    }

    /* Users Since - returns registered users sincelast visit */
    function usersSince($username) {
        $lastvisit = $this->functions->getUserInfoSingular('previous_visit', $username);
        $query = $this->db->query("SELECT username FROM " . TBL_USERS . " WHERE regdate > " . $lastvisit);
        return $userssince = $query->rowCount();
    }

}
