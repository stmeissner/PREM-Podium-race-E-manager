<?PHP if(!defined("CONFIG")) exit();
if(!isset($login)) { show_error("You do not have administrator rights\n"); return; }

$id = intval($_GET['id']);
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT * FROM main_news WHERE id = '$id' LIMIT 1";
$result = mysqli_query($link,$query);
if(!$result) {
	show_error("MySQL error: " . mysqli_error($link));
	return;
}
if(mysqli_num_rows($result) == 0) {
	show_error("news does not exist\n");
	return;
}
$item = mysqli_fetch_array($result);
?>
<h1>Delete news</h1>

<form action="remove_news_do.php" method="post">
<table border="0">
<tr>
	<td>title:</td>
	<td><?=$item['title']?></td>
    <td>day:</td>
	<td><?=$item['day']?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" class="button submit" value="Delete">
		<input type="button" class="button cancel" value="Cancel" onclick="history.go(-1);">
		<input type="hidden" name="id" value="<?=$id?>">
	</td>
</tr>
</table>
</form>
