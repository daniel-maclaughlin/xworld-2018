<?php
//load the database configuration file
include 'dbConfig.php';

if(!isset($_POST['delete']))
{
    echo 'No delete command was given...';
    exit;
}
else {
$sqlquery = "truncate table assets";
if (!$result = $db->query($sqlquery))
{
    die('Query failed: (' . $db->errno . ') ' . $db->error);
}
}


// PHP permanent URL redirection test
header("Location: http://xworld.jamfpro.services", true, 301);
exit();
?>
