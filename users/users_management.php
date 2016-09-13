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
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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
<?php if (!isset($_SESSION)) {
  session_start();
} ?>
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

mysql_select_db($database_db, $db);
$query_All_users = "SELECT * FROM m_users ORDER BY Added ASC";
$All_users = mysql_query($query_All_users, $db) or die(mysql_error());
$row_All_users = mysql_fetch_assoc($All_users);
$totalRows_All_users = mysql_num_rows($All_users);

$colname_userByID = "-1";
if (isset($_GET['User_ID'])) {
  $colname_userByID = $_GET['User_ID'];
}
mysql_select_db($database_db, $db);
$query_userByID = sprintf("SELECT * FROM m_users WHERE User_ID = %s", GetSQLValueString($colname_userByID, "int"));
$userByID = mysql_query($query_userByID, $db) or die(mysql_error());
$row_userByID = mysql_fetch_assoc($userByID);
$totalRows_userByID = mysql_num_rows($userByID);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chem-2185: Users preferences! <?php echo ($_SESSION['MM_Username']); ?></title>
</head>

<body>


<?php if (isset($_GET['User_ID'])) { 
		if (($_GET['User_ID']!=$_SESSION['User_ID']) and ($_SESSION["rights"]=="low")) { ?>
You don't have permission. Please contact administrator or project manager for higher access.
		<?php } else { echo ("Hello, ".$_SESSION["User_Name"]);?>
<table width="30%" border="1" cellspacing="1" cellpadding="1">
  <caption>
    User info
  </caption>
  <tr>
    <td>ID</td>
    <td><?php echo $row_userByID['User_ID']; ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Name</td>
    <td><?php echo $row_userByID['User_Name']; ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Surname</td>
    <td><?php echo $row_userByID['User_Surname']; ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>login</td>
    <td><?php echo $row_userByID['login']; ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>password</td>
    <td><input type="password" readonly="readonly" value="<?php echo $row_userByID['password']; ?>" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Birthday</td>
    <td><?php echo $row_userByID['BirthDate']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>email</td>
    <td><?php echo $row_userByID['email']; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Added</td>
    <td><?php echo $row_userByID['Added']; ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Last modified</td>
    <td><?php echo $row_userByID['ModDate']; ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Rights</td>
    <td><?php echo $row_userByID['rights']; ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Access</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>No of Experiments</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>No of projects</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

        
        

	<a href="edit_user.php?User_ID=<?php echo $_GET['User_ID']; ?>">Edit</a> 
<?php if ($_SESSION["rights"]=='adm') {?>
    <a href="edit_user.php">Create new</a>
<?php }	else {	echo ($_SESSION["rights"]);	}}
?>

	
<?php }  else {if ($_SESSION['rights']!=low){?>

<table width="95%" border="1" cellspacing="1" cellpadding="1">
  <caption>
    Users:
  </caption>
  <tr>
    <td>ID</td>
    <td>Name</td>
    <td>Login</td>
    <td>Email</td>
    <td>Birthday</td>
    <td>Added</td>
    <td>Rights</td>
    <td>Edit</td>
    
  </tr>
  <?php do { ?>
  <tr style="cursor:pointer" onclick="location.href='/users_management.php?User_ID=<?php echo $row_All_users['User_ID']; ?>'">
    <td>&nbsp;<?php echo $row_All_users['User_ID']; ?></td>
    <td>&nbsp;<?php echo $row_All_users['User_Name']; ?> <?php echo $row_All_users['User_Surname']; ?></td>
    <td>&nbsp;<?php echo $row_All_users['login']; ?></td>
    <td>&nbsp;<?php echo $row_All_users['email']; ?></td>
    <td>&nbsp;<?php echo $row_All_users['BirthDate']; ?></td>
    <td>&nbsp;<?php echo $row_All_users['Added']; ?></td>
    <td>&nbsp;<?php echo $row_All_users['rights']; ?></td>
    <td>&nbsp;<a href="edit_user.php?User_ID=<?php echo $row_All_users['User_ID']; ?>">Edit</a></td>
    
  </tr>
  <?php } while ($row_All_users = mysql_fetch_assoc($All_users)); 
?>
</table>



<?php } else { ?>
You don't have access.
<?php }; }; ?>
</body>
</html>
<?php
mysql_free_result($All_users);

mysql_free_result($userByID);
?>
