<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Picture</title>
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

		$query = "select * from picture where pid={$_GET['pid']}";
		$result = mysql_query($query, $connection) or die("Failed.");
		$row = mysql_fetch_array($result, MYSQL_ASSOC);

		$query = "select count(*) as cnt from likes
		where pid={$_GET['pid']}";
		$result = mysql_query($query, $connection) or die("Failed to count.");
		$temp = mysql_fetch_array($result, MYSQL_ASSOC);
		$cnt = $temp['cnt'];

		$query = "select bid from pin where pid={$_GET['pid']}";
		$result = mysql_query($query, $connection) or die("Failed to count.");
		$temp = mysql_fetch_array($result, MYSQL_ASSOC);
		$orgId = $temp['bid'];

		$query = "select * from likes where pid={$_GET['pid']} and uid='{$_SESSION['uid']}'";
		$result = mysql_query($query, $connection) or die("Failed to likes.");
		$temp = mysql_fetch_array($result, MYSQL_ASSOC);
		$isLiked = !empty($temp);

		if (!empty($_POST['selected'])) {
			$query = "insert into repin(frombid, tobid, pid, repintime)
			value ({$_GET['bid']}, {$_POST['selected']}, {$_GET['pid']}, now())";
			$result = mysql_query($query, $connection) or die("Failed");
		}

		if (!empty($_POST['cmt'])) {
			$query = "insert into comments(uid, bid, pid, cmtime, content)
			value ('{$_SESSION['uid']}', {$_GET['bid']}, {$_GET['pid']}, now(), \"{$_POST['cmt']}\")";
			$result = mysql_query($query, $connection) or die("Failed");
		}

		$query = "select C.* from comments C, pinboard B 
		where pid={$_GET['pid']} and C.bid={$_GET['bid']}
		and C.bid=B.bid and B.isPrivate!=1
		order by cmtime desc";
		$comments = mysql_query($query, $connection) or die("Failed to likes.");

		$query = "select * from pinboard where uid='{$_SESSION['uid']}' and 
		bid not in (select bid from pin where bid={$_GET['bid']} and pid={$_GET['pid']}
		union select tobid as bid from repin 
		where pid={$_GET['pid']} and frombid={$_GET['bid']}
		union select frombid as bid from repin 
		where pid={$_GET['pid']} and tobid={$_GET['bid']})
		and bid!={$_GET['bid']}";
		$repins = mysql_query($query, $connection) or die("Failed to likes.");

		$query = "select uid, isPrivate from pinboard B where B.bid={$orgId}";
		$result = mysql_query($query, $connection) or die("die");
		$UserId = mysql_fetch_array($result, MYSQL_ASSOC);
		
		if (!empty($UserId) && $UserId['uid'] != $_SESSION['uid'] && $UserId['isPrivate'] == 1) {
			$query = "select requestId as uid from friendship where acceptedTime!=0
			and acceptedId='{$_SESSION['uid']}' and requestId='{$UserId['uid']}'
			union select acceptedId as uid from friendship where acceptedTime!=0
			and requestId='{$_SESSION['uid']}' and acceptedId='{$UserId['uid']}'";
			$result = mysql_query($query, $connection) or die("friends");
			$UserId = mysql_fetch_array($result, MYSQL_ASSOC);
		}


		echo "<img src='{$row['url_local']}' height='200' width='200'><br>
		<label>{$row['pname']}</label>";
		if ($isLiked)
			echo "({$cnt})Liked";
		else {
			echo "<a href='like.php?pid={$_GET['pid']}&bid={$_GET['bid']}'>
			({$cnt})Likes</a>";
		}

		echo "<form method='post'><select name='selected'>";
		while ($row = mysql_fetch_array($repins, MYSQL_ASSOC)) {
			echo "<option value={$row['bid']}>{$row['bname']}</option>";
		}
		echo "</select><input type='submit' value='Repin'></form>";

		echo "<label>Comments:</label><ul>";
		while ($row = mysql_fetch_array($comments, MYSQL_ASSOC)) {
			echo "<li>{$row['uid']}:<br>
			<pre>{$row['content']}</pre><br>
			Posted: {$row['cmtime']}</li>";
		}
		echo "</ul>";

		if (!empty($UserId)) {
			echo "<form method='post'>
				<textarea name='cmt'></textarea><br>
				<input type='submit'>
			</form>";
		}

	?>
<br>
</form>
	<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>

</body>
</html>
