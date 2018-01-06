<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?PHP
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT * FROM cars";
$cars = mysqli_query($link,$query);
if(!$cars) {
	show_error("MySQL error: " . mysqli_error($link));
	return;
}

?>
<h1>Cars</h1>
<a href=".?page=car_edit_json_ac">Import car_ui.json with possibility to edit the file prior (fixing errors in it)</a></br>
<a href=".?page=car_add">Add car manually</a>
<?PHP
if(mysqli_num_rows($cars) == 0) {
	show_msg("No cars found\n");
	return;
}
?>
<div class="w3-container">
<table class="w3-table-all">
<tr class="w3-dark-grey">
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th>Sim</th>
	<th style="white-space:nowrap">Brand</th>
	<th style="white-space:nowrap">Name</th>
	<th style="white-space:nowrap">Simcode</th>
	<th style="text-align:center">Badge</th>
	<th>Horsepower</th>
	<th>Torque</th>
	<th>Weight</th>
	<th>Description</th>
</tr>
<?PHP
while($item = mysqli_fetch_array($cars)) {
?>
<tr>
	<td>
		<a href=".?page=car_chg&amp;id=<?=$item['id']?>"><img src="images/edit16.png" alt="chg"></a>
	</td>
	<td>
		<a href=".?page=car_rem&amp;id=<?=$item['id']?>"><img src="images/delete16.png" alt="rem"></a>
	</td>
	<td><?=$item['sim']?></td>
	<td style="white-space:nowrap";><?=$item['brand']?></td>
	<td style="white-space:nowrap";><?=$item['name']?></td>
	<td style="white-space:nowrap";><?=$item['code']?></td>
	<td style="text-align:center"><img src="images/badges/<?=$item['badge']?>" height="30" alt="<?$item['brand']?>"></td>
	<td><?=$item['horsepower']?></td>
	<td><?=$item['torque']?></td>
	<td><?=$item['weight']?></td>
	<td style="white-space: normal"><?=$item['description']?></td>
</tr>
<?PHP } ?>
</table>
</div>
