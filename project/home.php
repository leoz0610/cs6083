<!DOCTYPE html>
<html>
<head>
<title>Home page</title>


    <!-- jy for beauty -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="./static/favicon.png">

    <!-- Bootstrap core CSS -->
    <link href="./static/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./static/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

<link type="text/css" rel="stylesheet" href="./static/main.css" />


</head>
<body>
	<?php
		session_start();
		if (empty($_SESSION["isLogged"])) {
			echo "<div class='login-area'>
				<a class='login-link' href='login.php'>
				Log in</a>
				|
				<a class='login-link' href='signup.php'>
 					Sign up
				</a>
				</div>";
			echo "<h1>To explore more?
				<br>Just sign up OurPinterest!</h1>";

      echo '<br> <img src=" picture/home.jpg" alt="HTML tutorial" width="1260" height="600" align="middle" >';



		} else {
			include "./DB.php";

			include "./feeds.php";

			echo "<pre><h2>Welcome to OurPinterest, {$_SESSION['uid']}!</h2></pre>";
			
			echo "<table class='status'>
			<tr>
			<td class='post'><a href='profile.php'>
			My Profile</a></td></tr>
			<tr><td class='post'>
			<a href='friends.php'>Friends</a>
			</td></tr>
			<tr><td class='post'>
			<a href='pinboard.php'>My Pinboards: </a>
			</td></tr>
			</table>";	

			$query = "select * from pinboard where uid='{$_SESSION['uid']}'";
			$result = mysql_query($query, $connection) or 
				die("Can't query table pinboard.");

			echo "<ul>";
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				echo "<li><a href='boardContent.php?bid={$row['bid']}&
					bname={$row['bname']}'>
					{$row['bname']}</a></li>";
			}
			echo "</ul>";

			echo "<div class='status'><a href='followstream.php'>
				My FollowStream:</a></div>";

			$query = "select * from followStream where uid='{$_SESSION['uid']}'";
			$result = mysql_query($query, $connection) or 
				die("Can't query table pinboard.");

			echo "<ul>";
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				echo "<li><a href='followContent.php?fid={$row['fid']}&
					fname={$row['fname']}'>
					{$row['fname']}</a></li>";
			}
			echo "</ul>";

			echo "<h2>Our Recommandations for you:</h2>";
			$query = "select keyword from history where uid='{$_SESSION['uid']}'";
			$result = mysql_query($query, $connection) or 
				die("Failed history");
			
			echo "<table><tr>";
			while ($key = mysql_fetch_array($result, MYSQL_NUM)) {
				$query = "select * from pinboard where
					bname like '%{$key[0]}%'";
				$result1 = mysql_query($query, $connection) or
					die("Failed search.");
				while ($row = mysql_fetch_array($result1, MYSQL_ASSOC)) {
					echo "<td><a href='boardContent.php?bid={$row['bid']}&
						bname={$row['bname']}'>
						{$row['bname']}</a></td>";
				}

				$query = "select P.* from tag T, picture_tag PT, picture P
				where tname like '%{$key[0]}%' and T.tid=PT.tid and
				PT.pid=P.pid";
				$result1 = mysql_query($query, $connection) or
					die("Failed search.");
				while ($row = mysql_fetch_array($result1, MYSQL_ASSOC)) {
					echo "<td><img src='{$row['url_local']}' height='200' width='200'></td>";
				}
			}
			echo "</tr></table>";
		}
	?>
</body>
</html>
