<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Friends</title>
</head>
<body>
	<?php
		session_start();
		if (empty($_SESSION["isLogged"])) {
			header("Location: home.php");
			exit;
		} else {

			include "./DB.php";
			
			include "./feeds.php";

			echo "<h2>Friends: </h2>";

			$query = "select acceptedId as uid from friendship
				where requestId = '{$_SESSION['uid']}' and acceptedTime != 0
				union select requestId as uid from friendship
				where acceptedId = '{$_SESSION['uid']}' and acceptedTime != 0";

			$result = @ mysql_query($query, $connection) or
				die("Can't query table friendship.");

			echo "<table class='post'>";
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				echo "<tr><td><a href='friendProfile.php?uid={$row['uid']}'>
				{$row['uid']}</a></td>";
				echo "<td><label>Pinboards:</label></td>";
				$query = "select bid, bname from pinboard where uid='{$row['uid']}'";
				$fresult = mysql_query($query, $connection) or die('friends');
				while ($frow = mysql_fetch_array($fresult, MYSQL_ASSOC)) {
					echo "<td><a href='boardContent.php?bid={$frow['bid']}&bname={$frow['bname']}'>
					{$frow['bname']}
					</a></td>";
				}
				echo "</a></tr>";
			}
			echo "</table>";
		}
	?>
	<br>
	<a href="friendsadd.php">Add new friends</a>
	<br>
	<form action='home.php'>
		<input type="submit" value="Back to home page">
	</form>
</body>
</html>
