<?php require_once('../Connections/db.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
include '../aux_m/aux_header.php';
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['rights']);
  unset($_SESSION['User_ID']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "user")) {
  $updateSQL = sprintf("UPDATE m_users SET User_Name=%s, User_Surname=%s, login=%s, password=%s, email=%s, BirthDate=%s, Added=%s, rights=%s, `role`=%s, ModDate=%s WHERE User_ID=%s",
                       GetSQLValueString($_POST['User_Name'], "text"),
                       GetSQLValueString($_POST['User_Surname'], "text"),
                       GetSQLValueString($_POST['login'], "text"),
                       GetSQLValueString($_POST['password_1'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['BirthDate'], "date"),
                       GetSQLValueString($_POST['Added'], "date"),
                       GetSQLValueString($_POST['rights'], "text"),
                       GetSQLValueString($_POST['role'], "text"),
                       GetSQLValueString(date("Y-m-d H:i:s"), "date"),
                       GetSQLValueString($_POST['User_ID'], "int"));

  mysql_select_db($database_db, $db);
  $Result1 = mysql_query($updateSQL, $db) or die(mysql_error());

  $updateGoTo = "users_management.php?User_ID=" . $_POST['User_ID'] ;
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_userByID = "-1";
if (isset($_GET['User_ID'])) {
  $colname_userByID = $_GET['User_ID'];
}
mysql_select_db($database_db, $db);
$query_userByID = sprintf("SELECT * FROM m_users WHERE User_ID = %s", GetSQLValueString($colname_userByID, "int"));
$userByID = mysql_query($query_userByID, $db) or die(mysql_error());
$row_userByID = mysql_fetch_assoc($userByID);
$totalRows_userByID = mysql_num_rows($userByID);

mysql_select_db($database_db, $db);
$query_Permissions_all = "SELECT ID, T_name FROM a_permissions ORDER BY N_index ASC";
$Permissions_all = mysql_query($query_Permissions_all, $db) or die(mysql_error());
$row_Permissions_all = mysql_fetch_assoc($Permissions_all);
$totalRows_Permissions_all = mysql_num_rows($Permissions_all);
 if (!isset($_SESSION)) {
  session_start();
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chem-2185: user</title>
</head>

<body>
<?php if (isset($_GET['User_ID'])&& ($_SESSION['User_ID'])) { 
echo $_SESSION['rights'];
echo $_SESSION['User_ID'];
?>

<form method="POST" action="<?php echo $editFormAction; ?>" name="user">
ID: <br  />
	<input type="text" readonly="readonly" width="20" name="User_ID" value="<?php echo $row_userByID['User_ID']; ?>"/><br />
Name: <br  />
	<input type="text" width="20" maxlength="15" name="User_Name" value="<?php echo $row_userByID['User_Name']; ?>"/><br />
Surname: <br />
    <input type="text" width="20" maxlength="20" name="User_Surname" value="<?php echo $row_userByID['User_Surname']; ?>" /><br  />
Birthday: <br  />
    <input type="date" width="20" name="BirthDate" value="<?php echo $row_userByID['BirthDate']; ?>" /><br  />
Email: <br  />
    <input type="text" width="20" maxlength="30" name="email" value="<?php echo $row_userByID['email']; ?>" /><br  />
Login: <br />
    <input type="text" width="20" maxlength="20" name="login" value="<?php echo $row_userByID['login']; ?>" /><br />
Password: <br  />
    <input type="password" width="20" name="password_1" maxlength="20" value="<?php echo $row_userByID['password']; ?>" /><br />
    <input type="password" width="20" name="password_2" maxlength="20" value="" /><br  />
Added: <br  />
    <input type="datetime" name="Added" value="<?php echo $row_userByID['Added']; ?>" /><br />
Last modified: <br  />
    <input type="datetime" name="ModDate" value="<?php echo $row_userByID['ModDate']; ?>" /><br  />
Rights: <br  />
         <select name="rights">
   
    <?php do { if ($_SESSION['rights']>=$row_Permissions_all['ID']) {?>

        
        <option value="<?php echo $row_Permissions_all['ID']; ?>" <?php if($row_userByID['rights']==$row_Permissions_all['ID']) {?> selected="selected"<?php }; ?> > <? echo $row_Permissions_all['T_name']; ?></option>

     
      <?php }} while ($row_Permissions_all = mysql_fetch_assoc($Permissions_all)); ?>
       </select>
<br  />
Comment: <br  /> 
    <textarea rows="5" cols="20" name="role" > <?php echo $row_userByID['role']; ?></textarea><br  />
    <input type="submit" value="Save"  />
    <input type="button" value="Cancel" onclick="location.href = '../index.php'"  />

<input type="hidden" name="MM_update" value="user" />
</form>
<?php } else if (!isset($_GET['User_ID']) && ($_SESSION['rights']>=4)) { ?>    
    <form method="POST"  name="new_user">
ID: <br  />
	<input type="text" readonly="readonly" width="20" name="User_ID" value=""/><br />
Name: <br  />
	<input type="text" width="20" maxlength="15" name="User_Name" value=""/><br />
Surname: <br />
    <input type="text" width="20" maxlength="20" name="User_Surname" value="" /><br  />
Birthday: <br  />
    <input type="date" width="20" name="BirthDate" value="" /><br  />
Email: <br  />
    <input type="text" width="20" maxlength="30" name="email" value="" /><br  />
Login: <br />
    <input type="text" width="20" maxlength="20" name="login" value="" /><br />
Password: <br  />
    <input type="password" width="20" name="password_1" maxlength="20" value="" /><br />
    <input type="password" width="20" name="password_2" maxlength="20" value="" /><br  />
Added: <br  />
    <input type="datetime" name="Added" value="" /><br />
Last modified: <br  />
    <input type="datetime" name="ModDate" value="" /><br  />
Rights: <br  />
          <select name="rights">
	<?php do { if ($_SESSION['rights']>=$row_Permissions_all['ID']) {?>

        
        <option value="<?php echo $row_Permissions_all['ID']; ?>"><?php echo $row_Permissions_all['T_name']; ?></option>
        
      
      <?php }} while ($row_Permissions_all = mysql_fetch_assoc($Permissions_all)); ?>
      </select>
<br  />
Comment: <br  /> 
    <textarea rows="5" cols="20" name="role" > </textarea><br  />
    <input type="submit" value="Create"  />
    <input type="button" value="Cancel" onclick="location.href = '../index.php'"  />


</form>    
<?php } else { echo ("You dont have permissions for this operation"); } ?>
</body>
</html>
<?php
mysql_free_result($userByID);

mysql_free_result($Permissions_all);
?>
