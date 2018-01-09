<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?
if(isset($_GET['filter'])) {
	$filter = $_GET['filter'];
	$query_where = "WHERE name LIKE '%$filter%'";
}
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT * FROM user $query_where ORDER BY name ASC";
$result = mysqli_query($link,$query);
if(!$result) {
	show_error("MySQL error: " . mysqli_error($link));
	return;
}

?>
<h1>Admins</h1>

Admin default user and password: admin/admin <strong>PLEASE CHANGE THIS PASSWORD URGENTLY!!!</strong>

<div align="right">
<form action="." method="GET">
<input type="hidden" name="page" value="users">
<input type="text" class="search" name="filter" value="<?php echo $_GET['filter']?>">
</form>
</div>
<a href=".?page=user_add">Add admin</a>
<?
if(mysqli_num_rows($result) == 0) {
	show_msg("No admins found\n");
	return;
}
?>
<div class="w3-container">
<table class="w3-table-all">
<tr class="w3-dark-grey">
<td>&nbsp;</td><td>Name</td></tr>

<?

#$style = "odd";
while($item = mysqli_fetch_array($result)) {
?>
<!--<tr class="<?php echo $style?>">-->
<tr class="w3-hover-green">
	<td>
		<a href=".?page=user_chg&amp;id=<?php echo $item['id']?>"><img src="images/edit16.png" alt="chg"></a>
		<a href=".?page=user_rem&amp;id=<?php echo $item['id']?>"><img src="images/delete16.png" alt="rem"></a>
	</td>
	<td><?php echo $item['name']?></td>
</tr>
<?
#$style = $style == "odd" ? "even" : "odd";
}
?>
</table>
