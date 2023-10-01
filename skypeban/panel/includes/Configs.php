<?php

/*
 * This class gathers together an edits the configs from the database configuration table.
 */

class Configs {

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getConfig($value) {
        $sql = "SELECT config_value FROM configuration WHERE config_name = :value";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':value' => $value));
        return $row = $stmt->fetchColumn();
    }

    function updateConfigs($value, $configname) {
        $sql = "UPDATE " . TBL_CONFIGURATION . " SET config_value = :value WHERE config_name = :configname";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array(':value' => $value, ':configname' => $configname));
    }

    public function homePage() {
        return $url = $this->getConfig('WEB_ROOT') . $this->getConfig('home_page');
    }
       
    public function loginPage() {
        return $url = $this->getConfig('WEB_ROOT') . $this->getConfig('login_page');
    }

    /**
     * editConfigs - edits the site configurations in the database
     */
    function editConfigs($subsitename, $subsitedesc, $subemailfromname, $subadminemail, $subwebroot, $subhome_page, $sublogin_page, $subdateformat) {
        /* New Sitename entered */
        if ($subsitename) {
            /* Sitename error checking */
            $field = "sitename";
            if (!$subsitename) {
                Form::setError($field, "* Sitename not entered");
            } else if (strlen($subsitename) > 40) {
                Form::setError($field, "* Sitename above 40 characters");
            } else if (!preg_match("#^[A-Za-z0-9-[\]_+ ]+$#u", $subsitename)) {
                Form::setError($field, "* Sitename not alphanumeric");
            }
        }

        /* New Site Description entered */
        if ($subsitedesc) {
            /* Site description error checking */
            $field = "sitedesc";
            if (!$subsitedesc) {
                Form::setError($field, "* Site description not entered");
            } else if (strlen($subsitedesc) > 60) {
                Form::setError($field, "* Site description above 60 characters");
            } else if (!preg_match("#^[A-Za-z0-9-[\]_+ ]+$#u", $subsitedesc)) {
                Form::setError($field, "* Site description not alphanumeric");
            }
        }

        /* New E-mail From Name */
        if ($subemailfromname) {
            /* Site Email From Name error checking */
            $field = "emailfromname";
            if (!$subemailfromname) {
                Form::setError($field, "* Email From Name not entered");
            } else if (strlen($subemailfromname) > 60) {
                Form::setError($field, "* From Name above 60 characters");
            } else if (!preg_match("#^[A-Za-z0-9-[\]_+ ]+$#u", $subemailfromname)) {
                Form::setError($field, "* From Name not alphanumeric");
            }
        }

        /* New Admin Email Address */
        if ($subadminemail) {
            /* Site Admin Email error checking */
            $field = "adminemail";
            if (!$subadminemail) {
                Form::setError($field, "* Admin Email not entered");
            } else
            /* Check if valid email address using PHPs filter_var */
            if (!filter_var($subadminemail, FILTER_VALIDATE_EMAIL)) {
                Form::setError($field, "* Email invalid");
            }
        }

        if (!filter_var($subwebroot, FILTER_VALIDATE_URL)) {
            $field = "webroot";
            Form::setError($field, "* URL Invalid");
        }

        /* Errors exist, have user correct them */
        if (Form::$num_errors > 0) {
            return false;  //Errors with form
        }

        /* Update site name since there were no errors */
        if ($subsitename) {
            $this->updateConfigs($subsitename, "SITE_NAME");
        }

        if ($subsitedesc) {
            $this->updateConfigs($subsitedesc, "SITE_DESC");
        }

        if ($subemailfromname) {
            $this->updateConfigs($subemailfromname, "EMAIL_FROM_NAME");
        }

        if ($subadminemail) {
            $this->updateConfigs($subadminemail, "EMAIL_FROM_ADDR");
        }

        if ($subwebroot) {
            $this->updateConfigs($subwebroot, "WEB_ROOT");
        }

        if ($subhome_page) {
            $this->updateConfigs($subhome_page, "home_page");
        }

        if ($sublogin_page) {
            $this->updateConfigs($sublogin_page, "login_page");
        }
        
        if ($subdateformat) {
            $this->updateConfigs($subdateformat, "DATE_FORMAT");
        }

        /* Success! */
        return true;
    }

    /**
     * editRegConfigs - edits the sites registry configurations.
     */
    function editRegConfigs($subactivation, $sublimit_username_chars, $submin_user_chars, $submax_user_chars, $submin_pass_chars, $submax_pass_chars, $subsend_welcome, $sub_captcha, $sub_all_lowercase, $sub_allow_dupe_email) {

        /* New Minimum Username Characters */
        if ($submin_user_chars) {

            /* Suitable range for minimum and maximum characters for 'minimum' username characters */
            $min_user_options = array("options" => array("min_range" => 3, "max_range" => 20));

            /* Minimum Username Characters error checking */
            $field = "min_user_chars";
            if (!$submin_user_chars) {
                Form::setError($field, "* No minimum username length entered");
            } else if (!filter_var($submin_user_chars, FILTER_VALIDATE_INT, $min_user_options)) {
                Form::setError($field, "* Field not numerical or not within the range of " . $min_user_options['options']['min_range'] . " and " . $min_user_options['options']['max_range']);
            }
        }

        /* New Maximum Username Characters */
        if ($submax_user_chars) {

            /* Suitable range for minimum and maximum characters for 'maximum' username characters */
            $max_user_options = array("options" => array("min_range" => 6, "max_range" => 40));

            /* Maximum Username Characters error checking */
            $field = "max_user_chars";
            if (!$submax_user_chars) {
                Form::setError($field, "* No maximum username length entered");
            } else if (!filter_var($submax_user_chars, FILTER_VALIDATE_INT, $max_user_options)) {
                Form::setError($field, "* Field not numerical or not within the range of " . $max_user_options['options']['min_range'] . " and " . $max_user_options['options']['max_range']);
            }
        }

        /* New Minimum Password Characters */
        if ($submin_pass_chars) {

            /* Suitable range for minimum and maximum characters for 'maximum' password characters */
            $min_pass_options = array("options" => array("min_range" => 4, "max_range" => 10));

            /* Minimum Username Characters error checking */
            $field = "min_pass_chars";
            if (!$submin_pass_chars) {
                Form::setError($field, "* No minimum username length entered");
            } else if (!filter_var($submin_pass_chars, FILTER_VALIDATE_INT, $min_pass_options)) {
                Form::setError($field, "* Field not numerical or not within the range of " . $min_pass_options['options']['min_range'] . " and " . $min_pass_options['options']['max_range']);
            }
        }

        /* New Maximum Password Characters */

        /* Suitable range for minimum and maximum characters for 'maximum' password characters */
        $max_pass_options = array("options" => array("min_range" => 10, "max_range" => 110));

        if ($submax_pass_chars) {
            /* Maximum Username Characters error checking */
            $field = "max_pass_chars";
            if (!$submax_pass_chars) {
                Form::setError($field, "* No maximum password length entered");
            } else if (!filter_var($submax_pass_chars, FILTER_VALIDATE_INT, $max_pass_options)) {
                Form::setError($field, "* Field not numerical or not within the range of " . $max_pass_options['options']['min_range'] . " and " . $max_pass_options['options']['max_range']);
            }
        }

        /* Errors exist, have user correct them */
        if (Form::$num_errors > 0) {
            return false;  //Errors with form
        }

        $this->updateConfigs($submin_user_chars, "min_user_chars");
        $this->updateConfigs($submax_user_chars, "max_user_chars");
        $this->updateConfigs($submin_pass_chars, "min_pass_chars");
        $this->updateConfigs($submax_pass_chars, "max_pass_chars");

        if ($sublimit_username_chars) {
            $this->updateConfigs($sublimit_username_chars, "USERNAME_REGEX");
        }

        // Check for the existance of 0 otherwise IF will return false and not update.
        if ($subsend_welcome == 0 || 1) {
            $this->updateConfigs($subsend_welcome, "EMAIL_WELCOME");
        }

        if ($sub_captcha == 0 || 1) {
            $this->updateConfigs($sub_captcha, "ENABLE_CAPTCHA");
        }

        if (filter_var($subactivation, FILTER_VALIDATE_INT)) {
            $this->updateConfigs($subactivation, "ACCOUNT_ACTIVATION");
        }

        if ($sub_all_lowercase == 0 || 1) {
            $this->updateConfigs($sub_all_lowercase, "ALL_LOWERCASE");
        }

        if ($sub_allow_dupe_email == 0 || 1) {
            $this->updateConfigs($sub_allow_dupe_email, "ALLOW_DUPE_EMAIL");
        }

        /* Success! */
        return true;
    }

    /**
     * editSecConfigs - edits the site configurations in the database
     */
    function editSessConfigs($subuser_timeout, $subguest_timeout, $subcookie_expiry, $subcookie_path) {

        if (!filter_var($subuser_timeout, FILTER_VALIDATE_INT)) {
            $field = "timeout";
            Form::setError($field, "* Entry not numerical");
        } else {
            $this->updateConfigs($subuser_timeout, "USER_TIMEOUT");
        }

        if (!filter_var($subguest_timeout, FILTER_VALIDATE_INT)) {
            $field = "guesttimeout";
            Form::setError($field, "* Entry not numerical");
        } else {
            $this->updateConfigs($subguest_timeout, "GUEST_TIMEOUT");
        }

        if (!filter_var($subcookie_expiry, FILTER_VALIDATE_INT)) {
            $field = "cookieexpiry";
            Form::setError($field, "* Entry not numerical");
        } else {
            $this->updateConfigs($subcookie_expiry, "COOKIE_EXPIRE");
        }

        if ($subcookie_path) {
            $this->updateConfigs($subcookie_path, "COOKIE_PATH");
        }

        /* Errors exist, have user correct them */
        if (Form::$num_errors > 0) {
            return false;  //Errors with form
        } else {

            /* Success! */
            return true;
        }
    }

}
