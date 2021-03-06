<?
require_once("session_start.php");
if(!isset($login)) error("You do not have administrator rights\n");

$name = htmlspecialchars($_POST['name']);
$driver = $_POST['driver'];

function has_duplicates($ar, $values_ok) {
	$has = array();

	if(!is_array($ar)) $ar = array($ar);
	if(!is_array($values_ok)) $values_ok = array($values_ok);

	foreach($ar as $a) {
		if(in_array($a, $values_ok)) continue;
		if(in_array($a, $has)) return true;
		array_push($has, $a);
	}
	return false;
}

$error = "";

if(empty($name)) $error .= "You must fill in a name\n";
if(has_duplicates($driver, 0)) $error .= "Duplicate drivers selected\n";

if(!empty($error)) error($error);

$msg = "";

$logo = htmlspecialchars($_POST['logo']);

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "SELECT * FROM team WHERE name = '$name'";
$result = mysqli_query($link,$query);
if(!$result) error("MySQL Error: " . mysqli_error($link) . "\n");
if(mysqli_num_rows($result) > 0) error("team name is already in use\n");

$query = "INSERT INTO team (name, logo) VALUES ('$name','$logo')";
$result = mysqli_query($link,$query);
if(!$result) error("MySQL Error: " . mysqli_error($link) . "\n");

$team_id = mysqli_insert_id($link);

if(is_array($driver) && count($driver > 0)) {
	$query_values = "";

	for($x = 0; $x < count($driver); $x++) {
		if(empty($driver[$x]))
			continue;

		$d = addslashes($driver[$x]);
		$query_values .= "('$team_id',  '$d'), ";
	}
	$query_values = substr($query_values, 0, -2);

	if(!empty($query_values)) {
		$query = "INSERT INTO team_driver (team, driver) VALUES $query_values";
		$result = mysqli_query($link,$query);
		if(!$result) error("MySQL Error: " . mysqli_error($link) . "\n");
	}
}

return_do(".?page=teams", "Team succesfully added\n$msg");
?>
