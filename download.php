<?php	

require_once('fm/ftp.php');

$name = str_replace("/", "", str_replace($dir_name, "", $_GET['namefile']));

$test = 'test';

$handle = fopen($test, 'w');

if (ftp_fget($ftp_con, $handle, $_GET['namefile'], FTP_ASCII, 0)) {
	//var_dump($test);
	//echo $_GET['namefile'] . " wurde erfolgreich geschrieben\n";
} else {
	//echo "Ein Fehler ist aufgetreten\n";
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . strrev(strtok(strrev($_GET['namefile']), "/")) . '"');
readfile($test);


fclose($handle);

?>