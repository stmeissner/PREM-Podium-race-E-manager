<?
require_once("session_start.php");

$user = strtolower(addslashes($_POST['user']));
$pass = addslashes($_POST['pass']);

$mysql_link = mysqlconnect();

$login = new mysql_login($mysql_link, "user");
switch($login->login($user, $pass)) {
	case 0:
		// success
		break;
	case 0x20: // mysql_login::ERR_USER_LOGIN_INCORRECT:
		error("Username or password incorrect\n");
		break;
	case 0x21: // mysql_login::ERR_USER_INACTIVE:
		error("Your account is disabled\n");
		break;
	case 0x10: // mysql_login::ERR_SQL_CONNECTION:
	case 0x11: // mysql_login::ERR_SQL_QUERY:
		error("MySQL error: " . $login->last_error() . "\n");
		break;
	default:
		error("An unknown error occurred: " . $login->last_error() . "\n");
		break;
}

$_SESSION['login'] = $login;

header("Location: .");
?>
