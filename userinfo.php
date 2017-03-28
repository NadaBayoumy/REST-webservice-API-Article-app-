<?php
include_once('dbconfig.php');
extract($_GET);
$db = connect_db($DBHost,$DBUser,$DBUserPass,$DBName);

$userid = check_userid_where_token($token,$db);
$result = getuserinfo($userid,$db);


if($result){

	print_json($result,$token);
}




function print_json($result,$token)
{
	if($result)
	{
		$output=[
		'code' => '200',
		'message' => ['Data Retrieved successfully'],
		'success' => 'true',
		'token' => $token,
		];

		$co = mysqli_num_rows($result);

		for($i=0;$i<$co;$i++)
		{
		 	  $r = mysqli_fetch_assoc($result);
	 	  	   $output['user'] = ['id' => $r["id"] ,
					   'name' => $r["name"],
					   'email' => $r["email"],
					   'password' => $r["password"],
					   'logintoken' => $r["logintoken"],
					   ];

		}
	}
	else{
		$output=[
		'code' => '700',
		'message' => ['Cannot retrieve data'],
		'success' => 'false',
		'token' => '',
		];
	}
	print_r(json_encode($output));
}





function getuserinfo($id,$db){

	$getusersql = "select * from user where id ='".$id."'";
	$result = mysqli_query($db,$getusersql);
	return $result;
}

						 
?>	
			