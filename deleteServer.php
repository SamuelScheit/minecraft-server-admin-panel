<?php

require_once('LoginTest.php');
require_once('database.php');



if (isset($_POST['submitLöschen']))
{
	$sql = "DELETE FROM `servers` WHERE `servers`.`id` = " . $_POST['id'] . "";
	
	$con->query($sql);
    $deleteserver = 1;
	require('fm/index.php');
    
    header("Location: http://" . $_SERVER[HTTP_HOST] .  "/index.php");
    exit();
    
}
?>
