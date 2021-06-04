<?php

require_once('config.php');

$ftp_con = ftp_connect("REDACTED", 21, 1);

$ftp_login = ftp_login($ftp_con, "samuel", "aber7035");

if ((!$ftp_con) || (!$ftp_login)) {
	var_dump($ftp_login);
	var_dump($ftp_con);
    echo "FTP-Verbindung ist fehlgeschlagen!";
    echo "Verbindungsaufbau zu versucht.";
    exit();
} else {
    //echo "Verbunden zu " . hostname . " mit Benutzername " . username . " auf Port: " . port . "<br>";
}

$dir_name = ftp_pwd($ftp_con);
$dir_files = ftp_nlist($ftp_con, $dir_name);

if (isset($server)) {

	ftp_chdir($ftp_con, $server);
	
}

//ftp_mkdir($ftp_con, "hallo2");



?>