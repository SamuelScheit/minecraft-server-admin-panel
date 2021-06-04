<html>
	<head>
	  <meta charset="utf-8">
	  <title>File Manager Header (GNU Samuel Scheit)</title>
	  <link rel="stylesheet" href="/fm/src/css/header.css">
	</head>
	<body>
		<div class="wrapper" id="wrapperupload">
             <h1>Test</h1>
			<form class="form" id="uploader" action="index.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="post">
				<input type="hidden" name="name" value="<?php echo $dir_name; ?>">
				<?php
				if (isset($_POST['movefile']) && !isset($_POST['target'])){
					echo "JA";
				?>
					<input type="hidden" name="move">
					<input type="hidden" name="movefile" value="<?php echo $_POST['movefile']; ?>">
				<?php
				}
				?>
				<button class="button" name="dirup" id="dirup">
					<img height="48" src="/fm/images/nav/dirup.png">
				</button>
				<button class="button" name="home" id="home">
					<img height="48" src="/fm/images/nav/home.png">
				</button>
				<button class="button" name="upload" id="upload">
					<img height="48" src="/fm/images/nav/upload.png">
				</button>
				<button class="button" name="mkdir" id="mkdir">
					<img height="48" src="/fm/images/nav/folder.png">
				</button>
				<button class="button" name="delete" id="delete">
					<img height="48" src="/fm/images/nav/trash.png">
				</button>
                <button class="button" name="move" id="move">
					<img height="48" src="/fm/images/nav/move.png">
				</button>
                <!--<button class="button" name="zip" id="zip">
					<img height="48" src="/fm/images/nav/zip.png">
				</button>-->
			</form>
		</div>
	</body>
</html>