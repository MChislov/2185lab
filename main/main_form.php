<?php require_once('../Connections/db.php'); ?>
<?php 

if (!isset($_SESSION)) {
  session_start();
}

include'../aux_m/aux_login.php';
include'../aux_m/aux_logout.php'; 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="windows-1251" />
<title>2185 notebook</title>
</head>

<body>

<div class="header">

<a href="<?php echo $logoutAction ?>">Logout</a>

</div>
Здесь все проекты:
<input type="button"  value="Show All Projects" onclick="Show_project('all', 'small')"/>
<input type="button"  value="Show Project 1" onclick="Show_project('1', 'full')"/>
<div id="projects">

</div>


</body>
</html>
<script>
function Show_project(param, view) {
 {
        var xmlhttp = new XMLHttpRequest();
		var address;
		var inner_link;

        if (param=='all') 
			{	address="ID=all&View="+view;
						
				inner_link="projects";
			} else {
				address="ID="+param+"&View="+view;
				inner_link="project_"+param;
			};
			
			xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById(inner_link).innerHTML = xmlhttp.responseText;
            }
        };
		xmlhttp.open("GET", "SH/SH_project.php?"+address, true);
		
        xmlhttp.send();
    }
}
</script>