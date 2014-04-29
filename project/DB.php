<?php
	$connection = @ mysql_connect("localhost", "root", "") or 
	die("Not connected: ". mysql_error());
	$db_selected = @ mysql_select_db('project01', $connection) or 
		die("Can't use project01 : ". mysql_error());
?>
