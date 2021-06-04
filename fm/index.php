<?php


require_once(dirname(__FILE__) . "/ftp.php");

if (isset($_POST['name'])) {

	if (@ftp_chdir($ftp_con, $_POST['name'])) {
		
	} else {
		
		//$buffer = strrev(strtok(strrev($_POST['name']), "/"));
		
		$_POST['namefile'] = $_POST['name'];
	}
	
	$dir_name = ftp_pwd($ftp_con);
	$dir_files = ftp_nlist($ftp_con, $dir_name);

}

if (isset($_POST['dirup'])) {
    
    if (substr_count($dir_name, '/') < 2) {
        ftp_chdir($ftp_con, '/');
		if (isset($server)) {ftp_chdir($ftp_con, $server);}
    } else {
        $buffer = strrev(strtok(strrev($dir_name), "/"));
		$length = strlen($buffer) * -1;
		$buffer = substr($dir_name, 0, $length);
        ftp_chdir($ftp_con, $buffer);
    }
}

if (isset($_POST['home'])) {
    
    ftp_chdir($ftp_con, "/");
	
	if (isset($server)) {
		
		ftp_chdir($ftp_con, $server);
		
	}
	
}

if (@$_FILES['file']['name'] != '') {
    
    move_uploaded_file($_FILES['file']['tmp_name'], '/tmp/'. $_FILES['file']['name']);
    
    $upload = ftp_put($ftp_con, $_FILES['file']['name'], '/tmp/'. $_FILES['file']['name'], FTP_ASCII);

    if (!$upload) {
        echo "<p>Ftp upload war fehlerhaft!</p>";
    } else {
        //echo "Erfolgreich";
    }
}

if (isset($_POST['foldername'])) {
	
	ftp_mkdir($ftp_con, $_POST['foldername']);
}

if (isset($_POST['remove'])) {
	$ftp_delete = @ftp_delete($ftp_con, $_POST['remove']);
	$ftp_rmdir = @ftp_rmdir($ftp_con, $_POST['remove']);
	
	if ($ftp_delete != 1 && $ftp_rmdir != 1) {
		
		//echo "Es muss wohl ein voller Ordner sein<br>";
		$ftp_chdir = @ftp_chdir($ftp_con, $_POST['remove']);
		//echo "Ftp chdir:" . $ftp_chdir . "<br>";
		
		again:
		
		if (empty($dir_files)) {
			
			//echo "EMPTY!!!<br>";
			$buffer = strrev(strtok(strrev($dir_name), "/"));
			//echo "Buffer:" . $buffer . "<br>";

			$length = strlen($buffer) * -1 ;
			$length = $length -1;
			//echo "length:" . $length . "<br>";

			$buffer = substr($dir_name, 0, $length);
			//echo "Buffer:" . $buffer . "<br>";

			$ftp_chdir = @ftp_chdir($ftp_con, $buffer);
			//echo "Ftp chdir:" . $ftp_chdir . "<br>";
			
		}
		
		$dir_name = @ftp_pwd($ftp_con);
		$dir_files = ftp_nlist($ftp_con, $dir_name);
		
		for ($i = 0; $i < count($dir_files); $i++) {

			/*
			echo "Neudurchlauf:<br><br>";
			echo "i:" . $i . "<br>";

			echo "dir_files:";
			var_dump($dir_files);
			echo "<br>";

			echo "dir_name:" . $dir_name . "<br>";
			
			echo "dir_files[$ i]" . $dir_files[$i] . "<br>";
			*/
			
			$ftp_delete = @ftp_delete($ftp_con, $dir_files[$i]);
			$ftp_rmdir = @ftp_rmdir($ftp_con, $dir_files[$i]);
			
			if ($ftp_rmdir == 1) {
				
				/*
				echo "post:" . $_POST['remove'] . "<br>";
				echo "empty post:" . empty(ftp_nlist($ftp_con, $_POST['remove'])) . "<br>";
				*/
				
				if (empty(ftp_nlist($ftp_con, $_POST['remove'])) == 1) {
					
					/*
					echo "dir_name:" . $dir_name . "<br>";
					echo "JAAAA";
					*/
					$ftp_rmdir = @ftp_rmdir($ftp_con, $_POST['remove']);
					$ftp_chdir = @ftp_chdir($ftp_con, "/");
					goto end;
					
				} else {
					
					/*
					echo "dirname:" . ftp_pwd($ftp_con) . "<br>";
					echo "again1<br>";
					*/
					$dir_name = ftp_pwd($ftp_con);
					$dir_files = ftp_nlist($ftp_con, $dir_name);
					goto again;
					
				}
				
			} else {
				
				$ftp_chdir = @ftp_chdir($ftp_con, $dir_files[$i]);
				/*
				echo "dirname:" . ftp_pwd($ftp_con) . "<br>";
				echo "again<br>";
				*/
				$dir_name = @ftp_pwd($ftp_con);
				$dir_files = ftp_nlist($ftp_con, $dir_name);
				goto again;
				
			}

		}
		
		
	} else {
		//echo "Gelöscht:<br>File:" . $ftp_delete . "rmdir:" . $ftp_rmdir;
	}
	
	end:
	//echo "end";
    
    if (isset($deleteserver)) {
    	$ftp_delete = @ftp_delete($ftp_con, $_POST['remove']);
		$ftp_rmdir = @ftp_rmdir($ftp_con, $_POST['remove']);
        return;
    }
	
}

if (isset($_POST['namefile'])) {
	$url = $_SERVER['REQUEST_URI'];
	?>

	<meta http-equiv="refresh" content="0; URL='http://samuelscheit.ddns.net/download.php?namefile=<?php echo $_POST['namefile'];?>'" />			
	
	<?php


	exit();

}
/*
if (isset($_POST['zipfile'])) {
	echo $_POST['zipfile'];
	$zip = str_replace(".zip", "/", $_POST['zipfile']);

	$ftp_chdir = @ftp_chdir($ftp_con, $_POST['zipfile']);

	require_once (dirname(__FILE__) . "/zip.php");


	if ($ftp_chdir != true) {
		$ftp_get = ftp_get($ftp_con, $_POST['zipfile'], $_POST['zipfile'], FTP_ASCII);
		echo $ftp_get;
		$zip = new Zip;
		$zip->unzip_file("test.zip","zip");
	}
}*/

if (isset($_POST['target'])) {
	
	if ($_POST['target'] != "/") {$buffer = "/";}
	
	$buffer .= strrev(strtok(strrev($_POST['movefile']), "/"));
	
	$target = $_POST['target'] . $buffer;
	
	ftp_rename($ftp_con, $_POST['movefile'], $target);
	
	unset($_POST['movefile']);
	unset($_POST['move']);
	unset($_POST['target']);
	
}

$dir_name = ftp_pwd($ftp_con);
$dir_files = ftp_nlist($ftp_con, $dir_name);


require_once(dirname(__FILE__) . "/header.php");





?>

<head>
	<style type="text/css">
		.filetreehtml > * {
			color: #FFF;
		}
	</style>
</head>
<html class="filetreehtml">
	<head>
		<meta charset="utf-8">
		<title>File Manager (GNU Samuel Scheit)</title>
		<link rel="stylesheet" href="/fm/src/css/index.css">
	</head>
	<body>
		<h1>Current Path: 
			<?php
			echo ftp_pwd($ftp_con);
			?>
		</h1>
		<div id="filetree">
			<form enctype="multipart/form-data" method="post" action="index.php?<?php echo $_SERVER['QUERY_STRING']; ?>">
			<?php
				if (!isset($_POST['upload']) && !isset($_POST['mkdir']) && !isset($_POST['delete']) && !isset($_POST['move']) && !isset($_POST['zip'])) {
					
					require(dirname(__FILE__) . "/filetree.php");
					
				} elseif (isset($_POST['upload'])) {
					?>
						<input type="hidden" name="name" value="<?php echo $dir_name; ?>">
						<input type="file" name="file"/>
						<br>
						<br>
						<input style="color: #000;" value="Upload" type="submit">
					<?php
				} elseif (isset($_POST['mkdir'])) {
					?>
						<input type="hidden" name="name" value="<?php echo $dir_name; ?>">
						<input style="color: #000;" type="text" name="foldername">
						<input style="color: #000;" value="Erstellen" type="submit" >
					<?php
				} elseif (isset($_POST['delete'])) {
					if (!isset($_POST['rmname'])) {
					?>
						<input type="hidden" name="name" value="<?php echo $dir_name; ?>">
						<input type="hidden" name="delete">
						<h1>Klicke auf die Datei die du löschen möchtest:<h6>Volle Ordner können gelöscht werden:</h6></h1>
					<?php
						require("filetree.php");
					} else {
						echo 'Bist du dir sicher, dass du ' . $_POST['rmname'] . ' Löschen möchtest. Dies kann nicht rückgängig gemacht werden!';
						?>
						<input type="hidden" name="delete">
						<button style="color: #000;" type="submit" name="remove" value="<?php echo $_POST['rmname'];?>">Löschen</button>

						<?php
					}

				} elseif (isset($_POST['move'])) {
					if (!isset($_POST['movefile'])) {
					?>
						<input type="hidden" name="name" value="<?php echo $dir_name; ?>">
						 <input type="hidden" name="move">
						<h1>Klicke auf die Datei die du bewegen möchtest:</h1>
					<?php
						require(dirname(__FILE__) . "/filetree.php");
					} else {
						echo 'Wohin möchtest du: ' . $_POST['movefile'] . ' verschieben!<br><br>';
						require(dirname(__FILE__) . "/filetree.php");
						?>
						<br>
						<input type="hidden" name="move">
						<input type="hidden" name="movefile" value="<?php echo $_POST['movefile']; ?>">
						<button style="color: #000;" type="submit" name="target" value="<?php echo $_POST['name'];?>">Bewegen!</button>

						<?php
					}
				} elseif (isset($_POST['zip'])) {
				?>
					<input type="hidden" name="name" value="<?php echo $dir_name; ?>">
					<h1>Klicke auf die Datei die du entpacken möchtest:</h1>
				<?php
					require(dirname(__FILE__) . "/filetree.php");
				}
				?>
			</form>
        </div>
	</body>
</html>











