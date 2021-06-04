<?php

function ssh() 
{
	set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
	include('Net/SSH2.php');
	$ssh = new Net_SSH2('REDACTED');
	$ssh->login('REDACTED', 'REDACTED');
	return $ssh;
}

//phpinfo();
require_once('LoginTest.php');
require_once('database.php');

$sql = 'SELECT type,version FROM jar';
$result = $con->query($sql);
$jar = array();
while($row = $result->fetch_assoc()) 
{	
    $jar[] = $row;
}

$sql = 'SELECT * FROM mods';
$result = $con->query($sql);
$mods = array();
while($row = $result->fetch_assoc()) 
{	
	$mods[] = $row;	
}


$sql = "SELECT id,ip,port,name,type,version FROM servers";
$result = $con->query($sql);
$servers = array();
while($row = $result->fetch_assoc()) 
{	
	$servers[] = $row;	
}

if ($_GET['id'] > 2) {
	$server = $servers[$_GET['id'] - 1]['name'];
	require_once('fm/ftp.php');
}

if (isset($_POST['start'])) {

    $servername = $_POST['servername'];
	
    ftp_chdir($ftp_con, '/');
    
	$myfile = fopen("test", "w");
    fwrite($myfile, $servername . "/");
	fclose($myfile);
	$ftp_put = ftp_put($ftp_con, "dir.txt", 'test', FTP_ASCII);
	
	$myfile = fopen("test", "w");
    fwrite($myfile, "1");
	fclose($myfile);
	$ftp_put = ftp_put($ftp_con, "server.txt", 'test', FTP_ASCII);
	
    //$cmd = 'cd C:\Users\samue\Documents\Minecraft\WebServer\\' . $servername . ' && start java -jar server.jar';
	//$ssh->exec($cmd);
} elseif (isset($_POST['stop'])) {
	
	require_once("rcon/rcon.php");
	
	$host = 'REDACTED';
	$port = 203;
	$password = 'REDACTED';
	$timeout = 3;
	
	$rcon = new Rcon($host, $port, $password, $timeout);
	$rcon->connect();
	
	for ($i = 3; $i > 0; $i--) {
		$rcon->send_command('say Server stopping in ' . $i . " sec");
		sleep(1);
	}
	
	$rcon->send_command('stop');
	
}

if (isset($_POST['mods'])) {

	$version = strrev(strtok(strrev($_POST['mods']), '>'));
	$length = strlen($version) + 1;
	$mod = @substr($_POST['mods'], 0, $length * -1);

	$sql = 'SELECT id FROM mods WHERE version="1.12.2" AND name="Lucky Block"';
	$result = $con->query($sql);
	$buffer = array();

	while($row = $result->fetch_assoc()) 
	{	
	    $buffer[] = $row;
	}

	$sql = 'SELECT * FROM mods WHERE id="' . $buffer[0]['id'] . '"';
	$result = $con->query($sql);
	$buffer = array();

	while($row = $result->fetch_assoc()) 
	{	
	    $buffer[] = $row;
	}

	$file = fopen("test", "w");

	fwrite($file, $buffer[0]['jar']);
	fclose($file);

	$remotefile = $_POST['name'] . "/" . $mod . " " . $version . ".jar";

	ftp_put($ftp_con, $remotefile, 'test', FTP_ASCII);
}

if (!isset($_GET['id'])) 
{
	Header("Location: index.php?id=1");
	exit();
}

if ($_GET['id'] > 2)
{
    if (!isset($_GET['tab'])) 
    {
        Header("Location: index.php?id=" . $_GET['id'] . "&tab=1");
        exit();
    }
}

require_once("header.php");

?>

<html ">
    <head>
        <title>Samuels Web Interface für Minecraft Server</title>
        <link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/batmfa.css">
		<style>
			<?php
				echo (
					".tab" . $_GET['id'] . " {\n				color: #1fe022;border-left-color: #1fe022;\n			}\n"
				);
			?>
		</style>
        <meta charset="utf-8">
		<script type="text/javascript">
			function validateForm() {
					alert("iMac wird gestartet");
			}
		</script>
    </head>
    <body>
		<?php
		
		
		switch ($_GET['id'])
		{
            case 1:
                 ?>
                    <div class="WOL">
                        <h1>iMac starten</h1>
                        <h3>Damit die Server gestartet werden können muss der iMac angeschaltet werden</h3>
                        <form action="wakeup.php" onsubmit="return validateForm()" method="get">
                            <input class="button" type="submit" value="Start iMac">
                        </form>
                    </div>
                <?php
                break;
            case 2:
      	?>
                    <form method="POST" enctype="multipart/form-data" action="createServer.php?id=<?php echo $_GET['id']; ?>">
                        <table style="display: flex;flex-direction: row;justify-content: center;">
                            <tr>
                                <th>Name</th><th>Image</th><th>Port</th><th>Type & Version</th>
                            </tr>
                            <tr>
                                <td><input name="name" type="text"></td><td><input type="file" name="file" ></td><td><?php echo count($servers) + 101; ?></td><td>
                                    <select name="type" size="1">
                                        <?php
                                        for ($i = 0; $i < count($jar);$i++) {
                                            ?>
                                            <option name="type" value="<?php
                                                    echo $jar[$i]['type'] . $jar[$i]['version'];
                                                ?>">
                                                <?php
                                                    echo $jar[$i]['type'] .">". $jar[$i]['version'];
                                                ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] + 1;?>">
                        <input hidden="hidden" value="samuelscheit.ddns.net" name="ip">
                        <input value="<?php echo count($servers) + 101; ?>" name="port" hidden="hidden">
                        <?php //var_dump($jar); ?>
                        
                        <br>
                        <br>
                        <input value="Erstellen" name="submitErstellen" class="button" type="submit">
                    </form>
                <?php
                break;
                
			//INSERT INTO `servers` (`id`, `name`, `ip`, `port`) VALUES (NULL, 'test', '124.234.234.236', '100')
				
		
            default:
                ?>

                <form method="post" action="deleteServer.php">
                    <input hidden="hidden" name="id" value="<?php echo ($_GET['id']); ?>">
                    <input type="hidden" name="remove" value="/<?php echo $servers[$_GET['id'] -1]['name']; ?>">
					<button class="button" name="submitLöschen" type="submit" value="Server Löschen">Server Löschen<br><p style="font-size: 0.6em;">Dies dauert einen Moment</p></button>
					
                </form>
                <br>
                <br>
                <h1><div class="sidetabs">
                    <a class="st_0" href="index.php?id=<?php echo $_GET['id']; ?>&tab=1">
                        <img height="50em" src="Pictures/Status.png"><span> Status</span>
                    </a>
                    <a class="st_0" href="index.php?id=<?php echo $_GET['id']; ?>&tab=2">
                        <img height="50em" src="Pictures/Console.png"><span> Konsole</span>
                    </a>
                    <a class="st_0" href="index.php?id=<?php echo $_GET['id']; ?>&tab=3">
                        <img height="50em" src="Pictures/FileManager.png">
                        <span> File Manager</span>
                    </a>
                </div></h1>
                
            <?php
                break;
        }
		
            set_include_path('/var/www/html');
            if ($_GET['id'] > 2) {
				switch ($_GET['tab']) {
					case 1:
						
						require_once('query.php');
						$query = new Query('REDACTED', $servers[$_GET['id'] - 1]['port'], 1, true);
						$info = $query->get_info();
                        $query->disconnect();
						?>
		
						<table>
							<tr>
								<th>Status:</th>
								<td><?php
									if ($info == false) {
										echo "Offline";
									} else {
										echo "Online";
									}
									?></td>
							</tr>
							<tr>
								<th>IP:</th>
								<td><?php echo $servers[$_GET['id'] - 1]['ip']; ?></td>
							</tr>
							<tr>
								<th>Port:</th>
								<td><?php echo $servers[$_GET['id'] - 1]['port']; ?></td>
							</tr>
							<tr>
								<th>Type:</th>
								<td><?php echo $servers[$_GET['id'] - 1]['type']; ?></td>
							</tr>
							<tr>
								<th>Version:</th>
								<td><?php echo $servers[$_GET['id'] - 1]['version']; ?></td>
							</tr>
							<tr>
								<th>World:</th>
								<td><?php echo $info['map']; ?></td>
							</tr>
							<tr>
								<th>Plugins:</th>
								<td><?php echo $info['plugins']; ?></td>
							</tr>
							<tr>
								<th>MaxPlayers:</th>
								<td><?php echo $info['maxplayers']; ?></td>
							</tr>
							<tr>
								<th>Current Players:</th>
								<td><?php for ($i = 0; $i < count($info['players']); $i++) {
											echo $info['players'][$i] . "<br><br>";
										} ?>
								</td>
							</tr>
						</table>
						<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
							<input type="hidden" name="servername" value="<?php echo $servers[$_GET['id'] - 1]['name'];?>">
							<button name="start" class="button" >Starten</button>
							<button name="stop" class="button" >Stoppen</button>
						</form>
						<?php
						break;
					case 2:
						require('console.php');
						/*echo '
						<object name="console" type="text/html" data="foo.inc"></object>
						';*/					
						break;
					case 3:
						
						require('fm/index.php');
						if (strpos(ftp_pwd($ftp_con), "mods") != false) {
							?>

							<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
								Choose a mod to upload to the Server
								<select name="mods">
									<?php
									for ($i=0; $i < count($mods); $i++) { 
										echo "<option name=\"mods\" >" . $mods[$i]['name'] . ">" . $mods[$i]['version'] . "</option>";
									}
									?>
								</select>
								<br>
								<input type="submit" value="Upload">
								<input type="hidden" name="name" value="<?php echo $dir_name; ?>">
							</form>
							<?php
						}
						break;
				}
			}
		
			
		?>
    </body>
    <head>
        <script type="text/javascript">
                    window.scrollTo(0,document.body.scrollHeight);
        </script>
    </head>
</html>