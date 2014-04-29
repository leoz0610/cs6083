<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="./static/main.css" />
<title>Pin page</title>
</head>
<body>

<?php
		//jy
		session_start();
		if (empty($_SESSION["isLogged"])) {
			header("Location: home.php");
			exit;
		}
		include "./DB.php";	
		include "./feeds.php";
		include "./path.php";
		echo "<h2>Pin a picture</h2>";
		echo "<form action='pin.php?bid=".$_GET['bid']."' method='post'>";
		//jy add tag
		echo "<label>pin a picture from its URL:</label>
		URL:<input name='URL' type='text'><br>
		Tag: <input name='Tag' type='text'><br>
		<input type='submit' value='submit'>";
		// check if URL already exist or not
		if (!empty($_POST["URL"]))
		{
			$query = "select pid from picture where url = '{$_POST['URL']}'";
			$result = @ mysql_query($query, $connection) or 
				die("Can't connect table picture.");
			$result1 = mysql_fetch_array($result, MYSQL_ASSOC);
			echo $result1['pid'];
			if (!empty($result1['pid']))
     			 {
      				echo " <div class='error'>This picture already exists. </div>";
     			 }
			else
			//the URL does not exist, so add pin it i.e. insert it into tables picture, pin 
			{
			//echo $_POST['URL'];

			$query = "insert into picture(url, ptime)
				value ('{$_POST['URL']}', now())";	
			//echo $query;

			$result =  mysql_query($query, $connection) or 
				die("Can't insert into table picture.");

			
			$query = "select pid from picture where url = '{$_POST['URL']}'";
			$result = @ mysql_query($query, $connection) or 
					die("Can't connect table picture.");
			$row = mysql_fetch_array($result, MYSQL_ASSOC);

			$query = "insert into pin(bid, pid, pintime) value ({$_GET['bid']}, {$row['pid']}, now())";

			$resultInsert = mysql_query($query, $connection) or 
				die("Can't insert into pin.");
			$report = file_get_contents($_POST["URL"]);

			$temp_pid = $row["pid"];
			$urllocal = "{$path}{$temp_pid}.jpg";
			file_put_contents("{$urllocal}", $report);

			$url = "picture/".basename($urllocal);

			$query2 = "UPDATE picture SET url_local='{$url}' WHERE url ='{$_POST['URL']}'";
			$result = @ mysql_query($query2, $connection) or 
				die("Can't connect table picture.");


			
			
			//jy add tag
			$query = "select tid from tag where tname = '{$_POST['Tag']}'";
			$result = @ mysql_query($query, $connection) or 
				die("Can't connect table tag.");
			$result1 = mysql_fetch_array($result, MYSQL_ASSOC);
			$temp_tid = $result1['tid'];
			echo $result1['tid'];
			if (!empty($result1['tid']))
     			 {
      				echo " <div class='error'>This tag already exists. </div>";
				 $query = "insert into picture_tag(tid, pid, relevance)
					value ({$temp_tid},{$temp_pid},1)";	
				$result =  mysql_query($query, $connection) or 
					die("Can't insert into table picture_tag.");
     			 }
			else
			{
				$query = "insert into tag(tname)
					value ('{$_POST['Tag']}')";	
				$result =  mysql_query($query, $connection) or 
					die("Can't insert into table tag.");

				$query = "select tid from tag where tname = '{$_POST['Tag']}'";
				$result = @ mysql_query($query, $connection) or 
					die("Can't connect table tag.");
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$temp_tid = $row["tid"];
				$query = "insert into picture_tag(tid, pid, relevance)
					value ({$temp_tid},{$temp_pid},1)";	
				$result =  mysql_query($query, $connection) or 
					die("Can't insert into table picture_tag.");

				

			}
			


			

			echo "Successful!";

			}
		}
		echo "</form>";
?>


<br>
<br>
<br>

<form action="pin.php?bid=<?php echo $_GET['bid'];?>" method="post"
enctype="multipart/form-data">
<label for="file">pin a picture from local forder:</label>
File: <input type="file" name="file" id="file"><br>
Tag:  <input name='Tag' type='text'><br>
<input type="submit" name="submit" value="Submit">
</form>


<?php
	if (isset($_FILES['file'])) {

		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& ($_FILES["file"]["size"] < 2000000)
		&& in_array($extension, $allowedExts))
		{
			if ($_FILES["file"]["error"] > 0)
			{
    				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
			}
			else
			{
				/*
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
				 */
				
				if (file_exists(
					$path . $_FILES["file"]["name"]))
				{
					echo "<div class=error>". $_FILES["file"]["name"] . " already exists.</div>";
				}
				else
				{
					move_uploaded_file($_FILES["file"]["tmp_name"],
						$path . $_FILES["file"]["name"]);
					/*echo "Stored in: " . $path .
						$_FILES["file"]["name"];*/

      					$url = $path . $_FILES["file"]["name"];
					$urllocal = "picture/". $_FILES['file']['name'];

     					$query = "insert into picture(url, ptime, url_local)
	      					value ('{$url}', now(), '{$urllocal}')";
					$result =  mysql_query($query, $connection) or 
						die("Can't insert into table picture.");

					$query = "select pid from picture where url='{$url}'";

					$result = mysql_query($query, $connection) or 
						die("Can't select pid.");

					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					$temp_pid = $row["pid"];


					$query = "insert into pin(bid, pid, pintime) 
						value ({$_GET['bid']}, {$row['pid']}, now())";

					$resultInsert = mysql_query($query, $connection) or 
						die("Can't insert into pin.");

					
			//jy add tag
			$query = "select tid from tag where tname = '{$_POST['Tag']}'";
			$result = @ mysql_query($query, $connection) or 
				die("Can't connect table tag.");
			$result1 = mysql_fetch_array($result, MYSQL_ASSOC);
			$temp_tid = $result1["tid"];
			echo $result1['tid'];
			if (!empty($result1['tid']))
     			 {
				 echo " <div class='error'>This tag already exists. </div>";
				 $query = "insert into picture_tag(tid, pid, relevance)
					value ({$temp_tid},{$temp_pid},1)";	
				$result =  mysql_query($query, $connection) or 
					die("Can't insert into table picture_tag.");
     			 }
			else
			{
				$query = "insert into tag(tname)
					value ('{$_POST['Tag']}')";	
				$result =  mysql_query($query, $connection) or 
					die("Can't insert into table tag.");

				$query = "select tid from tag where tname = '{$_POST['Tag']}'";
				$result = @ mysql_query($query, $connection) or 
					die("Can't connect table tag.");
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$temp_tid = $row["tid"];
				$query = "insert into picture_tag(tid, pid, relevance)
					value ({$temp_tid},{$temp_pid},1)";	
				$result =  mysql_query($query, $connection) or 
					die("Can't insert into table picture_tag.");

				

			}
			
			
			echo "Successful!";
				}
			}
		}
		else
		{
			echo "<div class='error'>Invalid file</div>";
		}
	}
?>
<br>
<br>
<br>

</form>
	<form action="home.php">
		<input type="submit" value="Back to home page">
	</form>

</body>
</html>
