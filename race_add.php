<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?
$season = $_GET['season'];

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$squery = "SELECT s.*, d.name dname FROM season s JOIN division d ON (d.id = s.division)";
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
?>
<!--suppress ALL -->
<h1>Add race</h1>

<form action="race_add_do.php" method="post">
<table border="0">
<tr>
	<td width="120">Name:</td>
	<td><input type="text" name="name" maxlength="30"></td>
</tr>
<tr>
	<td>Track:</td>
	<td><input type="text" name="track" maxlength="30"></td>
</tr>
<tr>
	<td>image_link:</td>
	<td><input type="url" name="imagelink" maxlength="200"></td>
</tr>
<tr>
	<td>Forum link:</td>
	<td><input type="url" name="forumlink" maxlength="200"></td>
</tr>
<tr>
	<td>Laps:</td>
	<td><input type="text" name="laps" maxlength="3" size="3"></td>
</tr>
<tr>
	<td>Season:</td>
	<td>
		<select id="season" name="season" onchange="showOptions();">
		<option value="0">--NO SEASON--</option>
		<?PHP while($sitem = mysqli_fetch_array($sresult)) { ?>
			<option value="<?php echo $sitem['id']?>"<?php echo $season == $sitem['id'] ? " selected=\"1\"" : ""?>><?php echo $sitem['name']?> (<?php echo $sitem['dname']?>)</option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr id="diff_ruleset">
	<td>Different ruleset:</td>
	<td><input id="chk_diff_ruleset" name="diff_ruleset" type="checkbox" onchange="showOptions();"/></td>
</tr>
<tr id="division">
	<td>Division:</td>
	<td>
		<select name="division" onchange="void(0);">
		<?PHP while($ditem = mysqli_fetch_array($dresult)) { ?>
			<option value="<?php echo $ditem['id']?>"><?php echo $ditem['name']?> (<?php echo $ditem['type']?>)</option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr id="ruleset">
	<td>Ruleset:</td>
	<td>
		<select name="ruleset" onchange="void(0);">
		<?PHP while($ritem = mysqli_fetch_array($rresult)) { ?>
			<option value="<?php echo $ritem['id']?>"><?php echo $ritem['name']?></option>
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
			<option value="<?php echo $ritem['id']?>"><?php echo $ritem['name']?></option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Date:</td>
	<td>
		<select name="day">
		<?PHP for($x = 1; $x <= 31; $x++) { ?>
			<option<?php echo date("j") == $x ? " selected" : ""?>><?php echo sprintf("%02d", $x)?></option>
		<?PHP } ?>
		</select>
		<select name="month">
		<?PHP $months = array(1 => "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); ?>
		<?PHP for($x = 1; $x <= 12; $x++) { ?>
			<option<?php echo date("n") == $x ? " selected" : ""?> value="<?php echo $x?>"><?php echo $months[$x]?></option>
		<?PHP } ?>
		</select>
		<select name="year">
		<?PHP for($x = 2000; $x <= 2050; $x++) { ?>
			<option<?php echo date("Y") == $x ? " selected" : ""?>><?php echo sprintf("%04d", $x)?></option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Time:</td>
	<td>
		<select name="hour">
		<?PHP for($x = 0; $x <= 23; $x++) { ?>
			<option<?php echo $x == "12" ? " selected" : ""?>><?php echo sprintf("%02d", $x)?></option>
		<?PHP } ?>
		</select> :
		<select name="minute">
		<?PHP for($x = 0; $x <= 59; $x = $x + 5) { ?>
			<option><?php echo sprintf("%02d", $x)?></option>
		<?PHP } ?>
		</select>
	</td>
</tr>
<tr>
	<td>Max players:</td>
	<td><input type="text" name="maxplayers" maxlength="3" size="3" value="20"></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" class="button submit" value="Add">
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
