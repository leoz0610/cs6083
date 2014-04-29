<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title><?php echo $_GET['bname']; ?></title>
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

		$query = "select * from pinboard where bid={$_GET['bid']}
		and uid='{$_SESSION['uid']}' and bname='{$_GET['bname']}'";
		$result = mysql_query($query, $connection) or 
			die("Failed.");
		$row = mysql_fetch_array($result, MYSQL_ASSOC);

		if (empty($row)) {
			

			//Pictures:

			$query = "select uid from pinboard where bid={$_GET['bid']}";
			$result = mysql_query($query, $connection) or die('Failed');
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			echo "<h2>{$row['uid']}'s {$_GET['bname']}</h2>";

			$query = "select distinct url_local, pname, uid, P.pid from picture PIC,
			pin P, pinboard B where B.bid={$_GET['bid']} and PIC.pid=P.pid
			and P.bid=B.bid";
			$result = mysql_query($query, $connection) or 
				die("Failed to query picture.");

			$row = mysql_fetch_array($result, MYSQL_ASSOC);


			echo "<label>Pictures:</label><table>";
			if (!empty($row)) {
				$i = 0;
				$flag = false;
				do {
					if ($i == 0) {
						$flag = true;
						echo "<tr>";
					}
					$i++;
					echo "<td><a href='pictureContent.php?pid={$row['pid']}&bid={$_GET['bid']}'>
					<img src='{$row['url_local']}' alt='Pictures'  width='200' height='200'></a>
					<label>{$row['pname']}</label></td>";
					if ($i == 6) {
						$i = 0;
						$flag = false;
						echo "</tr>";
					}
				} while ($row = mysql_fetch_array($result, MYSQL_ASSOC));

				if ($flag)
					echo "</tr>";
				echo "</table>";
			}

			//Repins:
			$query = "select distinct url_local, pname, R.pid from repin R, picture P 
			where tobid={$_GET['bid']} and R.pid=P.pid";
			$result = mysql_query($query, $connection) or 
				die("Failed to query picture.");

			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			echo "<label>Repins:</label><table>";
			if (!empty($row)) {
				$i = 0;
				$flag = false;
				do {
					if ($i == 0) {
						$flag = true;
						echo "<tr>";
					}
					$i++;
					echo "<td><a href='pictureContent.php?pid={$row['pid']}&bid={$_GET['bid']}'>
					<img src='{$row['url_local']}' alt='Pictures'  width='200' height='200' ></a>
					<label>{$row['pname']}</label></td>";
					if ($i == 6) {
						$i = 0;
						$flag = false;
						echo "</tr>";
					}
				} while ($row = mysql_fetch_array($result, MYSQL_ASSOC));

				if ($flag)
					echo "</tr>";
				echo "</table>";
			}

			echo "</form>
			<form action='home.php'>
			<input type='submit' value='Back to home page'>
			</form>

			</body>
			</html>";
			exit;
		}

		/*if ($row['isPrivate'] == 1) {

			$query = "select distinct url_local, pname, P.pid from picture PIC,
			pin P, pinboard B where B.bid={$_GET['bid']} and PIC.pid=P.pid
			and P.bid=B.bid";
			$result = mysql_query($query, $connection) or 
				die("Failed to query picture.");

			echo "<h2>{$_SESSION['uid']}'s {$_GET['bname']}</h2>";

			echo "<div class='pin-area'>
			<a class='pin-link' href='pin.php?bid=".$_GET['bid']."'>
			Pin a picture</a>	
			</div>";
		//	echo "<a href='pin.php?bid=".$_GET['bid']."'>
		//	<img src='add_picture.jpg' alt='HTML tutorial'></a>";
			echo "<label>Pictures:</label><table>";
			$i = 0;
			$flag = false;
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if ($i == 0) {
					$flag = true;
					echo "<tr>";
				}
				$i++;
				echo "<td><a href='pictureContent.php?pid={$row['pid']}&bid={$_GET['bid']}'>
				<img src='{$row['url_local']}' alt='Pictures'  width='200' height='200' ></a>
				<label>{$row['pname']}</label></td>";
				if ($i == 6) {
					$i = 0;
					$flag = false;
					echo "</tr>";
				}
			}
			if ($flag)
				echo "</tr>";
			echo "</table>";

		//repins:
			$query = "select distinct url_local, pname, R.pid from picture P, repin R
			where tobid={$_GET['bid']} and P.pid=R.pid";
			$result = mysql_query($query, $connection) or 
				die("Failed to query picture.");

			echo "<label>Reins:</label><table>";
			$i = 0;
			$flag = false;
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if ($i == 0) {
					$flag = true;
					echo "<tr>";
				}
				$i++;
				echo "<td><a href='pictureContent.php?pid={$row['pid']}&bid={$_GET['bid']}'>
				<img src='{$row['url_local']}' alt='Pictures'  width='200' height='200' ></a>
				<label>{$row['pname']}</label></td>";
				if ($i == 6) {
					$i = 0;
					$flag = false;
					echo "</tr>";
				}
			}
			if ($flag)
				echo "</tr>";
			echo "</table>";
	
			exit;
		} // end of isPrivate*/

		$query = "select distinct url_local, pname, P.pid from picture PIC,
			pin P, pinboard B where B.bid={$_GET['bid']} and PIC.pid=P.pid
			and P.bid=B.bid";
		$result = mysql_query($query, $connection) or 
			die("Failed to query picture.");

		echo "<h2>{$_SESSION['uid']}'s {$_GET['bname']}</h2>";

		echo "<div class='pin-area'>
				<a class='pin-link' href='pin.php?bid=".$_GET['bid']."'>
				Pin a picture</a>	
				</div>";
		//	echo "<a href='pin.php?bid=".$_GET['bid']."'>
		//	<img src='add_picture.jpg' alt='HTML tutorial'></a>";
		echo "<label>Pictures:</label><table>";
		$i = 0;
		$flag = false;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($i == 0) {
				$flag = true;
				echo "<tr>";
			}
			$i++;
			echo "<td><a href='pictureContent.php?pid={$row['pid']}&bid={$_GET['bid']}'>
			<img src='{$row['url_local']}' alt='Pictures'  width='200' height='200' ></a>
			<label>{$row['pname']}</label></td>";
			if ($i == 6) {
				$i = 0;
				$flag = false;
				echo "</tr>";
			}
		}
		if ($flag)
			echo "</tr>";
		echo "</table>";

		//repins:
		$query = "select distinct url_local, pname, R.pid from picture P, repin R
		where tobid={$_GET['bid']} and P.pid=R.pid";
		$result = mysql_query($query, $connection) or 
			die("Failed to query picture.");

		echo "<label>Reins:</label><table>";
		$i = 0;
		$flag = false;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($i == 0) {
				$flag = true;
				echo "<tr>";
			}
			$i++;
			echo "<td><a href='pictureContent.php?pid={$row['pid']}&bid={$_GET['bid']}'>
			<img src='{$row['url_local']}' alt='Pictures'  width='200' height='200' ></a>
			<label>{$row['pname']}</label></td>";
			if ($i == 6) {
				$i = 0;
				$flag = false;
				echo "</tr>";
			}
		}
		if ($flag)
			echo "</tr>";
		echo "</table>";
	?>
<br>
</form>
	<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>

</body>
</html>
