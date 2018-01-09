<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?
$id = addslashes($_GET['id']);

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT * FROM point_ruleset WHERE id='$id'";
$result = mysqli_query($link,$query);
if(!$result) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($result) == 0){
	show_error("Ruleset does not exist\n");
	return;
}
$item = mysqli_fetch_array($result);

$error = "";

$squery = "SELECT s.name, d.name division FROM season s JOIN division d ON (s.division = d.id) WHERE (s.ruleset='$id' OR s.ruleset_qualifying='$id')";
$sresult = mysqli_query($link,$squery);
if(!$sresult) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($sresult) > 0) {
	$seasons = "";
	while($s = mysqli_fetch_array($sresult)) {
		$seasons .= "&bull; " . $s['name'] . " (" . $s['division'] . ")\n";
	}
	$error .= "Ruleset cannot be deleted because it is related to the following season(s):\n" . $seasons;
}

$rquery = "SELECT r.name, r.track FROM race r WHERE (r.ruleset='$id' OR r.ruleset_qualifying='$id') AND r.season='0'";
$rresult = mysqli_query($link,$rquery);
if(!$rresult) {
	show_error("MySQL error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($rresult) > 0) {
	$races = "";
	while($r = mysqli_fetch_array($rresult)) {
		$races .= "&bull; " . $r['name'] . " (" . $r['track'] . ")\n";
	}
	$error .= "Ruleset cannot be deleted because it is related to the following race(s):\n" . $races;
}

if(!empty($error)) {
	show_error($error);
	return;
}
?>
<h1>Delete ruleset</h1>

<form action="point_rem_do.php" method="post">
<table border="0">
<tr>
	<td width="120">Name ruleset:</td>
	<td><?php echo $item['name']?></td>
</tr>
<tr>
	<td>Race:</td>
	<td>
		<table border="0">
		<tr>
			<td width="22" align="right">1:</td>
			<td><?php echo $item['rp1']?></td>
			<td width="22" align="right">2:</td>
			<td><?php echo $item['rp2']?></td>
			<td width="22" align="right">3:</td>
			<td><?php echo $item['rp3']?></td>
			<td width="22" align="right">4:</td>
			<td><?php echo $item['rp4']?></td>
			<td width="22" align="right">5:</td>
			<td><?php echo $item['rp5']?></td>
		</tr>
		<tr>
			<td width="22" align="right">6:</td>
			<td><?php echo $item['rp6']?></td>
			<td width="22" align="right">7:</td>
			<td><?php echo $item['rp7']?></td>
			<td width="22" align="right">8:</td>
			<td><?php echo $item['rp8']?></td>
			<td width="22" align="right">9:</td>
			<td><?php echo $item['rp9']?></td>
			<td width="22" align="right">10:</td>
			<td><?php echo $item['rp10']?></td>
		</tr>
		<tr>
			<td width="22" align="right">11:</td>
			<td><?php echo $item['rp11']?></td>
			<td width="22" align="right">12:</td>
			<td><?php echo $item['rp12']?></td>
			<td width="22" align="right">13:</td>
			<td><?php echo $item['rp13']?></td>
			<td width="22" align="right">14:</td>
			<td><?php echo $item['rp14']?></td>
			<td width="22" align="right">15:</td>
			<td><?php echo $item['rp15']?></td>
			<td width="22" align="right">16:</td>
			<td><?php echo $item['rp16']?></td>
			<td width="22" align="right">17:</td>
			<td><?php echo $item['rp17']?></td>
			<td width="22" align="right">18:</td>
			<td><?php echo $item['rp18']?></td>
			<td width="22" align="right">19:</td>
			<td><?php echo $item['rp19']?></td>
			<td width="22" align="right">20:</td>
			<td><?php echo $item['rp20']?></td>
			<td width="22" align="right">21:</td>
			<td><?php echo $item['rp21']?></td>
			<td width="22" align="right">22:</td>
			<td><?php echo $item['rp22']?></td>
			<td width="22" align="right">23:</td>
			<td><?php echo $item['rp23']?></td>
			<td width="22" align="right">24:</td>
			<td><?php echo $item['rp24']?></td>
			<td width="22" align="right">25:</td>
			<td><?php echo $item['rp25']?></td>
			<td width="22" align="right">26:</td>
			<td><?php echo $item['rp26']?></td>
			<td width="22" align="right">27:</td>
			<td><?php echo $item['rp27']?></td>
			<td width="22" align="right">28:</td>
			<td><?php echo $item['rp28']?></td>
			<td width="22" align="right">29:</td>
			<td><?php echo $item['rp29']?></td>
			<td width="22" align="right">30:</td>
			<td><?php echo $item['rp30']?></td>
			<td width="22" align="right">31:</td>
			<td><?php echo $item['rp31']?></td>
			<td width="22" align="right">32:</td>
			<td><?php echo $item['rp32']?></td>
			<td width="22" align="right">33:</td>
			<td><?php echo $item['rp33']?></td>
			<td width="22" align="right">34:</td>
			<td><?php echo $item['rp34']?></td>
			<td width="22" align="right">35:</td>
			<td><?php echo $item['rp35']?></td>
			<td width="22" align="right">36:</td>
			<td><?php echo $item['rp36']?></td>
			<td width="22" align="right">37:</td>
			<td><?php echo $item['rp37']?></td>
			<td width="22" align="right">38:</td>
			<td><?php echo $item['rp38']?></td>
			<td width="22" align="right">39:</td>
			<td><?php echo $item['rp39']?></td>
			<td width="22" align="right">40:</td>
			<td><?php echo $item['rp40']?></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>Qualifying:</td>
	<td>
		<table border="0">
		<tr>
			<td width="22" align="right">1:</td>
			<td><?php echo $item['qp1']?></td>
			<td width="22" align="right">2:</td>
			<td><?php echo $item['qp2']?></td>
			<td width="22" align="right">3:</td>
			<td><?php echo $item['qp3']?></td>
			<td width="22" align="right">4:</td>
			<td><?php echo $item['qp4']?></td>
			<td width="22" align="right">5:</td>
			<td><?php echo $item['qp5']?></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>Fatest lap:</td>
	<td><?php echo $item['fl']?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>Are you sure that you want to delete this ruleset?</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="hidden" name="id" value="<?php echo $id?>">
		<input type="submit" class="button submit" value="Yes">
		<input type="button" class="button cancel" value="No" onclick="history.go(-1);">
	</td>
</tr>
</table>
</form>
