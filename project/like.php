<?php
	session_start();
	include "./DB.php";
	$query = "insert into likes(uid, pid, ltime)
	value ('{$_SESSION['uid']}', {$_GET['pid']}, now())";
	$result = mysql_query($query, $connection) or die("Insertion like");
	header("Location: pictureContent.php?pid={$_GET['pid']}
	&bid={$_GET['bid']}");
?>
