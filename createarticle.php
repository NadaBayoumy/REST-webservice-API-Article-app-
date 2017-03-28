<?php
include_once('dbconfig.php');
extract($_GET);
$db = connect_db($DBHost,$DBUser,$DBUserPass,$DBName);
$userid = check_userid_where_token($token,$db);

$result = insert_into_article($userid,$title,$body,$db);
print_json($result,$token);

function print_json($result,$token){
	if(!$result)
	{
		$output=[
		'code' => '700',
		'message' => ['Cannot Insert article'],
		'success' => 'false',
		'token' => '',
		];
	}
	else{
		$output=[
			'code' => '200',
			'message' => ['Article inserted successfully'],
			'success' => 'true',
			'token' => $token,
		];
	}
	print_r(json_encode($output));
}

function insert_into_article($userid,$title,$body,$db)
{
	$sql = "insert into article (`userId`, `title`, `body`) values (". $userid. ",'".$title."','".$body."')";
	$result = mysqli_query($db,$sql);
	return $result;
}				 
?>	
			