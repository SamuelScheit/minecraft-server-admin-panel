<?php

require_once ('LoginTest.php');

$db_host = "localhost";
$db_user = "root";
$db_pass = "[REDACTED]";
$db_name = "server";

$con = new mysqli($db_host,$db_user,$db_pass,$db_name, 3);

if($con->connect_error)
{
	exit("Connection Error" . $con->error);
}





?>