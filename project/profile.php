<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>My Profile</title>
</head>
<body>
	<?php
		session_start();
		if (empty($_SESSION["isLogged"])) {
			header("Location: home.php");
			exit;
		} else {

			echo "<h2>My Profile</h2>";
			
			include "./DB.php";

			include "./feeds.php";

			$query = "select * from profile where uid = '{$_SESSION['uid']}'";
			$result = @ mysql_query($query, $connection) or 
			die("Can't connect table profile.");

			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			if (empty($row)) { // User with no profile.
				echo "<div class='description'>You still have no profile.
					You may have one by clicking 
					<a href='profileEdit.php'>here</a>.</div>";
			} else {
				echo "<table>";

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

				echo "<br><a href='profileEdit.php'>edit</a>";
			}
		}
	?>

	<br>
	</form>
		<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>

</body>
</html>
