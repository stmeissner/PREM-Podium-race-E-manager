<?PHP
require_once("session_start.php");
if(!isset($login)) error("You do not have administrator rights\n");
require_once("functions.php"); // import mysql function
$link = mysqlconnect(); // call mysql function to get the link to the database

$id = addslashes($_POST['id']);
$sim = mysqli_real_escape_string($link,$_POST['sim']);
$brand = mysqli_real_escape_string($link,$_POST['brand']);
$name = mysqli_real_escape_string($link,$_POST['name']);
$code = mysqli_real_escape_string($link,$_POST['code']);
$badge = mysqli_real_escape_string($link,$_POST['badge']);
$horsepower = mysqli_real_escape_string($link,$_POST['horsepower']);
$torque = mysqli_real_escape_string($link,$_POST['torque']);
$weight = mysqli_real_escape_string($link,$_POST['weight']);
$description = mysqli_real_escape_string($link,$_POST['description']);

$query = "UPDATE cars SET sim='$sim', brand='$brand', name='$name', code='$code', badge='$badge',
                          horsepower='$horsepower', torque='$torque', weight='$weight', description='$description' WHERE id='$id'";
$result = mysqli_query($link,$query);
if(!$result) error("MySQL Error: " . mysqli_error($link) . "\n");

return_do(".?page=cars", "Car succesfully modified\n");
?>
