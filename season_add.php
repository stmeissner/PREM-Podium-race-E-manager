<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$diquery = "SELECT * FROM division ORDER BY name ASC";
$diresult = mysqli_query($link,$diquery);
if(!$diresult) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($diresult) == 0) {
	show_error("There are no divisions.\n<a href=\"?page=division_add\">Add one</a> first.\n");
	return;
}

$rsquery = "SELECT * FROM point_ruleset ORDER BY name ASC";
$rsresult = mysqli_query($link,$rsquery);
if(!$rsresult) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($rsresult) == 0) {
	show_error("There are no rulesets.\n<a href=\"?page=point_add\">Add one</a> first.\n");
	return;
}
?>
<h1>Add season</h1>

<form action="season_add_do.php" method="post">
<table class="w3-table-all">
<tr>
	<td style="vertical-align:middle;width:25%;">Name:</td>
	<td style="vertical-align:middle"><input type="text" name="name" size="35" maxlength="30"></td>
</tr>
<tr>
	<td style="vertical-align:middle">Division:</td>
	<td style="vertical-align:middle">
	<select name="division">
	<?PHP while($diitem = mysqli_fetch_array($diresult)) { ?>
		<option value="<?php echo $diitem['id']?>"><?php echo $diitem['name']?></option>
	<?PHP } ?>
	</select>
	</td>
</tr>
<tr>
	<td style="vertical-align:middle">Ruleset:</td>
	<td style="vertical-align:middle">
	<select name="ruleset">
	<?PHP while($rsitem = mysqli_fetch_array($rsresult)) { ?>
		<option value="<?php echo $rsitem['id']?>"><?php echo $rsitem['name']?></option>
	<?PHP } ?>
	</select>
	</td>
</tr>
<tr>
	<td style="vertical-align:middle">Ruleset qualifying:</td>
	<td style="vertical-align:middle">
	<select name="ruleset_qualifying">
	<option value="0">&nbsp;</option>
	<?PHP mysqli_data_seek($rsresult, 0); ?>
	<?PHP while($rsitem = mysqli_fetch_array($rsresult)) { ?>
		<option value="<?php echo $rsitem['id']?>"><?php echo $rsitem['name']?></option>
	<?PHP } ?>
	</select>
	</td>
</tr>
<tr>
	<td style="vertical-align:middle">Max teams:</td>
	<td style="vertical-align:middle"><input type="text" name="maxteams" maxlength="3" size="2" value="10"></td>
</tr>
<tr>
	<td style="vertical-align:middle">Series logo for Simresults:</td>
	<td style="vertical-align:middle"><input type="url" name="series_logo_simresults" maxlength="200"></td>
</tr>
<tr>
	<td style="vertical-align:middle">Summary of Provisionals and <br>withdrawned races per driver:</td>
	<td style="vertical-align:middle"><input type="text" name="prov_quantity" maxlength="1" size="2" value="0"></td>
</tr>

<tr>
	<td style="vertical-align:middle">(You have to add the teams with <br>the season change function later)&nbsp;</td>
	<td>
		<input type="submit" class="button submit" value="Add">
		<input type="button" class="button cancel" value="Cancel" onclick="history.go(-1);">
	</td>
</tr>
</table>
</form>
