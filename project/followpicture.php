<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Follow Pictures</title>
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
		echo "<label>Picture's names or tags</label>
		<input type='search' name='search'>";
		echo "<input type='submit' value='Search'>";
		echo "</form>";

		if (!empty($_POST['search'])) {
			$sname = mysql_real_escape_string($_POST['search']);
			$query = "select * from picture where pname like '%{$sname}%'
			and pid not in (select P.pid from picture P, follow_picture FP where
			P.pid=FP.pid and FP.fid={$_GET['fid']})
			union select P.* from picture P, picture_tag PT, tag T
			where P.pid=PT.pid and PT.tid=T.tid and T.tname like '%{$sname}%'
			and P.pid not in (select P.pid from picture P, follow_picture FP where
			P.pid=FP.pid and FP.fid={$_GET['fid']})";

			$result = mysql_query($query, $connection) or 
				die("Failed.");
			echo "<form method='post'><table>";
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				echo "<tr>
				<input type='checkbox' name='selected[]' value={$row['pid']}>
				<img src='{$row['url_local']}' alt='Pictures' height='200' width='200'>
				<br><label>{$row['pname']}</label>";
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
				$query = "insert into follow_picture(fid, pid, fptime)
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
