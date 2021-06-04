<?php
	require_once('ftp.php');
	if (!isset($_POST['upload']) || !isset($_POST['mkdir'])) {
?>

	<table>
		<tr>
			<th class="name">
				Name:
			</th>
			<th class="type">
				Type:
			</th>
		</tr>
			<?php
			for ($i = 0; $i < count($dir_files); $i++) {

				$bool = @ftp_chdir($ftp_con, $dir_files[$i]);

				if ($bool == 1) {
					ftp_chdir($ftp_con, $dir_name);
					$buffer = $dir_files[$i];
					?>
					<tr>
						<td class="name">
							<?php
							$buffer = strrev(strtok(strrev($dir_files[$i]), "/"));
							?>
							<button style="color: #bac5ff;" type="submit" name="<?php
								if (isset($_POST['delete'])) {
									echo "rmname";
								} elseif (isset($_POST['move']) && !isset($_POST['movefile'])) {
									echo "movefile";
								} elseif (isset($_POST['zip'])) {
									echo "zipfile";
								} else {
									echo "name";
								}
							?>" value="<?php echo $dir_files[$i]; ?>" class="astext folder"><?php echo $buffer; ?></button>
						</td>
						<td class="type">
							<button type="submit" name="type" class="astext">Folder</button>
						</td>
					</tr>
					<?php
				} else {
					$buffer = $dir_files[$i];
					?>
					<tr>
							<td class="name">
								<?php if (isset($_POST['delete'])) {?><input type="hidden" name="name" value="<?php echo $dir_name?>"><?php } ?>
								<button style="color: #5b85ff;" value="<?php echo $buffer; ?>" type="submit" name="<?php
								if (isset($_POST['delete'])) {
									echo "rmname";
								} elseif (isset($_POST['move'])) {
									echo "movefile";
								} elseif (isset($_POST['zip'])) {
									echo "zipfile";
								} else {
									echo "name";
								}
							?>" class="astext"><?php echo str_replace("/", "", str_replace($dir_name, "", $buffer)); ?></button>
							</td>
							<td class="type">
								<button type="submit" name="type" class="astext"><?php echo strstr($buffer, '.'); ?></button>
							</td>
					</tr>
					<?php
				}

			}
			?>

			</table>


<?php
}