<?php
include_once('dbconfig.php');
extract($_GET);
$db = connect_db($DBHost,$DBUser,$DBUserPass,$DBName);
$userid = check_userid_where_token($token,$db);

if($userid){
	$result = delete_article($id,$db);
	print_json($result,$token);
}

function print_json($result,$token)
{
	if($result)
	{
		$output=[
		'code' => '200',
		'message' => ['Article deleted successfully'],
		'success' => 'true',
		'token' => $token,
		];
	}else{
		$output=[
		'code' => '700',
		'message' => ['Cannot delete article'],
		'success' => 'false',
		'token' => '',
		];
	}
	print_r(json_encode($output));

}

function delete_article($id,$db)
{
	$sql = "delete from article where id ='".$id."'";
	$result = mysqli_query($db,$sql);
	return $result;
}

	

						 
?>	
			