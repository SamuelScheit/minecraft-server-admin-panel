<?php

session_start();

if($_SESSION['Login'] == 0) {
	Header("Location: Login.php");
}

if(isset($_POST['Logout'])) {
	session_destroy();
	session_unset();
	Header("Location: Login.php?id=1");
	exit();
}




?>