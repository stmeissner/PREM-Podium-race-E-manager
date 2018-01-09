<?PHP if (!defined("CONFIG"))    exit();
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$sql_drivers = "SELECT driver.id as driverID, driver.name as driverName, team.team as teamID FROM driver LEFT JOIN team_driver as team ON team.driver = driver.id ORDER BY driver.id LIMIT 0, 30";
$exe_drivers = mysqli_query($link,$sql_drivers);
if (!$exe_drivers) {
    show_error("MySQL Error: " . mysqli_error($link) . "\n");
    return;
}
while ($drivers = mysqli_fetch_array($exe_drivers)) {
	$driver[$drivers['teamID']][$drivers['driverID']] = $drivers['driverName'];
}
mysqli_free_result($exe_drivers);
if (!isset($driver)) {
    show_error("Drivers has been not found.\n");
    return;
}
$teams = "SELECT `team`.`id`, `team`.`name` , `team`.`logo` FROM team ORDER BY `team`.`name` ASC";
$result = mysqli_query($link,$teams);
if (!$result) {
    show_error("MySQL Error: " . mysqli_error($link) . "\n");
    return;
}
?>
<h1>Teams</h1>
<div class="w3-container">
<div class="w3-responsive">
<table class="w3-table-all">
	<tr class="w3-dark-grey">
		<td><h1><strong>Name</strong></h1></td>
		<td><h1><strong>Drivers</strong></h1></td>
		<td><h1><strong>Logo</strong></h1></td>
	</tr>
	<?php
	#$style = "odd";
	while ($sitem = mysqli_fetch_array($result)) {
	 if ($sitem['logo'] == '') { $url = 'images/logo.png' ; } else { $url = $sitem['logo']; }
	?>
	<tr class="w3-hover-green">
	<!--<tr class="<?php echo  $style ?>">-->
	<td><?php echo  $sitem['name'] ?></td><!--team name-->
	<td><!--driver name-->
		<?php
		if (is_array($driver[$sitem['id']])) {
			foreach ($driver[$sitem['id']] as $driverID => $driverName) {
				echo "<li>".$driverName."</li>";
			}
		}
		?>
	</td><!--driver name-->
	<td><a><img src="<?php echo $url;?>" width="150" height="150"/></a></td><!--url logo-->
	</tr>
	<?
	#$style = $style == "odd" ? "even" : "odd";
	}
	mysqli_free_result($result);
	?>
</table>
</div>
</div>
