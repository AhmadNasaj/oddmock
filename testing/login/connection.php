<?php

$dbhost = "localhost";
$dbuser = "oddmswss_linus";
$dbpass = "Nathan4554";
$dbname = "oddmswss_login";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}
