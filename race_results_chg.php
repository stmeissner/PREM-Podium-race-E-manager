<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>

<?
require_once("results_functions.php");

$id = addslashes($_GET['id']);

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT r.*, d.name dname, rs.name rsname, s.name sname
					FROM race r
					JOIN division d ON (d.id = r.division)
					JOIN point_ruleset rs ON (rs.id = r.ruleset)
					LEFT JOIN season s ON (s.id = r.season)
					WHERE r.id='$id' ORDER BY r.date DESC";
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

if($item['season'] == 0)
	$dquery = "SELECT td.id, t.name team, d.name driver, d.plate dplate
						 FROM team_driver td
						 JOIN team t ON (t.id = td.team)
						 JOIN driver d ON (d.id = td.driver)";
else
	$dquery = "SELECT td.*, t.name team, d.name driver, d.plate dplate
						 FROM season_team st
						 JOIN team t ON (t.id = st.team)
						 JOIN team_driver td ON (td.team = t.id)
						 JOIN driver d ON (d.id = td.driver)
						 WHERE st.season='{$item['season']}'";

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
	$drivers[$ditem['id']]['cartype'] = $ditem['cartype'];
	$drivers[$ditem['id']]['ballast'] = $ditem['ballast'];
	$drivers[$ditem['id']]['restrictor'] = $ditem['restrictor'];
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

$rdquery = "SELECT * FROM race_driver WHERE race='$id' ORDER BY position ASC, time ASC, grid ASC";
$rdresult = mysqli_query($link,$rdquery);
if(!$rdresult) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}

?>
<h1>Modify race results</h1>

<br/>
<a href="?page=race_result_import_rfactor&amp;id=<?php echo $id?>"><img src="images/properties16.png" alt=""/> Import rFactor XML</a> |
<a href="?page=race_result_import_lfspoints&amp;id=<?php echo $id?>"><img src="images/properties16.png" alt=""/> Import LFSPoints XML</a> |
<a href="?page=race_result_import_ac&amp;id=<?php echo $id?>"><img src="images/properties16.png" alt=""/> Import Assetto Corsa JSON</a><br/>

<br/>

<form action="race_results_chg_do.php" method="post">
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
<table>
<tr>
	<td>
	Official result?
	</td>
	<td colspan="3">
	<input type="checkbox" name="official"<?php echo $item['result_official']=='1'?" checked=\"1\"":""?>>
	</td>
	<td>
	Withdraw Race?
	</td>
	<td colspan="3">
	<input type="checkbox" name="withdrawn"<?php echo $item['withdrawn']=='1'?" checked=\"1\"":""?>>
	</td>
</tr>
<tr>
	<td>Race Comment (max. 200 char)</td>
	<td><textarea maxlength="200" name="comment" cols="70" rows="3"><?echo $item['comment'];?></textarea></td>
	</tr>
</table>
<table>
<tr>
	<td colspan="4">
		<table class="w3-table-all">
		<tr class="w3-dark-grey">
			<td style="text-align: center;">Driver (Team)</td>
			<td style="text-align: center;">Car #</td>
			<td style="text-align: center;">Car Type</td>
			<td style="text-align: center;">Ballast</td>
			<td style="text-align: center;">Restrictor</td>
			<td style="text-align: center;">Grid</td>
			<td style="text-align: center;">Pos</td>
			<td style="text-align: center;">Laps</td>
			<td style="text-align: center;">Time</td>
			<td style="text-align: center;"><span class="abbr" title="Fastest Lap">FL</span></td>
			<td style="text-align: center;">Status</td>
		</tr>
		<?PHP $style = "odd"; ?>
		<?PHP for($x = 0; $x < $item['maxplayers']; $x++) {
			if($rditem = mysqli_fetch_array($rdresult)) {
				$driver = $rditem['team_driver'];
				$dplate = $rditem['dplate'];
				$driver_cartype = $rditem['cartype'];
				$driver_ballast = $rditem['ballast'];
				$driver_restrictor = $rditem['restrictor'];
				$grid = $rditem['grid'];
				if($grid == 0) $grid = "";
				$position = $rditem['position'];
				if($position == 0) $position = "";
				$laps = $rditem['laps'];
				if($laps == 0) $laps = "";
				$time = $rditem['time'];
				$fl = $rditem['fastest_lap'];
				$status = $rditem['status'];

				$hour = floor($time / 3600000);
				$time = $time % 3600000;
				$minute = floor($time / 60000);
				$time = $time % 60000;
				$second = floor($time / 1000);
				$ms = $time % 1000;
			} else {
				$driver = 0;
				$grid = "";
				$position = "";
				$laps = "";
				$hour = "";
				$minute = "";
				$second = "";
				$ms = "";
				$fl = 0;
				$status = 0;
			}
			?>
			<tr class="w3-hover-green">
				<td style="text-align: center; padding: 0px;"><?PHP show_driver_combo($driver) ?></td>
				<td style="text-align: center; padding: 0px;"><input type="text" name="dplate[]" value="<?php echo $dplate?>" size="2" maxlength="3"></td>
				<td style="text-align: center; padding: 0px;"><input type="text" name="cartype[]" value="<?php echo $driver_cartype?>" size="20" maxlength="50"></td>
				<td style="text-align: center; padding: 0px;"><input type="number" style="width: 4em" name="ballast[]" value="<?php echo $driver_ballast?>" min="0" max="200" step="5"></td>
				<td style="text-align: center; padding: 0px;"><input type="number" style="width: 4em" name="restrictor[]" value="<?php echo $driver_restrictor?>" min="0" max="100" step="5"></td>
				<td style="text-align: center; padding: 0px;"><input type="text" name="grid[]" value="<?php echo $grid?>" size="1" maxlength="2"></td>
				<td style="text-align: center; padding: 0px;"><input type="text" name="pos[]" value="<?php echo $position?>" size="1" maxlength="2"></td>
				<td style="text-align: center; padding: 0px;"><input type="text" name="laps[]" value="<?php echo $laps?>" size="1" maxlength="3"></td>
				<td style="text-align: center; padding: 0px;">
					<input type="text" name="hour[]" value="<?php echo $hour?>" style="text-align:right;" size="1" maxlength="2">h
					<input type="text" name="minute[]" value="<?php echo $minute?>" style="text-align:right;" size="1" maxlength="2">m
					<input type="text" name="second[]" value="<?php echo $second?>" style="text-align:right;" size="1" maxlength="2">s
					<input type="text" name="ms[]" value="<?php echo $ms?>" size="2" maxlength="3">
				</td>
				<td style="text-align: center; padding: 0px;"><input type="checkbox" name="fl[<?php echo $x?>]"<?php echo $fl==1?" checked":""?>></td>
				<td style="text-align: center; padding: 0px;">
					<select name="status[]">
						<?PHP foreach($race_status_s as $i => $s) { ?>
						<option value="<?php echo $i?>"<?php echo $i == $status ? " selected" : ""?>><?php echo $s?></option>
						<?PHP } ?>
					</select>
				</td>
			</tr>
		<?	$style = $style == "odd" ? "even" : "odd"; ?>
		<?PHP } ?>
		</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="3">
		<input type="hidden" name="id" value="<?php echo $id?>">
		<input type="hidden" name="season" value="<?php echo $item['season']?>">
		<input type="submit" class="button submit" value="Save results">
		<input type="button" class="button cancel" value="Cancel" onclick="history.go(-1);">
	</td>
</tr>
</table>
</form>

<script type="text/javascript" language="javascript" src="functions.js"></script>
<script type="text/javascript" language="javascript">
<!--
function showOptions() {
	var season = ele("season").value;

	if(season === 0) {
		ele("division").style.display = "table-row";
		ele("ruleset").style.display = "table-row";
	}
	else {
		ele("division").style.display = "none";
		ele("ruleset").style.display = "none";
	}
}
showOptions();
// -->
</script>
