<?php
include_once('dbconfig.php');
extract($_GET);
$db = connect_db($DBHost,$DBUser,$DBUserPass,$DBName);
$userid = check_userid_where_token($token,$db);
if($userid)
{
	$result = getallarticles($db);
	if( mysqli_num_rows($result)){
		print_json($token,$result);
	}
}


function print_json($token,$result)
{
		if( mysqli_num_rows($result)){
			$co = mysqli_num_rows($result);
			
			$output=[
				'code' => '200',
				'message' => ['Data of article Retrieved successfully'],
				'success' => 'true',
				'token' => $token,
			];

			 for($i=0;$i<$co;$i++)
			 {
			 	  $r = mysqli_fetch_assoc($result);
				    $output['article'][$i] =   ['id' => $r["id"] ,
										   'userid' => $r["userId"],
										   'title' => $r["title"],
										   'body' => $r["body"],								   
										     ];
			 }

		}
		else{
			$output=[
			'code' => '700',
			'message' => ['Cannot retrieve article data'],
			'success' => 'false',
			'token' => '',
			];
		}

		print_r(json_encode($output));
}




function getallarticles($db){

	$getarticlesql = "select * from article ";
	$result = mysqli_query($db,$getarticlesql);
	return $result;
}

						 
?>	
			



