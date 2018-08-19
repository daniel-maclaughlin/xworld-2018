<?php
//DB details
$dbHost = 'localhost';
$dbUsername = 'assets';
$dbPassword = 'xworld2018';
$dbName = 'assets';

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Set character set as UTF 8
$db->set_charset("utf8");

if ($db->connect_error) {
	die("Unable to connect database: " . $db->connect_error);
}
?>
