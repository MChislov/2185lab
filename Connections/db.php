<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_db = "localhost";
$database_db = "2185db";
$username_db = "test";
$password_db = "test";
$db = mysql_connect($hostname_db, $username_db, $password_db) or trigger_error(mysql_error(),E_USER_ERROR); 
?>