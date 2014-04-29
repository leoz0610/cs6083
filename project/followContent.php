<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title><?php echo $_GET['fname']; ?></title>
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

		$query = "select * from followstream where fid={$_GET['fid']}
		and uid='{$_SESSION['uid']}' and fname='{$_GET['fname']}'";
		$result = mysql_query($query, $connection) or 
			die("Failed.");
		$row = mysql_fetch_array($result, MYSQL_ASSOC);

		if (empty($row)) {
			header("Location: home.php");
			exit;
		}
		//for pictures
		$query = "select distinct url_local, pname from picture PIC,
			follow_picture FP, followstream F where PIC.pid=FP.pid 
			and F.fid={$_GET['fid']} and FP.fid=F.fid";

		$result = mysql_query($query, $connection) or 
			die("Failed to query picture.");

		echo "<h2>{$_SESSION['uid']}'s {$_GET['fname']}</h2>";

		echo "<div class='pin-area'>
				<a class='pin-link' href='followpicture.php?fid=".$_GET['fid']."'>
				Follow a picture</a>	
				</div>";
		//	echo "<a href='followpicture.php?fid=".$_GET['fid']."'>
		//	<img src='add_picture.jpg' alt='HTML tutorial'></a>";
		echo "<label>Following Pictures:</label><table>";
		$i = 0;
		$flag = false;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($i == 0) {
				$flag = true;
				echo "<tr>";
			}
			$i++;
			echo "<td><img src='{$row['url_local']}' alt='Pictures'  width='200' height='200'>
			<label>{$row['pname']}</label></td>";
			if ($i == 2) {
				$i = 0;
				$flag = false;
				echo "</tr>";
			}
		}
		if ($flag)
			echo "</tr>";
		echo "</table><br>";

		// for boards
		$query = "select distinct B.bid, bname from pinboard B,
			follow_board FB, followstream F where F.fid={$_GET['fid']} and B.bid=FB.bid
			and FB.fid=F.fid";

		$result = mysql_query($query, $connection) or 
			die("Failed to query board.");

		echo "<div class='pin-area'>
				<a class='pin-link' href='followboard.php?fid=".$_GET['fid']."'>
				Follow a board</a>	
				</div>";
		//	echo "<a href='followboard.php?fid=".$_GET['fid']."'>
		//	<img src='add_picture.jpg' alt='HTML tutorial'></a>";
		echo "<label>Following Boards:</label><table>";
		$i = 0;
		$flag = false;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($i == 0) {
				$flag = true;
				echo "<tr>";
			}
			$i++;
			echo "<td><a href='boardContent.php?bid={$row['bid']}&
				bname={$row['bname']}'>
			{$row['bname']}</a></td>";
			if ($i == 2) {
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

