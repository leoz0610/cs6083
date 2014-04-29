<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Sign up page</title>

    <!-- jy for beauty -->
    //<meta charset="utf-8">
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


</head>
<body>
<div class="container">  <!--//jy for beauty-->

<form class="form-signin" role="form" method="post">

	<h2 class="form-signin-heading">Please sign up</h2>
		<?php
		session_start();

		include "./DB.php";

		if (!isset($_POST["userID"]))
			$_POST["userID"] = "";
		if (!isset($_POST["password"]))
			$_POST["password"] = "";
		if (!isset($_POST["repwd"]))
			$_POST["repwd"] = "";

		$error[0] = "";
		$error[1] = "";
		$error[2] = "";

		//Validate the username
		if (strlen($_POST["userID"]) > 30) {
			$error[0] = "The maximum length of username is 30 characters.";
		} else if (!empty($_POST["userID"])){
			$userID = mysql_real_escape_string($_POST["userID"]);
			$query = "select * from user where uid = '".$userID."'";
			$result = mysql_query($query, $connection);

			if ($result !== false)
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
			if (!empty($row["uid"]))
				$error[0] = "The username exists.";
		}

		//Validate the password
		/*if (empty($_POST["password"])) {
			$error[1] = "The password should not be empty.";
		}*/

		//Validate the retyped password
		if (strcmp($_POST["repwd"], $_POST["password"]) !== 0) {
			$error[2] = "The password does not match.";
		}

		if (empty($error[0]) && empty($error[1]) && empty($error[2])) {
			if (!empty($_POST["userID"]) && !empty($_POST["password"])) {
				$userID = mysql_real_escape_string($_POST["userID"]);
				$pwd = mysql_real_escape_string($_POST["password"]);
				$result = @ mysql_query("insert into user(uid, password)
					value ('{$userID}', '{$pwd}')") or
					die("Can't insert into table user.");
				$_SESSION["uid"] = $_POST["userID"];
				$_SESSION["isLogged"] = true;
				header("Location: ./home.php");
			}
		}
		
		echo "
			<input name='userID' type='text'  class='form-control' placeholder='User name (userID)' required autofocus value={$_POST['userID']}><div class='error'>{$error[0]}</div>
		<br>
		<input name='password' type='password' class='form-control' placeholder='Password' required ><div class='error'>{$error[1]}</div>
		<input name='repwd' type='password' class='form-control' placeholder='Password again' required ><div class='error'>{$error[2]}</div>
	<label class='checkbox'>
          <input type='checkbox' value='remember-me'> Remember me
        </label>
	<button class='btn btn-lg btn-primary btn-block' type='submit'>Sign up</button>";

	?>
 <!-- 	<br>
	<br>
	<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>
 -->

</form>

</div> <!-- /container -->

</body>
</html>
