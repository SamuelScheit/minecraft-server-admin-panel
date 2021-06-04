<?php

require_once('LoginTest.php');
require_once('database.php');

if (@$_FILES['file']['tmp_name'] != 0)
{
    $imagetmp=addslashes (file_get_contents($_FILES['file']['tmp_name']));
} else
{
$imagetmp = hex2bin('89504E470D0A1A0A0000000D4948445200000001000000010802000000907753DE000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001974455874536F667477617265007061696E742E6E657420342E302E3231F12069950000000C49444154185763D0D3D303000118008B7B349E6C0000000049454E44AE426082');
}

if (isset($_POST['submitErstellen']))
{

	$res = $con->query('SELECT name FROM servers WHERE name="' . $_POST['name'] . '"');
    
	$ret = array();
	$i = 0;
    while($row = $res->fetch_assoc()) {
		$i++;
        $ret[$i] = $row;
    }
	
	if (count($ret) > 0) {
		
		Header('Location: index.php?id=2&error=1');
		exit();

	} elseif ($_POST['name'] === 0) {
		Header('Location: index.php?id=2&error=2&' . $_POST['name'] . '');
		exit();
	}
	
    $con->query('ALTER TABLE servers AUTO_INCREMENT = ' . $_POST['id']);    

    $version = strstr($_POST['type'], "1");
    $type = substr($_POST['type'], 0, strlen($version) * -1);


    $sql = "INSERT INTO servers VALUES(NULL, '" . $_POST['name'] . "', '" . $_POST['ip'] . "' , '" . $_POST['port'] . "', '" . $type . "', '" . $version . "', '" . $imagetmp ."')";

    $con->query($sql);


    require('fm/ftp.php');

    @ftp_mkdir($ftp_con, $_POST['name']);
    @ftp_chdir($ftp_con, $_POST['name']);



    $sql = 'SELECT id FROM jar WHERE type="' . $type . '" AND version="' . $version .'"';

    $res = $con->query($sql);
    $ret = array();
    while($row = $res->fetch_assoc()) {
        $ret = $row;
    }

    $sql = 'SELECT * FROM jar WHERE id=' . $ret['id'] ;

    $res = $con->query($sql);
    $ret = array();
    while($row = $res->fetch_assoc()) {
        $ret = $row;
    }
	
    $myfile = fopen("test", "w");
    fwrite($myfile, $ret['jar']);
	fclose($myfile);
	
	ftp_put($ftp_con, "server.jar", 'test', FTP_BINARY);
	
	$rconport = $_POST['port'] + 100;
	
	$myfile = fopen("test", "w");
    fwrite($myfile, '#Minecraft server properties
#Fri Feb 16 22:04:07 CET 2018
view-distance=10
max-build-height=256
server-ip=
level-seed=
rcon.port=203
allow-nether=true
enable-command-block=false
server-port=103
gamemode=0
enable-rcon=true
op-permission-level=4
enable-query=true
generator-settings=
resource-pack=
player-idle-timeout=0
level-name=world
rcon.password=REDACTED
motd=A Minecraft Server
announce-player-achievements=true
query.port=103
force-gamemode=false
debug=false
hardcore=false
white-list=false
broadcast-console-to-ops=true
pvp=true
spawn-npcs=true
generate-structures=true
spawn-animals=true
snooper-enabled=true
difficulty=1
network-compression-threshold=256
level-type=DEFAULT
spawn-monsters=true
max-tick-time=60000
max-players=20
spawn-protection=16
online-mode=true
allow-flight=false
resource-pack-hash=
max-world-size=29999984
');
	fclose($myfile);
	
	ftp_put($ftp_con, "server.properties", 'test', FTP_ASCII);
	
	$myfile = fopen("test", "w");
    fwrite($myfile,
		  '#By changing the setting below to TRUE you are indicating your agreement to our EULA (https://account.mojang.com/documents/minecraft_eula).
#Thu Feb 15 15:13:18 CET 2018

eula=true'
		  );
	fclose($myfile);
	
	$ftp_put = ftp_put($ftp_con, "eula.txt", 'test', FTP_ASCII);
	
	$ftp_mkdir = ftp_mkdir($ftp_con, "mods");

    
    }
    Header("Location: index.php?id=2");
?>