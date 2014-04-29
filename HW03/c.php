<html>
<head>
<title>Part c</title>
</head>
<body>
	<?php
		$attr = $_GET['attr'];

		$connection = mysql_connect("localhost", "root", "")
			or die("Not connected: ". mysql_error());

		$db_selected = mysql_select_db('hw03', $connection) or 
			die("Can\'t use hw03 : ". mysql_error());

		$result = mysql_query("show tables", $connection) 
			or die('Query failed: '. mysql_error());	

		echo "<table><tr>";
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			if (mysql_query("select $attr from $row[0]")
			       	!== false) {
				echo "<td><a href='b.php?tn=$row[0]'>".
					$row[0]."</a></td>";
			}
		}
		echo "</tr></table>";
	?>

	<form action='a.php' method='get'>
	<input type='submit' value='Part A'>
	</form>
</body>
</html>
