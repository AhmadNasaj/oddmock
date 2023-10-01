<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'cccccc';
$db = 'blacklist';
$connect = mysql_connect($dbhost,$dbuser,$dbpass);
$link = mysql_select_db($db, $connect);

$query = mysql_query("SELECT * FROM api");
$red = '';
while($row = mysql_fetch_array($query)){
$key = $row['key'];
if($key == $_GET['key']){
 
$auth = $auth + 1;
 
}else{
 
$auth = $auth;
 
}
}
 
if($auth == 1){

// $genapi = "";
// $result5 = htmlentities(file_get_contents($genapi));
// echo $result5;

$blacklisted = "That Skype user is blacklisted!";
echo $blacklisted;
   

 
}else{
$notblacklisted = "That Skype user is NOT blacklisted!";
echo $notblacklisted;
}
?>