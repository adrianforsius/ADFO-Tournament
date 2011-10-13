<?
	$connection = mysql_connect("127.0.0.1", "root", "root");
	mysql_select_db("tournament");
	
		$sql = "INSERT INTO users (user_name, user_password) VALUES('".$_POST['reguser']."', '".$_POST['regpassword']."')";
		$result = mysql_query($sql);
	echo $result ;
