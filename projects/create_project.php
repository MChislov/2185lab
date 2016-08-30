<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Project management</title>
</head>

<body>
<?php if (isset($_SESSION['User_ID'])&&($_SESSION['rights']>=3)) {
?>

<form name="create_project">
	<input type="hidden" name="ID_user" value="<? echo $_SESSION['User_ID'];?>" />
  <table>
  <tr>
    <td>Index (max. 5)&nbsp;</td>
    <td><input type="text" maxlength="5" name="T_p_index" />&nbsp;</td>
  </tr>
  <tr>
    <td>Name&nbsp;</td>
    <td><input type="text" maxlength="50" name="T_Name" />&nbsp;</td>
  </tr>
  <tr>
    <td>Description&nbsp;</td>
    <td><input type="text" maxlength="50" name="T_Description" />&nbsp;</td>
  </tr>
</table>
  <input type="button" value="Cancel" onclick="location.href = '../index.php'" />
  <input type="submit" value="Create"  />
</form>
<?php }; ?>

    
    
</body>
</html>