<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>FollowStream</title>
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

		echo "<h2>My FollowStream</h2>";

		if (!empty($_POST['fname'])) {
			$query = "select * from followstream
			where uid='{$_SESSION['uid']}' and fname='{$_POST['fname']}'";
			$result = mysql_query($query, $connection) or 
				die("Failed to check board.");
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			if (!empty($row)) {
				$followExist = "<div class='error'>{$row['fname']} exists</div>";
			} else {
				unset($followExist);

				$query = "insert into followstream(fname, uid, ftime)
				value ('{$_POST['fname']}', '{$_SESSION['uid']}', now())";
				$result = mysql_query($query, $connection) or 
					die("Failed to insert into pinboard.");
			}
		}

		$query = "select * from followstream where uid='{$_SESSION['uid']}'";
		$result = mysql_query($query, $connection) or 
			die("Can't query table pinboard.");
		echo "<form method='post'>
		<label>Create a new followstream</label>
		<input type='text' name='fname'>
		<input type='submit' value='Create'>";
		if (isset($boardExist))
			echo $boardExist;
		echo "</form>
		<ul>";
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<li><a href='followContent.php?fid={$row['fid']}&
				fname={$row['fname']}'>
			{$row['fname']}</a></li>";
		}
		echo "</ul>";
	?>

</form>
	<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>

</body>
</html>

