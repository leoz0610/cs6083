<html>
<head>
<title>Part a</title>
</head>
<body>
	<?php
	echo "
	<form method='post'>
	Table name:<input type='text' name='tname' value=''>
	<input type='submit'>";
	echo "<table><tr>";
	if (isset($_POST['tname']) && $_POST['tname'] != '') {
		$input = strtolower($_POST['tname']);
	} else {
		$input = ' ';
	}

	$connection = mysql_connect("localhost", 'root', '');
	if (!$connection) {
		die('Not connected: '. mysql_error());
	}

	$db_selected = mysql_select_db('hw03', $connection);
	if (!$db_selected) {
		die('Can\'t use hw03 : '. mysql_error());
	}

	$result = mysql_query("show tables like '%".
		mysql_escape_string($input)."%'", $connection);
	if ($result !== false) {
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			echo "<td><a href='b.php?tn=$row[0]'>$row[0]</a></td>";
		}
	}

	unset($result);

	echo "
	</tr>
	</table>
	</form>";
	?>
</body>
</html>
