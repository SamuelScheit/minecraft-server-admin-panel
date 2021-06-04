<?php

require_once("LoginTest.php");

?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/css/header.css">
		<link rel="stylesheet" type="text/css" href="css/batmfa.css">
	</head>
</html>

<?php


require_once('database.php');

$sql = "SELECT * FROM servers";

$res = $con->query($sql);

while($row = $res->fetch_assoc()) {
	
	echo '<div class="navi">
			<h1><a class="naviElement" href="index.php?id=' . $row['id'] . '">' . 
				'<img height="64" src="data:image/jpeg;base64, ' . base64_encode( $row['imgdata'] ).'"/>' . 
				'<p class="tab' . $row['id'] . '">' . $row['name'] .' </p>
			</a></h1>
		</div'
	
	;
}

$sql = "ALTER TABLE servers AUTO_INCREMENT = 0";

$res = $con->query($sql);

?>

<html>
	<head>
		<title>Samuels Web Interface für Minecraft Server</title>
	</head>
	<body>
		<form action="LoginTest.php" method="post">
			<input type="submit" name="Logout" value="Logout" class="Button">
		</form>
		<?php
		
		if (isset($_GET['error'])) {
			switch ($_GET['error']) {
				case 1:
					echo "<h2>Couldn't Create Server, because the server with the given name already exists</h2>";
					break;
				case 2:
					echo "<h2>Couldn't Create Server, because no name was specified for the server</h2>";
					break;
				default:
					break;
			}
		}
		
		?>
        <br>
        <br>
	</body>
</html>