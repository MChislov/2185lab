<?php require_once('../../Connections/db.php'); ?>
<?PHP header('Content-Type: text/html; charset=windows-1251'); ?>
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
$query_All_projects = "SELECT * FROM m_projects ORDER BY Date_modified DESC";
$All_projects = mysql_query($query_All_projects, $db) or die(mysql_error());
$row_All_projects = mysql_fetch_assoc($All_projects);
$totalRows_All_projects = mysql_num_rows($All_projects);



$colname_Project_by_ID = "-1";
if (isset($_GET['ID'])) {
  $colname_Project_by_ID = $_GET['ID'];
}
mysql_select_db($database_db, $db);
$query_Project_by_ID = sprintf("SELECT * FROM m_projects WHERE ID = %s", GetSQLValueString($colname_Project_by_ID, "int"));
$Project_by_ID = mysql_query($query_Project_by_ID, $db) or die(mysql_error());
$row_Project_by_ID = mysql_fetch_assoc($Project_by_ID);
$totalRows_Project_by_ID = mysql_num_rows($Project_by_ID);


?>
<?php if ($_GET['ID']=='all') { ?>
<meta http-equiv="Content-Type" content="text/html" charset="windows-1251" />



<body charset="koi8-r">
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <th>
    <td width="10%">ID_project&nbsp;</td>
    <td width="30%">small_view of project &nbsp;</td>
    <td>Head&nbsp;</td>
    <td>Themes&nbsp;</td>
    <td>Files&nbsp;</td>
    <td>Actions&nbsp;</td>
  </th>



<?php do { ?>
  <tr id="project_<?php echo $row_All_projects['ID']; ?>" style=" cursor:pointer" onClick="Show_project('<?php echo $row_All_projects['ID']; ?>', 'full')">
        <td width="10%"><?php echo $row_All_projects['T_p_index']; ?>&nbsp;</td>
        <td charset="windows-1251" width="30%"><?php echo $row_All_projects['T_Name']; ?>&nbsp;</td>
        <td charset="koi8-r"><?php echo $row_All_projects['T_Description']; ?>&nbsp;</td>
        <div id="themes_<?php echo $row_All_projects['ID']; ?>" style="display:none">Themes&nbsp;</div>
        <div id="files_<?php echo $row_All_projects['ID']; ?>" style="display:none">Files&nbsp;</div>
        <div id="actions_<?php echo $row_All_projects['ID']; ?>" style="display:none">Actions&nbsp;</div>
      </tr>  

  <?php } while ($row_All_projects = mysql_fetch_assoc($All_projects)); ?>
  </table>

<?php } else if ($_GET['ID']&&($_GET['View']=='full')) { ?>
<?php echo $row_Project_by_ID['T_p_index']; ?> <?php echo $_GET['ID']; echo 'full'; ?>
<?php } else if ($_GET['ID']&&($_GET['View']=='small')){echo $_GET['ID']; echo 'small';}
else {echo "wrong view parameter"; }?>
</body>

<?php
mysql_free_result($All_projects);
mysql_free_result($Project_by_ID);
?>