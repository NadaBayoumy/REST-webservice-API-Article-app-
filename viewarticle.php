<?php
include_once('dbconfig.php');
extract($_GET);
$db = connect_db($DBHost,$DBUser,$DBUserPass,$DBName);
$userid = check_userid_where_token($token,$db);

if($userid)
{
	$result = getarticle($id,$db);
	print_json($token,$result);
}


function print_json($token,$result)
{
		$r = mysqli_fetch_assoc($result);

		if($r){
			
			$co = mysqli_num_rows($result);

			$output=[
				'code' => '200',
				'message' => ['Data of article Retrieved successfully'],
				'success' => 'true',
				'token' => $token,
			];

			 for($i=0;$i<$co;$i++)
			 {
			 	  
		 	  	   $output['article'] =   ['id' => $r["id"] ,
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




function getarticle($id,$db){

	$getarticlesql = "select * from article where id ='".$id."'";
	$result = mysqli_query($db,$getarticlesql);

	return $result;
}

						 
?>	
			