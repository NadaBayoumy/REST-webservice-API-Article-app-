<?php
include_once('dbconfig.php');
extract($_GET);
// var_dump($_GET);

$db = connect_db($DBHost,$DBUser,$DBUserPass,$DBName);
$tokenstr = md5(uniqid(rand(), true));
$result = create_new_user($name,$email,$password,$tokenstr,$db);
print_json($tokenstr,$result);

function print_json($tokenstr,$result)
{
	if(!$result)
	{
		$output=[
		'code' => '100',
		'message' => ['Cannot Insert'],
		'success' => 'false',
		'token' => '',
		];
	}
	else{
		$output=[
			'code' => '200',
			'message' => ['Inserted'],
			'success' => 'true',
			'token' => $tokenstr,
		];
	}
	print_r(json_encode($output));
}






function create_new_user($name,$email,$password,$tokenstr,$db)
{
	$sql = "insert into user (`name`, `email`, `password`, `logintoken`) values ('".$name."','".$email."','".$password."','".$tokenstr."')";

	return mysqli_query($db,$sql);
}
						 
?>	
			