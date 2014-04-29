<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Follow Boards</title>
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

		echo "<form method='post'>";
		echo "<label>Boards' names</label>
		<input type='search' name='search'>";
		echo "<input type='submit' value='Search'>";
		echo "</form>";

		if (!empty($_POST['search'])) {
			$sname = mysql_real_escape_string($_POST['search']);
			$query = "select * from pinboard where bname like '%{$sname}%'
			and bid not in (select B.bid from pinboard B, follow_board FB where
			B.bid=FB.bid and FB.fid={$_GET['fid']})";

			$result = mysql_query($query, $connection) or 
				die("Failed.");
			echo "<form method='post'><table>";
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				echo "<tr>
				<input type='checkbox' name='selected[]' value={$row['bid']}>
				{$row['bname']}, {$row['uid']}";
				echo "</tr>";
			}
			echo "<input type='submit' value='Follow'>";
			echo "</table></form>";

			$query = "insert into history(uid, keyword, htime)
			value ('{$_SESSION['uid']}', '{$_POST['search']}', now())";
			$result = mysql_query($query, $connection) or 
				die("Failed search.");
		}

		if (!empty($_POST['selected'])) {
			foreach ($_POST['selected'] as $id) {
				$query = "insert into follow_board(fid, bid, fbtime)
				value ({$_GET['fid']}, {$id}, now())";
				$result = mysql_query($query, $connection) or 
					die("Insertion failed.");
			}
		}
		

	?>
	<br>
	<form action='home.php'>
		<input type="submit" value="Back to home page">
	</form>
</body>
</html>
