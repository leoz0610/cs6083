<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Log in page</title>

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

<div class="container"> <!--//jy for beauty-->
<form class="form-signin" role="form" method="post"><!--//jy for beauty-->


	<h2 class="form-signin-heading">Please log in</h2>
	<?php
		session_start();

		include "./DB.php";

/*		echo "<label>User ID</label>
		<input name='userID' type='text'>
		<br>
		<label>Password</label>
		<input name='password' type='password'>
		<br>
		<input type='submit' value='Log In'>";
 */

		echo "
			<input name='userID' type='text'  class='form-control' placeholder='User name (userID)' required autofocus >
		<br>
		<input name='password' type='password' class='form-control' placeholder='Password' required >
	<label class='checkbox'>
          <input type='checkbox' value='remember-me'> Remember me
        </label>
	<button class='btn btn-lg btn-primary btn-block' type='submit'>Sign in</button>";
		
		
		if (!isset($_POST["userID"]))
			$_POST["userID"] = "";
		if (!isset($_POST["password"]))
			$_POST["password"] = "";

		$userID = mysql_real_escape_string($_POST["userID"]);
		$query = "select * from
			user where uid = '".$userID."'";

		$result = mysql_query($query, $connection);

		if ($result !== false) {
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			if (!empty($row["uid"]) 
				&& strcmp($row["password"], $_POST["password"]) == 0) {
				$_SESSION["uid"] = $userID;
				$_SESSION["isLogged"] = true;
				header("Location: ./home.php");
			}
		}

	?>



</form> <!--//jy for beauty-->

	<br>
	<br>
	<form class="form-signin" role="form"  action="home.php">
<button class='btn btn-lg btn-primary btn-block' type='submit'>Back to home page</button>";
	</form>
</div> <!--//jy for beauty-->

</body>
</html>
