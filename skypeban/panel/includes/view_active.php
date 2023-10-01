<?php
/**
 * A widget that displays how many registered members there are, how many users currently logged in and viewing site, 
 * and how many guests viewing site. Active users are displayed, with link to their user information.
 * 
 * include("include/controller.php"); must be called on a page displaying this widget.
 */

echo "<p>Member Total: ".$session->getNumMembers()."</p>";
echo "<p>There are ".$functions->calcNumActiveUsers()." registered members and ".$session->calcNumActiveGuests()." guests viewing the site.</p>";

if(!defined('TBL_ACTIVE_USERS')) {
  die("Error processing page");
}

$stmt = $session->db->query("SELECT username FROM ".TBL_ACTIVE_USERS." ORDER BY timestamp DESC,username");
/* Error occurred, return given name by default */
$num_rows = $stmt->columnCount();

if(!$stmt || ($num_rows < 0)){
   echo "Error displaying info";
}
else if($num_rows > 0)
    {
    echo "<p>User's Online:";
   /* Display active users, with link to their info */
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo " <a href='userinfo.php?user=".$row['username']."'>".$row['username']."</a>  /";
        }
    echo "</p>";    
    }
    echo "<p>Most User's Online: ".$configs->getConfig('record_online_users')." on ".date('M j, Y, g:i a', $configs->getConfig('record_online_date'))."</p>";
    
