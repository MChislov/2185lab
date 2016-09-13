<?php require_once('Connections/db.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
include 'aux_m/aux_header.php';

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
	
  $logoutGoTo = "index.php";
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


?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_db, $db);
  
  $LoginRS__query=sprintf("SELECT login, password FROM m_users WHERE login=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $db) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

$colname_LoggedUser = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_LoggedUser = $_SESSION['MM_Username'];

mysql_select_db($database_db, $db);
$query_LoggedUser = "SELECT * FROM m_users WHERE login = '". $_SESSION['MM_Username']."'";
$LoggedUser = mysql_query($query_LoggedUser, $db) or die(mysql_error());
$row_LoggedUser = mysql_fetch_assoc($LoggedUser);
$totalRows_LoggedUser = mysql_num_rows($LoggedUser);
$_SESSION['User_ID']=$row_LoggedUser['User_ID'];
$_SESSION['User_Name']=$row_LoggedUser['User_Name'];
$_SESSION['rights']=$row_LoggedUser['rights'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>2185Lab</title>
</head>
<body>
<h1>2185 Lab </h1>
<div class="header">
<?php if (isset($_SESSION['MM_Username'])) { ?>

<?php }; ?>
</div>

<?php if  (!$_SESSION['MM_Username']) { ?>
<form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST" name="login_form">
	<input name="username" width="20" maxlength="20" value="" /> <br />
    <input type="password" name="password" width="20" maxlength="20" value="" /> <br />
    <button type="submit" >Login</button>
    <button onclick="">Cancel</button>
</form>

<?php 
} 
else 
{ ?>


<button onclick="location.href= '/users/users_management.php?User_ID=<?php echo $_SESSION['User_ID']; ?>'">Profile</button>
<?php if ($_SESSION['rights']>=2) { ?>
<button onclick="location.href= '/users/users_management.php'">Users manager</button>
<button onclick="location.href= '/main/main_form.php'">Main Page</button>
<?php }; ?>
<?php 
}; ?>
</body>
</html>
<?php
if ($LoggedUser) {
mysql_free_result($LoggedUser);
}
?>
