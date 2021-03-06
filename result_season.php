<? if(!defined("CONFIG")) exit(); ?>
<?
require_once("results_functions.php");

define("SHOW_POINTS", 0);
define("SHOW_INCREMENTAL", 1);
define("SHOW_POSITIONS", 2);

$season = $_GET['season'];
$show = isset($_GET['show']) ? $_GET['show'] : 0;

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
// Get season information
$query = "SELECT s.*, d.name dname, COUNT(r.id) racecount FROM season s JOIN division d ON (s.division = d.id) LEFT JOIN race r ON (r.season = s.id) WHERE s.id='$season' GROUP BY s.id";
$result = mysqli_query($link,$query);
if(!$result) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($result) == 0) {
	show_error("Season does not exist\n");
	return;
}

$item = mysqli_fetch_array($result);

// Get the rulesets
$rsquery = "SELECT * FROM point_ruleset";
$rsresult = mysqli_query($link,$rsquery);
if(!$rsresult) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}
if(mysqli_num_rows($rsresult) == 0) {
	show_error("Ruleset does not exist\n");
	return;
}
while($rsitem = mysqli_fetch_array($rsresult)) {
	$ruleset[$rsitem['id']] = $rsitem;
}

// Get all teams and driver for this season
$drquery = "SELECT d.id did, d.name dname, t.id tid, t.name tname FROM season_team st JOIN team t ON (st.team = t.id) JOIN team_driver td ON (td.team = t.id) JOIN driver d ON (d.id = td.driver) WHERE st.season = '$season' ORDER BY t.name ASC, d.name ASC";
$drresult = mysqli_query($link,$drquery);
if(!$drresult) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}

$team = array();
$driver = array();
while($dritem = mysqli_fetch_array($drresult)) {
	if(!isset($team[$dritem['tid']])) {
		$team[$dritem['tid']]['name'] = $dritem['tname'];
		$team[$dritem['tid']]['points'] = 0;
		$team[$dritem['tid']]['pointsrace'] = array();
		$team[$dritem['tid']]['pointsraceinc'] = array();
	}
	$driver[$dritem['did']]['name'] = $dritem['dname'];
	$driver[$dritem['did']]['team'] = $dritem['tname'];
	$driver[$dritem['did']]['points'] = 0;
	$driver[$dritem['did']]['pointsrace'] = array();
	$driver[$dritem['did']]['pointsraceinc'] = array();
}

$rquery = <<<EOF
SELECT
	r.id race, r.name rname, r.track rtrack, r.ruleset, r.ruleset_qualifying,
	td.driver, td.team,
	rd.fastest_lap, rd.grid, rd.status
FROM race r
	JOIN race_driver rd ON (rd.race = r.id)
	JOIN team_driver td ON (td.id = rd.team_driver)
WHERE r.season='$season' AND r.progress = 2 AND (rd.status = 0 OR rd.status = 1)
ORDER BY r.date ASC, rd.position ASC
EOF;
$rresult = mysqli_query($link,$rquery);
if(!$rresult) {
	show_error("MySQL Error: " . mysqli_error($link) . "\n");
	return;
}

$show_qualifypoint = ($ruleset_qualifying != 0);

$last_race = 0;
$race = 0;
$races = array();
/* Creates an array of all drivers and team, and their race information (points, positions) */
while($ritem = mysqli_fetch_array($rresult)) {
	if($last_race != $ritem['race']) {
		$position = 0;
		$race++;
		$races[$race]['id'] = $ritem['race'];
		$races[$race]['name'] = $ritem['rname'];
		$races[$race]['track'] = $ritem['rtrack'];
		$last_race = $ritem['race'];
	}

	if($ritem['status'] == 0) { // if status = OK (IE not DNF)
		// Assign points and position to driver for the race
		$position++;
		$driver[$ritem['driver']]['points'] += points_total($position, $ritem['grid'], $ritem['fastest_lap'], $ruleset[$ritem['ruleset']]);
		$driver[$ritem['driver']]['pointsrace'][$race] = points_total($position, $ritem['grid'], $ritem['fastest_lap'], $ruleset[$ritem['ruleset']]);
		$driver[$ritem['driver']]['pointsraceinc'][$race] += $driver[$ritem['driver']]['points'];
		$driver[$ritem['driver']]['position'][$race] = $position;

		// Assign points for the team
		$team[$ritem['team']]['points'] += $driver[$ritem['driver']]['pointsrace'][$race];
		$team[$ritem['team']]['pointsrace'][$race] += $driver[$ritem['driver']]['pointsrace'][$race];
		$team[$ritem['team']]['pointsraceinc'][$race] = $team[$ritem['team']]['points'];
	}

	if($ritem['ruleset_qualifying'] != 0) {
		// Qualifying points if set
		$show_qualifypoint = true;
		$driver[$ritem['driver']]['pointsqualifying'] += points_race($ritem['grid'], $ruleset[$ritem['ruleset_qualifying']]);
		$driver[$ritem['driver']]['pointsqualifyingrace'][$race] = points_race($ritem['grid'], $ruleset[$ritem['ruleset_qualifying']]);
		$driver[$ritem['driver']]['pointsqualifyingraceinc'][$race] = $driver[$ritem['driver']]['pointsqualifying'];
	}

}

usort($driver, "point_sort");
usort($team, "point_sort");
?>
<h1>Season results</h1>
<div class="w3-container">
<div class="w3-responsive">
<table class="w3-table-all">
<tr class="w3-dark-grey">
	<td width="20%">Name:</td>
	<td width="30%"><?=$item['name']?></td>
	<td width="20%">Races:</td>
	<td width="30%"><?=$item['racecount']?></td>
</tr>
<tr class="w3-grey">
	<td width="20%">Division:</td>
	<td width="30%"><?=$item['dname']?></td>
	<td width="20%">Ruleset:</td>
	<td width="30%"><?=$ruleset['name']?><?if(isset($ruleset_qualifying)) echo " (qual: " . $ruleset_qualifying['name'] . ")"?></td>
</tr>
<tr class="w3-green">
	<td colspan="4" align="center">
		<a href=".?page=result_season&amp;season=<?=$season?>&amp;show=<?=SHOW_POINTS?>">points per race</a> |
		<a href=".?page=result_season&amp;season=<?=$season?>&amp;show=<?=SHOW_INCREMENTAL?>">points incremental</a> |
		<a href=".?page=result_season&amp;season=<?=$season?>&amp;show=<?=SHOW_POSITIONS?>">positions</a>
	</td>
</tr>
</table>
</div>
</div>

<h2>Drivers</h2>
<div class="w3-container">
<div class="w3-responsive">
<table class="w3-table-all">
<tr class="w3-dark-grey">
	<td>&nbsp;</td>
	<td>Driver</td>
	<td>Team</td>
<? for($x = 1; $x <= $race; $x++) { ?>
	<td width="1" align="right"><javascript:void(0)" class="tablink" title="Click to more details"><div class="w3-topbar w3-bottombar w3-hover-border-red"><a href="?page=result_race&amp;race=<?=$races[$x]['id']?>"><img src="img_season_race.php?text=<?=urlencode($races[$x]['name'])?>&amp;text2=<?=urlencode($races[$x]['track'])?>" alt="<?=$x?>"></a></td>
<? } ?>
	<td width="1" align="right">Pts</td>
</tr>
<?
$style = "odd";
$pos = 0;
foreach($driver as $id => $ditem) {
?>
<tr class="w3-hover-green">
	<td width="1" align="right"><?=++$pos?>&nbsp;</td>
	<td><?=$ditem['name']?></td>
	<td><?=$ditem['team']?></td>
<?
$total = 0;
for($x = 1; $x <= $race; $x++) {
	switch($show) {
	case SHOW_POINTS:
		$data = !empty($ditem['pointsrace'][$x]) ? $ditem['pointsrace'][$x] : "-";
		break;
	case SHOW_INCREMENTAL:
		$total += $ditem['pointsrace'][$x];
		$data = $total;
		break;
	case SHOW_POSITIONS:
		$data = !empty($ditem['position'][$x]) ? $ditem['position'][$x] : "-";
		break;
	}
	?>
	<td width="1" align="right"><?=$data?></td>
<? } ?>
	<td width="1" align="right"><strong><?=!empty($ditem['points']) ? $ditem['points'] : "0" ?></strong></td>
</tr>
<?

} ?>
</table>
</div>
</div>

<h2>Teams</h2>
<div class="w3-container">
<div class="w3-responsive">
<table class="w3-table-all">
<tr class="w3-dark-grey">
	<td>&nbsp;</td>
	<td>Team</td>
<? for($x = 1; $x <= $race; $x++) { ?>
	<td width="1" align="right"><javascript:void(0)" class="tablink" title="Click to more details"><div class="w3-topbar w3-bottombar w3-hover-border-red"><a href="?page=result_race&amp;race=<?=$races[$x]['id']?>"><img src="img_season_race.php?text=<?=urlencode($races[$x]['name'])?>&amp;text2=<?=urlencode($races[$x]['track'])?>" alt="<?=$x?>"></a></td>
<? } ?>
	<td width="1" align="right">Pts</td>
</tr>
<?

$pos = 0;
foreach($team as $id => $titem) {
?>

<tr class="w3-hover-green">
	<td width="1" align="right"><?=++$pos?>&nbsp;</td>
	<td><?=$titem['name']?></td>
<?
$total = 0;
for($x = 1; $x <= $race; $x++) {
	switch($show) {
	case SHOW_POINTS:
	case SHOW_POSITIONS:
		$data = !empty($titem['pointsrace'][$x]) ? $titem['pointsrace'][$x] : "-";
		break;
	case SHOW_INCREMENTAL:
		$total += $titem['pointsrace'][$x];
		$data = $total;
		break;
	}
	?>
	<td width="1" align="right"><?=$data?></td>
<? } ?>
	<td width="1" align="right"><strong><?=!empty($titem['points']) ? $titem['points'] : "0" ?></strong></td>
</tr>
<?

} ?>
</table>
</div>
</div>

<? if($show_qualifypoint) {
	usort($driver, 'point_sort_qual');
	?>
<h2>Drivers qualifying</h2>
<div class="w3-container">
<div class="w3-responsive">
<table class="w3-table-all">
<tr class="w3-dark-grey">
	<td>&nbsp;</td>
	<td>Driver</td>
	<td>Team</td>
<? for($x = 1; $x <= $race; $x++) { ?>
	<td width="1" align="right"><javascript:void(0)" class="tablink" title="Click to more details"><div class="w3-topbar w3-bottombar w3-hover-border-red"><a href="?page=result_race&amp;race=<?=$races[$x]['id']?>"><img src="img_season_race.php?text=<?=urlencode($races[$x]['name'])?>&amp;text2=<?=urlencode($races[$x]['track'])?>" alt="<?=$x?>"></a></td>
<? } ?>
	<td width="1" align="right">Pts</td>
</tr>
<?

$pos = 0;
foreach($driver as $id => $ditem) {
?>

<tr class="w3-hover-green">
	<td width="1" align="right"><?=++$pos?>&nbsp;</td>
	<td><?=$ditem['name']?></td>
	<td><?=$ditem['team']?></td>
<? for($x = 1; $x <= $race; $x++) { ?>
	<td width="1" align="right"><?=!empty($ditem['pointsqualifyingrace'][$x]) ? $ditem['pointsqualifyingrace'][$x] : "-"?></td>
<? } ?>
	<td width="1" align="right"><strong><?=!empty($ditem['pointsqualifying']) ? $ditem['pointsqualifying'] : "0" ?></strong></td>
</tr>
<?

} ?>
</table>
</div>
</div>
<? } ?>
