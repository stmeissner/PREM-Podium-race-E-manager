<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?php
$id = addslashes($_GET['id']);

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database

$query = "SELECT r.*, s.ruleset sruleset, s.ruleset_qualifying srulesetqual
					FROM race r
					LEFT JOIN season s ON (s.id = r.season)
					WHERE r.id='$id'";

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

$squery = "SELECT s.*, d.name dname
					 FROM season s
					 JOIN division d ON (d.id = s.division)";

$sresult = mysqli_query($link,$squery);
if(!$sresult) {
	show_error("MySQL error: " . mysqli_error($link));
	return;
}

$dquery = "SELECT * FROM division";
$dresult = mysqli_query($link,$dquery);
if(!$dresult) {
	show_error("MySQL error: " . mysqli_error($link));
	return;
}

$rquery = "SELECT id, name FROM point_ruleset";
$rresult = mysqli_query($link,$rquery);
if(!$rresult) {
	show_error("MySQL error: " . mysqli_error($link));
	return;
}

// Check if race uses different ruleset
$different = false;
if($item['season'] != 0) {
	if($item['ruleset'] != $item['sruleset'])
		$different = true;
	if($item['ruleset_qualifying'] != $item['srulesetqual'])
		$different = true;
}
?>
<!--suppress ALL -->
<h1>Modify race</h1>

<form action="race_chg_do.php" method="post">
<table class="w3-table-all">
<tr style="width: 10em">
	<td width="120">Name:</td>
	<td><input type="text" name="name" value="<?php echo $item['name']?>" maxlength="30"></td>
</tr>
<tr>
	<td style="width: 10em">Track:</td>
	<td><input type="text" name="track" value="<?php echo $item['track']?>" maxlength="30"></td>
</tr>
<tr>
<td>Imagelink:</td>
	<td><input type="url" name="imagelink" value="<?php echo $item['imagelink']?>" size=100% maxlength="200"></td>
</tr>
<tr>
  <td>Forum link:</td>
	<td><input type="url" name="forumlink" value="<?php echo $item['forumlink']?>" size=100% maxlength="200"></td>
</tr>
<tr>
<td>Simresults Link:</td>
	<td><input type="url" name="simresults" value="<?php echo $item['simresults']?>" size=100% maxlength="200"></td>
</tr><tr>
<td>Replay:</td>
	<td><select name="replay">
			<optgroup label="(replays in /replays/)">
			<option value=""</option>
			</optgroup>
			<option value="<?php echo $item['replay']?>" selected="selected">Press F5</option>
	<?php
			 foreach(glob(dirname(__FILE__) . '/replays/*') as $filename){
			 $filename = basename($filename);
			 echo "<option value='" . $filename . "'>".$filename."</option>";
		}
?></td>
</tr>
<tr>
	<td>Laps:</td>
	<td><input type="text" name="laps" value="<?php echo $item['laps']?>" maxlength="3" size="3"></td>
</tr>
<tr>
	<td>Season:</td>
	<td>
		<select id="season" name="season" onchange="showOptions();">
		<option value="0">--NO SEASON--</option>
		<?PHP while($sitem = mysqli_fetch_array($sresult)) { ?>
			<option value="<?php echo $sitem['id']?>"<?php echo $item['season'] == $sitem['id'] ? " selected=\"1\"" : ""?>><?php echo $sitem['name']?> (<?php echo $sitem['dname']?>)</option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Different Ruleset:</td>
	<td><input id="chk_diff_ruleset" name="diff_ruleset" type="checkbox" onchange="showOptions();"<?php echo $different?" checked=\"1\"":""?>/></td>
</tr>
<tr id="division">
	<td>Division:</td>
	<td>
		<select name="division" onchange="void(0);">
		<?PHP while($ditem = mysqli_fetch_array($dresult)) { ?>
			<option value="<?php echo $ditem['id']?>"<?php echo $item['division'] == $ditem['id'] ? " selected" : ""?>><?php echo $ditem['name']?> (<?php echo $ditem['type']?>)</option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr id="ruleset">
	<td>Ruleset:</td>
	<td>
		<select name="ruleset" onchange="void(0);">
		<?PHP while($ritem = mysqli_fetch_array($rresult)) { ?>
			<option value="<?php echo $ritem['id']?>"<?php echo $item['ruleset'] == $ritem['id'] ? " selected" : ""?>><?php echo $ritem['name']?></option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr id="ruleset_qualifying">
	<td>Ruleset qualifying:</td>
	<td>
		<select name="ruleset_qualifying" onchange="void(0);">
		<?PHP mysqli_data_seek($rresult, 0); ?>
		<option value="">&nbsp;</option>
		<?PHP while($ritem = mysqli_fetch_array($rresult)) { ?>
			<option value="<?php echo $ritem['id']?>"<?php echo $item['ruleset_qualifying'] == $ritem['id'] ? " selected" : ""?>><?php echo $ritem['name']?></option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Date:</td>
	<td>
		<select name="day">
		<?PHP for($x = 1; $x <= 31; $x++) { ?>
			<option<?php echo date("j", $date) == $x ? " selected" : ""?>><?php echo sprintf("%02d", $x)?></option>
		<?PHP } ?>
		</select>
		<select name="month">
		<?PHP $months = array(1 => "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); ?>
		<?PHP for($x = 1; $x <= 12; $x++) { ?>
			<option<?php echo date("n", $date) == $x ? " selected" : ""?> value="<?php echo $x?>"><?php echo $months[$x]?></option>
		<?PHP } ?>
		</select>
		<select name="year">
		<?PHP for($x = 2000; $x <= 2050; $x++) { ?>
			<option<?php echo date("Y", $date) == $x ? " selected" : ""?>><?php echo sprintf("%04d", $x)?></option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Time:</td>
	<td>
		<select name="hour">
		<?PHP for($x = 0; $x <= 23; $x++) { ?>
			<option<?php echo date("H", $date) == $x ? " selected" : ""?>><?php echo sprintf("%02d", $x)?></option>
		<?PHP } ?>
		</select> :
		<select name="minute">
		<?PHP for($x = 0; $x <= 59; $x = $x + 5) { ?>
			<option<?php echo date("i", $date) == $x ? " selected" : ""?>><?php echo sprintf("%02d", $x)?></option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Max players:</td>
	<td><input type="text" name="maxplayers" value="<?php echo $item['maxplayers']?>" maxlength="3" size="3"></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="hidden" name="id" value="<?php echo $id?>">
		<input type="submit" class="button submit" value="Modify">
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
	var chk_diff_ruleset = ele("chk_diff_ruleset").checked;

	if(season === 0) {
		ele("diff_ruleset").style.display = "none";
		ele("division").style.display = "table-row";
		ele("ruleset").style.display = "table-row";
		ele("ruleset_qualifying").style.display = "table-row";
	}
	else {
		ele("diff_ruleset").style.display = "table-row";
		ele("division").style.display = "none";
		if(chk_diff_ruleset) {
			ele("ruleset").style.display = "table-row";
			ele("ruleset_qualifying").style.display = "table-row";
		} else {
			ele("ruleset").style.display = "none";
			ele("ruleset_qualifying").style.display = "none";
		}
	}
}
showOptions();
// -->
</script>
