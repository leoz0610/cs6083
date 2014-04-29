<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>PinBoards</title>
</head>
<body>
	<?php
		session_start();
		if (empty($_SESSION["isLogged"])) {
			header("Location: home.php");
			exit;
		}
		include "./DB.php";	
		include "./feeds.php";

		echo "<h2>My Pinboards</h2>";

		if (!empty($_POST['bname'])) {
			$query = "select * from pinboard
			where uid='{$_SESSION['uid']}' and bname='{$_POST['bname']}'";
			$result = mysql_query($query, $connection) or 
				die("Failed to check board.");
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			if (!empty($row)) {
				$boardExist = "<div class='error'>{$row['bname']} exists</div>";
			} else {
				unset($boardExist);
				if (!empty($_POST['isPrivate']))
					$isPrivate = 1;
				else
					$isPrivate = 0;
				$query = "insert into pinboard(bname, uid, btime, isPrivate)
				value ('{$_POST['bname']}', '{$_SESSION['uid']}', now(),
				{$isPrivate})";
				$result = mysql_query($query, $connection) or 
					die("Failed to insert into pinboard.");
			}
		}

		$query = "select * from pinboard where uid='{$_SESSION['uid']}'";
		$result = mysql_query($query, $connection) or 
			die("Can't query table pinboard.");
		echo "<form method='post'>
		<label>Create a new board</label>
		<input type='text' name='bname'>
		<input type='checkbox' name='isPrivate' value='true'>
		Private?<br>
		<input type='submit' value='Create'>";
		if (isset($boardExist))
			echo $boardExist;
		echo "</form>
		<ul>";
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<li><a href='boardContent.php?bid={$row['bid']}&
				bname={$row['bname']}'>
			{$row['bname']}</a></li>";
		}
		echo "</ul>";
	?>

</form>
	<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>

</body>
</html>
