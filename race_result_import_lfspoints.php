<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?
if(isset($_GET['id']))
	$id = addslashes($_GET['id']);
elseif(isset($_POST['id']))
	$id = addslashes($_POST['id']);

if(isset($_POST['xml'])) {
	$upload = false;
	switch($_FILES['userfile']['error']) {
	case UPLOAD_ERR_OK:
		$upload = true;
		break;
	case UPLOAD_ERR_NO_FILE:
		$error .= "No file selected for uploading\n";
		break;
	case UPLOAD_ERR_INI_SIZE:
		$error .= "XML file too big\n";
		break;
	case UPLOAD_ERR_PARTIAL:
		$error .= "Upload of the XML file was not completed\n";
		break;
	case UPLOAD_NO_TMP_DIR:
		$error .= "Server error: missing tmp-directory\n";
		break;
	case UPLOAD_ERR_CANT_WRITE:
		$error .= "Server error: cannot write file\n";
		break;
	}

	if($upload) {
		$driver = array();
		$elem = null;

		# Parser functions
		function startElement($parser, $name, $attrs) {
			global $driver;
			global $elem;

			if($name == "DRIVER") $driver[] = array();
			$elem = $name;
		}

		function endElement($parser, $name) {
			global $elem;
			$elem = null;
		}

		function textData($parser, $text) {
			global $driver;
			global $elem;

			if(count($driver) == 0)
				return;
			$elems = array("PLAYERNAME", "STARTPOSITION", "FINALPOSITION", "BESTLAPTIME", "OVERALLTIME", "LAPSDONE");

			if(in_array($elem, $elems)) {
				$driver[count($driver) - 1][strtolower($elem)] = $text;
			}
		}

		# Parse the XML file
		$file = $_FILES['userfile']['tmp_name'];
		$parser = xml_parser_create();
		if (!($fp = fopen($file, "r"))) {
			show_error("Could not open XML file");
			return;
		}
		$data = fread($fp, filesize($file));
		fclose($fp);
		xml_set_element_handler($parser, "startElement", "endElement");
		xml_set_character_data_handler($parser, "textData");
		xml_parse($parser, $data, true);
		xml_parser_free($parser);

		# Sort out drivers by final position
		foreach($driver as $key => $row) {
			$position[$key] = $row['position'];
		}
		array_multisort($position, SORT_ASC, SORT_NUMERIC, $driver);

		# Get driver with fastest lap
		$bestlapdriver = null;
		$bestlap = null;
		foreach($driver as $key => $row) {
			if($bestlap == null || $row['bestlaptime'] < $bestlap) {
				$bestlap = $row['bestlaptime'];
				$bestlapdriver = $key;
			}
		}
		$driver[$bestlapdriver]['fastestlap'] = 1;
	}
}

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT r.*, d.name dname, rs.name rsname, s.name sname FROM race r JOIN division d ON (d.id = r.division) JOIN point_ruleset rs ON (rs.id = r.ruleset) LEFT JOIN season s ON (s.id = r.season) WHERE r.id='$id' ORDER BY r.date DESC";
$result = mysqli_query($link,$query);
if(!$result) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($result) == 0){
	show_error("Race does not exist\n");
	return;
}
$item = mysqli_fetch_array($result);

$date = strtotime($item['date']);
?>
<h1>Import LFSPoints XML</h1>

<table border="0" width="100%">
<tr>
	<td width="120">Name:</td>
	<td><?php echo $item['name']?></td>
	<td>Laps:</td>
	<td><?php echo $item['laps']?></td>
</tr>
<tr>
	<td>Track:</td>
	<td><?php echo $item['track']?></td>
	<?PHP if($item['season'] == 0) { ?>
	<td>Division/Ruleset:</td>
	<td><?php echo $item['dname']?> / <?php echo $item['rsname']?></td>
	<?PHP } else { ?>
	<td>Season / Division:</td>
	<td><?php echo $item['sname']?> / <?php echo $item['dname']?></td>
	<?PHP } ?>
</tr>
<tr>
	<td>Date/Time:</td>
	<td>
		<?php echo date("j F Y, H:i", $date)?>
	</td>
	<td>Max players:</td>
	<td><?php echo $item['maxplayers']?></td>
</tr>
</table>
<?PHP if(!$upload) { ?>
<br/>
<form action=".?page=race_result_import_lfspoints" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="2">
<tr>
	<td>XML file:</td>
	<td><input type="file" name="userfile"/></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" class="button submit" value="Upload"/>
		<input type="hidden" name="id" value="<?php echo $id?>"/>
		<input type="hidden" name="xml" value="1"/>
	</td>
</tr>
</table>
</form>
<?PHP } else { ?>
<?PHP require_once("results_functions.php"); ?>
<?
if($item['season'] == 0)
	$dquery = "SELECT td.id, t.name team, d.name driver FROM team_driver td JOIN team t ON (t.id = td.team) JOIN driver d ON (d.id = td.driver)";
else
	$dquery = "SELECT td.id, t.name team, d.name driver FROM season_team st JOIN team t ON (t.id = st.team) JOIN team_driver td ON (td.team = t.id) JOIN driver d ON (d.id = td.driver) WHERE st.season='{$item['season']}'";

$dresult = mysqli_query($link,$dquery);
if(!$dresult) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($dresult) == 0){
	show_error("No drivers exist in teams or no teams are in this season\n");
	return;
}

$drivers = array();
while($ditem = mysqli_fetch_array($dresult)) {
	$drivers[$ditem['id']]['name'] = $ditem['driver'];
	$drivers[$ditem['id']]['team'] = $ditem['team'];
}

function show_driver_combo($did = 0) {
	global $drivers;

	echo "<select name=\"driver[]\">\n";
	echo "<option value=\"\">&nbsp;</option>\n";
	foreach($drivers as $id => $driver) {
		echo "<option value=\"$id\"";
		if($id == $did) echo " selected";
		echo ">" . $driver['name'] . " (" . $driver['team'] . ")";
		echo "</option>\n";
	}
	echo "</select>\n";
}
?>
<form action="race_results_chg_do.php" method="post">
		<table border="0" cellspacing="0" cellpadding="1" width="100%">
		<tr class="head">
			<td>Driver</td>
			<td align="center">Grid</td>
			<td align="center">Pos</td>
			<td align="center">Laps</td>
			<td>Time</td>
			<td align="center"><span class="abbr" title="Fastest Lap">FL</span></td>
			<td>Status</td>
		</tr>
		<?PHP $style = "odd"; ?>
		<?PHP for($x = 0; $x < $item['maxplayers']; $x++) {
			if($x < count($driver)) {
				$ditem = $driver[$x];
				$drivername = $ditem['playername'];
				$grid = $ditem['startposition'];
				if($grid == 0) $grid = "";
				$position = $ditem['finalposition'];
				if($position == 0) $position = "";
				$laps = $ditem['lapsdone'];
				if($laps == 0) $laps = "";
				$time = $ditem['overalltime'] * 10;
				$fl = $ditem['fastestlap'];
				$status = 0;

				$hour = floor($time / 36000000);
				$time = $time % 36000000;
				$minute = floor($time / 600000);
				$time = $time % 600000;
				$second = floor($time / 10000);
				$ms = round(($time % 10000) / 10);
				$ms = sprintf("%03d", $ms);
			} else {
				$drivername = "";
				$grid = "";
				$position = "";
				$laps = "";
				$hour = "";
				$minute = "";
				$second = "";
				$ms = "";
				$fl = 0;
				$status = 3;
			}
			?>
			<tr class="<?php echo $style?>">
				<td><?php echo $drivername . (!empty($drivername) ? "<br/>" : "")?><?PHP show_driver_combo(); ?></td>
				<td align="center"><input type="text" name="grid[]" value="<?php echo $grid?>" size="2" maxlength="2"></td>
				<td align="center"><input type="text" name="pos[]" value="<?php echo $position?>" size="2" maxlength="2"></td>
				<td align="center"><input type="text" name="laps[]" value="<?php echo $laps?>" size="3" maxlength="3"></td>
				<td>
					<input type="text" name="hour[]" value="<?php echo $hour?>" style="text-align:right;" size="1" maxlength="2">h
					<input type="text" name="minute[]" value="<?php echo $minute?>" style="text-align:right;" size="1" maxlength="2">m
					<input type="text" name="second[]" value="<?php echo $second?>" style="text-align:right;" size="1" maxlength="2">s
					<input type="text" name="ms[]" value="<?php echo $ms?>" size="2" maxlength="3">
				</td>
				<td align="center"><input type="checkbox" name="fl[<?php echo $x?>]"<?php echo $fl==1?" checked":""?>></td>
				<td align="center">
					<select name="status[]">
						<?PHP foreach($race_status_s as $i => $s) { ?>
						<option value="<?php echo $i?>"<?php echo $i == $status ? " selected" : ""?>><?php echo $s?></option>
						<?PHP } ?>
					</select>
				</td>
			</tr>
		<?	$style = $style == "odd" ? "even" : "odd"; ?>
		<?PHP } ?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="6">
					<input type="hidden" name="id" value="<?php echo $id?>">
					<input type="hidden" name="season" value="<?php echo $item['season']?>">
					<input type="submit" class="button submit" value="Save results"/>
					<input type="button" class="button cancel" value="Cancel" onclick="history.go(-1);"/>
				</td>
			</tr>
		</table>
</form>
<?PHP } ?>
