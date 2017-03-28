<?php
	header('Content-Type: application/json');
	
	$DBName = "twitter";
	$DBHost = "localhost";
	$DBUser = "root";
	$DBUserPass ="ROOT";


	function connect_db($DBHost,$DBUser,$DBUserPass,$DBName)
	{
		$db = mysqli_connect($DBHost,$DBUser,$DBUserPass,$DBName);
		if(mysqli_connect_errno())
		{
			echo "cannot connect";
			return false;
		}
		return $db;
	}


	function check_user_valid($email,$password,$db)
	{
	    $sql = "select * from user where email='".$email."' and password= '" .$password."'";
		$result = mysqli_query($db,$sql);
		$userid = $result->fetch_assoc()["id"];	
		return $userid;
	}

	

	function check_userid_where_token($token,$db)
	{
		$checkusersql = "select * from user where logintoken ='".$token."'";
		$result = mysqli_query($db,$checkusersql);
		$userid = $result->fetch_assoc()["id"];

		return $userid;
	}





?>