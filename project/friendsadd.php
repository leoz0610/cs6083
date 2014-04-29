<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Adding new friends</title>
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

			echo "<form method='post'>";
			echo "<label>User Id</label>
			<input type='search' name='search'>";
			echo "<input type='submit' value='Search'>";
			echo "</form>";

			if (!empty($_POST['search'])) {
				$sname = mysql_real_escape_string($_POST['search']);
				$query = "select uid from user where uid like '%{$sname}%'
				and uid not in (select acceptedId as uid from friendship
				where requestId = '{$_SESSION['uid']}' and acceptedTime != 0
				union select requestId as uid from friendship
				where acceptedId = '{$_SESSION['uid']}' and acceptedTime != 0)
				and uid != '{$_SESSION['uid']}'";

				$result = mysql_query($query, $connection) or 
					die("Failed.");
				echo "<ul>";
				while ($user_row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$query = "select * from friendship 
						where requestId = '{$user_row['uid']}' and
						acceptedId = '{$_SESSION['uid']}' or 
						requestId = '{$_SESSION['uid']}' and acceptedId =
						'{$user_row['uid']}'";
					$appRes = mysql_query($query, $connection) or 
						die("appRes Failed.");
					$row = mysql_fetch_array($appRes);
					echo "<li>{$user_row['uid']} ";
					if (!empty($row)) {
						echo "Waiting for response.";
					} else {
						echo "<a href='friendRequest.php?reqId={$user_row['uid']}'>
						add</a>";
					}
					echo "</li>";
				}
				echo "</ul>";
			}
		}
	?>
	<br>
	<form action='home.php'>
		<input type="submit" value="Back to home page">
	</form>
</body>
</html>		
