<?PHP if(!defined("CONFIG")) exit();
if(!isset($login)) { show_error("You do not have administrator rights\n"); return; }
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$id = intval($_GET['id']);
if (isset($_POST['news'])) {
    $news = mysqli_real_escape_string($link,$_POST['news']);
    $id = addslashes($_POST['id']);
    $title = addslashes($_POST['title']);
    mysqli_query($link,"UPDATE main_news SET `title` = '$title', `news` = '$news' WHERE id='$id'");
}
$exe_news = mysqli_query($link,"SELECT title, news FROM main_news WHERE id='$id' LIMIT 1");
list($title, $news) = mysqli_fetch_array($exe_news);
mysqli_free_result($exe_news);
$news = htmlspecialchars($news);
?>

<form method="post" action="index.php?page=edit_news">
    <table border="0">
	<td width="120">title:</td>
	<td><input type="text" name="title" maxlength="30" value="<?php echo $title;?>"></td>
</tr>
</table>
    <textarea id="tinyeditor" name="news" cols="50" rows="15"><?php echo $news; ?></textarea>
    <input type="hidden" name="id" value="<?php echo $id;?>">
    <input type="submit" value="Save" />
</form>
