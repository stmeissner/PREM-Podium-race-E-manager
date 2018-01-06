<?PHP if(!defined("CONFIG")) exit(); ?>
<?
require_once("results_functions.php");

$race = addslashes($_GET['race']);

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT r.*, s.name sname, s.series_logo_simresults slogo, d.name dname, rs.name rsname, qrs.name qrsname
					FROM race r
					LEFT JOIN season s ON (s.id = r.season)
					JOIN division d ON (d.id = r.division)
					JOIN point_ruleset rs ON (rs.id = r.ruleset)
					LEFT JOIN point_ruleset qrs ON (qrs.id = r.ruleset_qualifying)
					WHERE r.id=$race";
$result = mysqli_query($link,$query);
if(!$result) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($result) == 0) {
	show_error("Race does not exist\n");
	return;
}

$item = mysqli_fetch_array($result);
$date = strtotime($item['date']);

$dquery = "SELECT rd.*, d.name dname, d.country dcountry, t.name tname
					 FROM race_driver rd
					 JOIN team_driver td ON (td.id = rd.team_driver)
					 JOIN team t ON (t.id = td.team)
					 JOIN driver d ON (d.id = td.driver)
					 WHERE rd.race=$race AND (rd.status = 0)
					 ORDER BY rd.position ASC";
$dresult = mysqli_query($link,$dquery);
if(!$dresult) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}

$ndquery = "SELECT rd.*, d.name dname, d.country dcountry, t.name tname
					  FROM race_driver rd
						JOIN team_driver td ON (td.id = rd.team_driver)
						JOIN team t ON (t.id = td.team)
						JOIN driver d ON (d.id = td.driver)
						WHERE rd.race=$race AND (rd.status != 0)
						ORDER BY rd.position ASC";
$ndresult = mysqli_query($link,$ndquery);
if(!$dresult) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}

$rsquery = "SELECT * FROM point_ruleset WHERE id='{$item['ruleset']}'";
$rsresult = mysqli_query($link,$rsquery);
if(!$rsresult) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($rsresult) == 0) {
	show_error("Ruleset does not exist\n");
	return;
}
$ruleset = mysqli_fetch_array($rsresult);

if($item['ruleset_qualifying'] != 0) {
	$qrsquery = "SELECT * FROM point_ruleset WHERE id='{$item['ruleset_qualifying']}'";
	$qrsresult = mysqli_query($link,$qrsquery);
	if(!$qrsresult) {
		show_error("MySQL Error: " . mysqli_error($link) . "\n");
		return;
	}
	if(mysqli_num_rows($qrsresult) == 0) {
		show_error("Qualifying ruleset does not exist\n");
		return;
	}
	$ruleset_qualifying = mysqli_fetch_array($qrsresult);
}
?>
<h1>Race results</h1>
<div class="w3-container">
<div class="w3-responsive">
<table class="w3-table-all">
<tr class="w3-dark-grey">
	<td width="20%">Name:</td>
	<td width="30%"><?=$item['name']?></td>
	<td width="20%">Laps:</td>
	<td width="30%"><?=$item['laps']?></td>
</tr>
<tr class="w3-grey">
	<td>Track:</td>
	<td><?=$item['track']?></td>
	<?PHP if($item['season'] == 0) { ?>
	<td>Division/Ruleset:</td>
	<td><?=$item['dname']?> / <?=$item['rsname']?><?=!empty($item['qrsname']) ? " (qualifying: " . $item['qrsname'] : ""?>)</td>
	<?PHP } else { ?>
	<td>Season / Division:</td>
	<td><?=$item['sname']?> / <?=$item['dname']?></td>
	<?PHP } ?>
</tr>
<tr class="w3-grey">
	<td>Date/Time:</td>
	<td>
		<?=date("j F Y, H:i", $date)?>
	</td>
	<td>Max players:</td>
	<td><?=$item['maxplayers']?></td>
</tr>
<tr class="w3-grey">
    <td>Replay:</td>
    <td><a href="<?=$item['replay']?>" target="_blank"><img src="images/replay.png" alt="replay"></a></td>
		<td>Link to Forumthread:</td>
    <td><a href="<?=$item['forumlink']?>" target="_blank"><img src="images/forum.png" alt="Discussion"></a></td>

</tr>
<tr class="w3-amber">
	<td colspan="4">
	<div align="center">
	<strong>
	<?
	if($item['progress'] == RACE_NEW) {
		echo "Race is planned\n";
	}
	elseif($item['progress'] == RACE_QUALIFYING) {
		if($item['result_official'])
			echo "Official qualifying results\n";
		else
			echo "Unofficial qualifying results\n";
	}
	elseif($item['progress'] == RACE_RACE) {
		if($item['result_official'])
			echo "Official race results\n";
		else
			echo "Unofficial race results\n";
	}
	?>
	</strong>
	</div>
	</td>
</tr>
</table>
</div>
</div>

<div class="w3-container">
<div class="w3-responsive">
<table class="w3-table-all">
<tr class="w3-dark-grey">
	<td>Pos&nbsp;</td>
	<td>Driver</td>
	<td>Car #</td>
	<td>Country</td>
	<td>Team</td>
	<?PHP if($item['progress'] != RACE_NEW) { ?>
	<td align="right">Qual</td>
	<?PHP if($item['progress'] != RACE_QUALIFYING) { ?>
	<td align="right">Laps</td>
	<td align="right"><span class="abbr" title="Fastest Lap">FL</span></td>
	<td align="right">Time</td>
	<td align="right">Gap</td>
	<td style="width:50px;vertical-align:bottom;background-color:transparent;text-align:right;color:white;font-weight:bold;">Pts</td>
	<?PHP } ?>
	<?PHP } ?>
</tr>
<?

while($ditem = mysqli_fetch_array($dresult)) {
	if(!isset($best_time)) $best_time = $ditem['time'];
	if(!isset($most_laps)) $most_laps = $ditem['laps'];
	if($ditem['status'] != 0)
		$time = $race_status_s[$ditem['status']];
	else
		$time = time_hr($ditem['time']);

	if($most_laps > 0) {
		$laps = $ditem['laps'] . "/" . $item['laps'];
		if($ditem['laps'] < $most_laps)
			$gap = "+" . ($most_laps - $ditem['laps']) . " Laps";
		elseif($best_time > 0) {
			$time_gap = $ditem['time'] - $best_time;
			if($time_gap > 0)
				$gap = "+" . time_hr($ditem['time'] - $best_time);
			else
				$gap = time_hr($ditem['time'] - $best_time);
		}
		else {
			$gap = "";
			$time = "";
		}
	}
	else {
		$laps = "";
		$gap = "";
		$time = "";
	}
?>

<tr class="w3-hover-green">
	<td align="right"><?=++$position?>&nbsp;</td>
	<td><?=$ditem['dname']?></td>
	<td><?=$ditem['dplate']?></td>
	<td><img src="images/flags/<?=$ditem['dcountry']?>.png"></td>
	<td><?=$ditem['tname']?></td>
	<?PHP if($item['progress'] != RACE_NEW) { ?>
	<td align="right"><?=$ditem['grid']?></td>
	<?PHP if($item['progress'] != RACE_QUALIFYING) { ?>
	<td align="right"><?=$laps?></td>
	<td align="right"><?if($ditem['fastest_lap']=='1') echo "<img src=\"images/chrono.png\" alt=\"yes\">";?></td>
	<td align="right"><?=$time?></td>
	<td align="right"><?=$gap?></td>
	<td style="background-color:transparent; text-align: right; color:black; font-weight: bold;"><?=points_total($position, $ditem['grid'], $ditem['fastest_lap'], $ruleset)?></td>
	<?PHP } ?>
	<?PHP } ?>
</tr>
<?
}

while($ditem = mysqli_fetch_array($ndresult)) {
	if(!isset($best_time)) $best_time = $ditem['time'];
	if(!isset($most_laps)) $most_laps = $ditem['laps'];
	if($ditem['status'] != 0)
		$time = $race_status_s[$ditem['status']];
	else
		$time = time_hr($ditem['time']);

	if($most_laps > 0) {
		$laps = $ditem['laps'] . "/" . $item['laps'];
		if($ditem['laps'] < $most_laps)
			$gap = "+" . ($most_laps - $ditem['laps']) . " Laps";
		elseif($best_time > 0) {
			$time_gap = $ditem['time'] - $best_time;
			if($time_gap > 0)
				$gap = "+" . time_hr($ditem['time'] - $best_time);
			else
				$gap = time_hr($ditem['time'] - $best_time);
		}
		else {
			$gap = "";
			$time = "";
		}
	}
	else {
		$laps = "";
		$gap = "";
		$time = "";
	}
?>
<tr class="w3-hover-green">
	<td align="right">-&nbsp;</td>
	<td><?=$ditem['dname']?></td>
	<td><?=$ditem['dplate']?></td>
  <td><img src="images/flags/<?=$ditem['dcountry']?>.png"></td>
	<td><?=$ditem['tname']?></td>
	<?PHP if($item['progress'] != RACE_NEW) { ?>
	<td align="right"><?=$ditem['grid']?></td>
	<?PHP if($item['progress'] != RACE_QUALIFYING) { ?>
	<td align="right"><?=$laps?></td>
	<td align="right"><?if($ditem['fastest_lap']=='1') echo "<img src=\"images/chrono.png\" alt=\"yes\">";?></td>
	<td align="right"><?=$race_status_s[$ditem['status']]?></td>
	<td align="right">-</td>
	<td align="right">-</td>
	<?PHP } ?>
	<?PHP } ?>
</tr>
<?
}
?>
</table>
</div>
</div>
</br>
<div class="w3-container">
<div class="w3-responsive">
<iframe src="<?=$item['simresults'];?>?logo=<?=$item['slogo'];?>" width="100%" height="1080px"></iframe>
</div>
</div>
