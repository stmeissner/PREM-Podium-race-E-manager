<?
require_once("session_start.php");
if(!isset($login)) error("You do not have administrator rights\n");

$id = addslashes($_POST['id']);
$sim = htmlspecialchars($_POST['sim']);
$brand = htmlspecialchars($_POST['brand']);
$name = htmlspecialchars($_POST['name']);
$code = htmlspecialchars($_POST['code']);
$badge = htmlspecialchars($_POST['badge']);
$horsepower = htmlspecialchars($_POST['horsepower']);
$torque = htmlspecialchars($_POST['torque']);
$weight = htmlspecialchars($_POST['weight']);
$description = htmlspecialchars($_POST['description']);

require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database
$query = "UPDATE cars SET sim='$sim', brand='$brand', name='$name', code='$code', badge='$badge',
                          horsepower='$horsepower', torque='$torque', weight='$weight', description='$description' WHERE id='$id'";
$result = mysqli_query($link,$query);
if(!$result) error("MySQL Error: " . mysqli_error($link) . "\n");

return_do(".?page=cars", "Car succesfully modified\n");
?>
