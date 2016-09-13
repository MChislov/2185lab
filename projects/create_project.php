<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('../Connections/db.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "create_project")) {
  $insertSQL = sprintf("INSERT INTO m_projects (ID_user_last_status_change, T_p_index, T_Name, T_Description, ID_author, ID_status, ID_user_modified, Date_created) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID_user_last_status_change'], "int"),
                       GetSQLValueString($_POST['T_p_index'], "text"),
                       GetSQLValueString($_POST['T_Name'], "text"),
                       GetSQLValueString($_POST['T_Description'], "text"),
                       GetSQLValueString($_POST['ID_author'], "int"),
                       GetSQLValueString($_POST['ID_status'], "int"),
                       GetSQLValueString($_POST['ID_user_modified'], "int"),
                       GetSQLValueString($_POST['Date_created'], "date"));

  mysql_select_db($database_db, $db);
  $Result1 = mysql_query($insertSQL, $db) or die(mysql_error());

  $insertGoTo = "../main/main_form.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Project management</title>
</head>

<body>
<?php echo $_SESSION['User_Name']; ?>
<?php if (isset($_SESSION['User_ID'])&&($_SESSION['rights']>=3)) {
?>

<form method="POST" action="<?php echo $editFormAction; ?>" name="create_project"><?php require_once('../Connections/db.php'); ?>

  
  <table>
  <tr style="display:none">
    <td>ID</td>
    <td><input type="hidden" name="ID" value="" />&nbsp;</td>
  </tr>
  <tr style="display:none">
    <td>ID_user_last_status_change&nbsp;</td>
    <td><input type="hidden" name="ID_user_last_status_change" value="<? echo $_SESSION['User_ID'];?>" />&nbsp;</td>
  </tr>
  <tr >
    <td>Index (max. 5)&nbsp;</td>
    <td><input type="text" maxlength="5" name="T_p_index" />&nbsp;</td>
  </tr>
  <tr>
    <td>Name&nbsp;</td>
    <td><textarea name="T_Name" type="text" rows="2" cols="25" maxlength="50"> </textarea>&nbsp;</td>
  </tr>
  <tr>
    <td>Description&nbsp;</td>
    <td><textarea name="T_Description" rows="10" cols="25" maxlength="50"> </textarea>
    &nbsp;</td>
  </tr>
  <tr style="display:none">
    <td width="50">ID_author&nbsp;</td>
    <td><input type="text" maxlength="50" name="ID_author" value="<? echo $_SESSION['User_ID'];?>" />&nbsp;</td>
  </tr>
   <tr style="display:none">
    <td>ID_status&nbsp;</td>
    <td><input type="text" maxlength="5" name="ID_status" value="1" />&nbsp;</td>
  </tr>
  <tr style="display:none">
    <td>Date_modified&nbsp;</td>
    <td><input type="text" maxlength="50" name="Date_modified" />&nbsp;</td>
  </tr>
  <tr style="display:none">
    <td>ID_user_modified&nbsp;</td>
    <td><input type="text" maxlength="50" name="ID_user_modified" value="<? echo $_SESSION['User_ID'];?>" />&nbsp;</td>
  </tr>
  <tr style="display:none">
    <td>Date_created&nbsp;</td>
    <td><input type="text" maxlength="50" name="Date_created" value="<?php echo date ("Y-m-d H:i:s"); ?>"/>&nbsp;</td>
  </tr>
  <tr style="display:none">
    <td>Date_last_status_change&nbsp;</td>
    <td><input type="text" maxlength="50" name="Date_created" value="<?php echo date ("Y-m-d H:i:s"); ?>" />&nbsp;</td>
  </tr>  
</table>
  <input type="button" value="Cancel" onclick="location.href = '../index.php'" />
  <input type="submit" value="Create"  />
  <input type="hidden" name="MM_insert" value="create_project" />
</form>
<?php }; ?>
<?php echo date ("Y-m-d H:i:s"); ?>

    
    
</body>
</html>