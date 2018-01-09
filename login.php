<?PHP if(!defined("CONFIG")) exit(); ?>
<?
if(!defined("USE_MYSQL") || !defined("USE_LOGIN")) {
	show_error("Login is disabled\n");
	return;
}
?>
<div id="login">
<h1><?php echo TITLE?></h1>
<?php echo SUBTITLE?><br>
<a href="<?php echo $config['org_link']?>"><?php echo $config['org']?></a><br>
<br>
<?PHP mysql_login::print_login_form() ?>
<br>
<div class="small">Version <?php echo VERSION?><br></div><br>
</div>
