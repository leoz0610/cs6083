<?php
	session_start();
	if (empty($_SESSION['isLogged']) || empty($_GET['reqId'])) {
		header("Location: home.php");
		exit;
	} else {
		include "./DB.php";
		$query = "insert into friendship(requestId, acceptedId, requestTime,
		acceptedTime) value ('{$_SESSION['uid']}', '{$_GET['reqId']}', now(),
		0)";
		$result = mysql_query($query, $connection) or 
			die("Failed.");
		header("Location: friendsadd.php");
	}
?>
