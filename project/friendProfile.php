<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title><?php echo "{$_GET['uid']}"; ?></title>
</head>
<body>
	<?php
		session_start();

		include "./DB.php";

		$query = "select * from friendship
			where (requestId = '{$_SESSION['uid']}' 
			and acceptedId = '{$_GET['uid']}' or
			requestId = '{$_GET['uid']}' and
			acceptedId = '{$_SESSION['uid']}')
			and acceptedTime != 0";

		$result = mysql_query($query, $connection) or die("Failed");

		$row = mysql_fetch_array($result);

		if (empty($_SESSION["isLogged"]) || empty($row)) {
			header("Location: home.php");
			exit;
		} else {
			include "./feeds.php";

			$query = "select * from profile where uid = '{$_GET['uid']}'";
			$result = @ mysql_query($query, $connection) or 
			die("Can't connect table profile.");

			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			if (empty($row)) { // User with no profile.
				echo "{$row['name']} doesn't have a profile.";
			} else {
				echo "<h2>{$row['uid']}'s profile</h2><table>";

				echo "<tr><td class='post'>";
				echo "<div class='post-heading'>Name: </div>
					<div class='post-content'>{$row['name']}</div>";
				echo "<br></td></tr>";
				
				echo "<tr><td class='post'>";
				echo "<div class='post-heading'>Last update: </div>
					<div class='post-time'>{$row['ptime']}</div>";
				echo "<br></td></tr>";

				if (!empty($row["gender"])) {
					echo "<tr><td class='post'>";
					echo "<div class='post-heading'>Gender: </div>
						<div class='post-content'>{$row['gender']}</div>";
					echo "<br></td></tr>";
				}

				if (!empty($row["age"])) {
					echo "<tr><td class='post'>";
					echo "<div class='post-heading'>Age: </div>
						<div class='post-content'>{$row['age']}</div>";
					echo "<br></td></tr>";
				}

				if (!empty($row["email"])) {
					echo "<tr><td class='post'>";
					echo "<div class='post-heading'>Email: </div>
						<div class='post-content'>{$row['email']}</div>";
					echo "<br></td></tr>";
				}

				if (!empty($row["selfDescription"])) {
					echo "<tr><td class='post'>";
					echo "<div class='post-heading'>Self Description: </div>
					<pre><div class='post-content'>{$row['selfDescription']}</div></pre>";
					echo "<br></td></tr>";
				}

				echo "</table>";
			}

		}
	?>
	<br>
	<a href="friendsadd">Add new friends</a>
	<br>
	<form action='home.php'>
		<input type="submit" value="Back to home page">
	</form>
</body>
</html
