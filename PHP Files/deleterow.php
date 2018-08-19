<?php
// Your database info
include 'dbConfig.php';


//allow error out if something went wrong
if (!isset($_GET['serialnumber']))
{
    echo 'No ID was given...';
    exit;
}
//get the serial numebr from input 
$serial = $_GET['serialnumber'];

//mysql command to delete a row matching from the serial numnber id
$sql = "delete from assets where serialnumber = '$serial'";
if (!$result = $db->query($sql))
{
    die('Query failed: (' . $db->errno . ') ' . $db->error);
}
?>

<?php
// PHP permanent URL redirection once the button has been pressed retrun to the index page	
header("Location: index.php");
exit();
?>
