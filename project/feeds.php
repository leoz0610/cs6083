<?php 	

	$query = "select requestId, requestTime from friendship
	where acceptedId = '{$_SESSION['uid']}' and acceptedTime = 0";

	$result = mysql_query($query, $connection) or 
		die("Can't query table friendship.");

	$row = mysql_fetch_array($result);

	echo "<div class='login-area'>";

	if (!empty($row))
		echo "<a href='friendResponse.php'>
		New Feeds</a> | ";
				
	echo	"{$_SESSION["uid"]}
		| <a class='login-link' href='logout.php'>
		Log out
		</a>
		</div>";

?>
