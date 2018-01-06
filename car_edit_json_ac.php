<?PHP if(!defined("CONFIG")) exit(); ?>
<?PHP if(!isset($login)) { show_error("You do not have administrator rights\n"); return; } ?>
<?
if(isset($_POST['json_edit']))
		{$upload = false;
		switch($_FILES['userfile']['error']) {case UPLOAD_ERR_OK:$upload = true;
		break;
	}
	# Upload the JSON file
		$file = file_get_contents($_FILES['userfile']['tmp_name']);
  }
?>
<h1>Edit Assetto Corsa ui_car.json</h1>
<p>Be sure that the .json contains no /br, no spaces and no '! - Otherwise the file cannot get parsed!</p>

<?PHP if(!$upload) { ?>
<br/>
<form action=".?page=car_edit_json_ac" method="post" enctype="multipart/form-data">
<table border="0" cellspacing="0" cellpadding="2">
<tr>
	<td>JSON file:</td><td><input type="file" name="userfile"/></td>
</tr>
<tr>
	<td>&nbsp;</td><td><input type="submit" class="button submit" value="Upload"/>
										 <input type="hidden" name="json_edit" value="1"/></td>
</tr></table></form>
<?PHP } else { ?>
<form action=".?page=car_ui_json_import_ac" method="post">
  		<table class="w3-table-all" border="0" cellspacing="0" cellpadding="1" width="100%">
        <tr>
        	<td><textarea cols= "120" rows= "60" maxlength="10240" name="json_file"><?echo "$file";?></textarea></td>
        </tr>
			<tr>
				<td><input type="submit" class="button submit" value="Proceed"/>
					  <input type="hidden" name="json" value="json_file"/>
					  <input type="button" class="button cancel" value="Cancel" onclick="history.go(-1);"/>
				</td>
			</tr>
		</table>
</form>
<?PHP } ?>
