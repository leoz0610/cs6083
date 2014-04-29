<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Profile Editing</title>
</head>
<body>
	<?php
		session_start();
		if (empty($_SESSION["isLogged"])) {
			header("Location: home.php");
			exit;
		} else {

			echo "<h2>Profile Editing</h2>";
			
			include "./DB.php";

			include "./feeds.php";

			$query = "select * from profile where uid = '{$_SESSION['uid']}'";
			$result = @ mysql_query($query, $connection) or 
				die("Can't connect table profile.");

			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			$flag = true;

			if (empty($row['uid'])) { // User with no profile.
				$row["uid"] = $_SESSION["uid"];
				$query = "insert into profile(uid, ptime, age)
					value ('{$row['uid']}', now(), 0)";
				$result = @ mysql_query($query, $connection) or 
					die("Can't insert into table profile.");
				$query = "select * from profile where uid = '{$_SESSION['uid']}'";
				$result = @ mysql_query($query, $connection) or 
					die("Can't connect table profile.");

				$row = mysql_fetch_array($result, MYSQL_ASSOC);
			}


			if (!isset($_POST["name"])) {
				$_POST["name"] = $row['name'] or "";
				$flag = false;
			}

			if (!isset($_POST["gender"])) {
				$_POST["gender"] = $row['gender'] or "";
				$flag = false;
			}

			if (!isset($_POST["age"])) {
				$_POST["age"] = $row['age'] or 0;
				$flag = false;
			}

			if (!isset($_POST["email"])) {
				$_POST["email"] = $row['email'] or "";
				$flag = false;
			}

			if (!isset($_POST["selfDescription"])) {
				$_POST["selfDescription"] = $row['selfDescription'] or "";
				$flag = false;
			}

			if ($flag) {
				$query = "update profile set name = '{$_POST['name']}',
					age = {$_POST['age']}, gender = '{$_POST['gender']}',
					email = '{$_POST['email']}', 
					selfDescription = \"{$_POST['selfDescription']}\"
				       	where profileId='{$row['profileId']}'";
				$result = mysql_query($query, $connection) or 
					die("Can't update table profile.");
				header("Location: profile.php");
			}

			echo "<form method='post'><table>";

			echo "<tr><td class='post'>";
			echo "<label>Name: </label>
				<input type='text' name='name' value={$_POST['name']}>";
			echo "<br></td></tr>";
			
			echo "<tr><td class='post'>";
			echo "<label>Gender: </label>
				<input type='text' name='gender' value={$_POST['gender']}>";
			echo "<br></td></tr>";

			echo "<tr><td class='post'>";
			echo "<label>Age: </label>
				<input type='text' name='age' value={$_POST['age']}>";
			echo "<br></td></tr>";

			echo "<tr><td class='post'>";
			echo "<label>Email: </label>
				<input type='email' name='email' value={$_POST['email']}>";
			echo "<br></td></tr>";

			echo "<tr><td class='post'>";
			echo "<label>Self Description: </label>
				<textarea name='selfDescription'>{$_POST['selfDescription']}</textarea>";
			echo "<br></td></tr>";

			echo "</table>";

			echo "<input type='submit'>";
			echo "</form>";
		}	

	?>

	<br>
	</form>
		<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>	

</body>
</html>
