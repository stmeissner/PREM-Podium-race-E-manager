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

?>
<h1>Modify ruleset</h1>

<form action="point_chg_do.php" method="post">
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
			<td><input type="text" name="rp1" value="<?php echo $item['rp1']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">2:</td>
			<td><input type="text" name="rp2" value="<?php echo $item['rp2']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">3:</td>
			<td><input type="text" name="rp3" value="<?php echo $item['rp3']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">4:</td>
			<td><input type="text" name="rp4" value="<?php echo $item['rp4']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">5:</td>
			<td><input type="text" name="rp5" value="<?php echo $item['rp5']?>" size="3" maxlength="3"></td>
		</tr>
		<tr>
			<td width="22" align="right">6:</td>
			<td><input type="text" name="rp6" value="<?php echo $item['rp6']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">7:</td>
			<td><input type="text" name="rp7" value="<?php echo $item['rp7']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">8:</td>
			<td><input type="text" name="rp8" value="<?php echo $item['rp8']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">9:</td>
			<td><input type="text" name="rp9" value="<?php echo $item['rp9']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">10:</td>
			<td><input type="text" name="rp10" value="<?php echo $item['rp10']?>" size="3" maxlength="3"></td>
		</tr>
		<tr>
			<td width="22" align="right">11:</td>
			<td><input type="text" name="rp11" value="<?php echo $item['rp11']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">12:</td>
			<td><input type="text" name="rp12" value="<?php echo $item['rp12']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">13:</td>
			<td><input type="text" name="rp13" value="<?php echo $item['rp13']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">14:</td>
			<td><input type="text" name="rp14" value="<?php echo $item['rp14']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">15:</td>
			<td><input type="text" name="rp15" value="<?php echo $item['rp15']?>" size="3" maxlength="3"></td>
		</tr>
		<tr>
			<td width="22" align="right">16:</td>
			<td><input type="text" name="rp16" value="<?php echo $item['rp16']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">17:</td>
			<td><input type="text" name="rp17" value="<?php echo $item['rp17']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">18:</td>
			<td><input type="text" name="rp18" value="<?php echo $item['rp18']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">19:</td>
			<td><input type="text" name="rp19" value="<?php echo $item['rp19']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">20:</td>
			<td><input type="text" name="rp20" value="<?php echo $item['rp20']?>" size="3" maxlength="3"></td>
		</tr>
		<tr>
			<td width="22" align="right">21:</td>
			<td><input type="text" name="rp21" value="<?php echo $item['rp21']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">22:</td>
			<td><input type="text" name="rp22" value="<?php echo $item['rp22']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">23:</td>
			<td><input type="text" name="rp23" value="<?php echo $item['rp23']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">24:</td>
			<td><input type="text" name="rp24" value="<?php echo $item['rp24']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">25:</td>
			<td><input type="text" name="rp25" value="<?php echo $item['rp25']?>" size="3" maxlength="3"></td>
		</tr>
		<tr>
			<td width="22" align="right">26:</td>
			<td><input type="text" name="rp26" value="<?php echo $item['rp26']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">27:</td>
			<td><input type="text" name="rp27" value="<?php echo $item['rp27']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">28:</td>
			<td><input type="text" name="rp28" value="<?php echo $item['rp28']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">29:</td>
			<td><input type="text" name="rp29" value="<?php echo $item['rp29']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">30:</td>
			<td><input type="text" name="rp30" value="<?php echo $item['rp30']?>" size="3" maxlength="3"></td>
		</tr>
		<tr>
			<td width="22" align="right">31:</td>
			<td><input type="text" name="rp31" value="<?php echo $item['rp31']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">32:</td>
			<td><input type="text" name="rp32" value="<?php echo $item['rp32']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">33:</td>
			<td><input type="text" name="rp33" value="<?php echo $item['rp33']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">34:</td>
			<td><input type="text" name="rp34" value="<?php echo $item['rp34']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">35:</td>
			<td><input type="text" name="rp35" value="<?php echo $item['rp35']?>" size="3" maxlength="3"></td>
		</tr>
		<tr>
			<td width="22" align="right">36:</td>
			<td><input type="text" name="rp36" value="<?php echo $item['rp36']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">37:</td>
			<td><input type="text" name="rp37" value="<?php echo $item['rp37']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">38:</td>
			<td><input type="text" name="rp38" value="<?php echo $item['rp38']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">39:</td>
			<td><input type="text" name="rp39" value="<?php echo $item['rp39']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">40:</td>
			<td><input type="text" name="rp40" value="<?php echo $item['rp40']?>" size="3" maxlength="3"></td>
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
			<td><input type="text" name="qp1" value="<?php echo $item['qp1']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">2:</td>
			<td><input type="text" name="qp2" value="<?php echo $item['qp2']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">3:</td>
			<td><input type="text" name="qp3" value="<?php echo $item['qp3']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">4:</td>
			<td><input type="text" name="qp4" value="<?php echo $item['qp4']?>" size="3" maxlength="3"></td>
			<td width="22" align="right">5:</td>
			<td><input type="text" name="qp5" value="<?php echo $item['qp5']?>" size="3" maxlength="3"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>Fatest lap:</td>
	<td><input type="text" name="fl" value="<?php echo $item['fl']?>" size="3" maxlength="3"></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		Please note that by changing a ruleset, all the result related to it will be affected.<br>
		<br>
		<input type="hidden" name="id" value="<?php echo $id?>">
		<input type="submit" class="button submit" value="Modify">
		<input type="button" class="button cancel" value="Cancel" onclick="history.go(-1);">
	</td>
</tr>
</table>
</form>
