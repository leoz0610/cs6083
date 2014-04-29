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

			if (!empty($_POST['selected']) && $_POST['decision'] == 'Accept')  {
				foreach ($_POST['selected'] as $id) {
					$query = "update friendship set requestTime = requestTime,
					acceptedTime = now() where requestId = '{$id}' and
					acceptedId = '{$_SESSION['uid']}'";
					$result = mysql_query($query, $connection) or 
						die("Updated failed.");
				}
			} else if (!empty($_POST['selected']) && $_POST['decision'] == 'Reject') {
				foreach ($_POST['selected'] as $id) {
					$query = "delete from friendship where requestId = '{$id}' and
					acceptedId = '{$_SESSION['uid']}'";
					$result = mysql_query($query, $connection) or 
						die("Delete failed.");
				}
			}

			include "./feeds.php";

			$query = "select * from friendship where acceptedId = '{$_SESSION['uid']}'
				and acceptedTime = 0";
			$result = mysql_query($query, $connection) or 
				die("Failed.");

			echo "<form action='friendResponse.php' method='post'>";
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				echo "<input type='checkbox' name='selected[]' value='{$row['requestId']}'>
				{$row['requestId']}<br>";
			}
			echo "<input type='submit' name='decision' value='Accept'>
			<input type='submit' name='decision' value='Reject'>
				</form>";

		}
	?>
	<br>
	<form action='home.php'>
		<input type="submit" value="Back to home page">
	</form>
</body>
</html>	
