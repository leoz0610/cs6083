<html>
<head>
<title>Part b</title>
</head>
<body>
	<?php
		$table = $_GET['tn'];

		echo "<h3>The attributes that Table \"$table\" has: </h3>";
			
		$connection = mysql_connect("localhost", "root", "")
			or die("Not connected: ". mysql_error());
		$db_selected = mysql_select_db('hw03', $connection) or 
			die("Can\'t use hw03 : ". mysql_error());

		$result = mysql_query("select * from $table") or
			die('Query failed: '. mysql_error());

		$num = mysql_num_fields($result);

		echo "<table>";
		for ($i = 0; $i < $num; $i++) {
			$name = mysql_field_name($result, $i);
			echo "<tr><td><a href='c.php?attr=$name'>".
				$name.
				"</a></td></tr>";
		}
		echo "</table>";
	?>
</body>
</html>
