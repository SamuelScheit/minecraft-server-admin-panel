<?php

session_start();

if(isset($_SESSION['Login'])) {
	if($_SESSION['Login'] == 1) {
		Header("Location: index.php");
	}
}
	
if(isset($_POST['submit']))
{
	if($_POST['user'] == "admin" && $_POST['pass'] == "03mcadmin") {
		$_SESSION['Login'] = 1;
		Header("Location: index.php");
	}
}

?>
<html>
	<header>
		<link rel="stylesheet" type="text/css" href="css/login.css">
		<title>Login</title>
	</header>
    <body>
		<div class="login-page">
		  <div class="form">
			<form class="login-form" method="post">
			  <input type="text" class="input" name="user" placeholder="username"/>
			  <input type="password" class="input" name="pass" placeholder="password"/>
			  <input type="submit" name="submit" class="button" value="Login"/>
			</form>
		  </div>
		</div>
    </body>
</html>