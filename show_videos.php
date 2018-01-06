<?PHP if (!defined("CONFIG"))
    exit();
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$video = "SELECT `id`, `video_name`, `video_url` FROM video ORDER BY `id` DESC";
$result = mysqli_query($link,$video);
if (!$result) {
    show_error("MySQL Error: " . mysqli_error($link) . "\n");
    return;
}
while ($sitem = mysqli_fetch_array($result)) {
	$url = $sitem['video_url'];
	?>
	<iframe id="ytplayer" type="text/html" width="420" height="345"
		src="<?php echo $url; ?>" frameborder="0" allowfullscreen></iframe>
	<?php
}
