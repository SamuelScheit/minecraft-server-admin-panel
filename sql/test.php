<?php 

$GLOBALS['db_host'] = "localhost";
$GLOBALS['db_user'] = "root";
$GLOBALS['db_pass'] = "aber7035";
$GLOBALS['db_name'] = "server";

$con = new mysqli($GLOBALS['db_host'],$GLOBALS['db_user'],$GLOBALS['db_pass'],$GLOBALS['db_name']);

if($con->connect_error)
{
	exit("Connection Error" . $con->error);
}

echo ("Connected<br>");

$sql = "SELECT * FROM servers";

$res = $con->query($sql);


while($row = $res->fetch_assoc()) {
	
	echo $row['name'] . ": " . '<img width="100" src="data:image/jpeg;base64,' . base64_encode( $row['imgdata'] ).'"/>';
}


$con->close();

?>