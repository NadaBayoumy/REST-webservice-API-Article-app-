<?php
require_once('nusoap-master/nusoap.php');

class article{

	public $name;
    public $email;
    public $password;
    public $tokenstr;
    public $DBName;
	public $DBHost;
	public $DBUser;
	public $DBUserPass;
	public $db;

    function __construct(){
    		$this->DBName = "twitter";
			$this->DBHost = "localhost";
			$this->DBUser = "root";
			$this->DBUserPass ="ROOT";
			$this->db = mysqli_connect($this->DBHost,$this->DBUser,$this->DBUserPass,$this->DBName);
	}

	public function viewarticles($token)
	{
		$checkusersql = "select * from user where logintoken ='".$token."'";
		$result = mysqli_query($this->db,$checkusersql);
		$userid = $result->fetch_assoc()["id"];

		if(!$userid)
		{
			$result = "";
			return $this->print_json_all_articles($token,$result);
		}
		else
		{
			$getarticlesql = "select * from article ";
			$result = mysqli_query($this->db,$getarticlesql) or die(mysqli_error($this->db));
			
			return $this->print_json_all_articles($token,$result);
		}

	
	}


	public function create_new_user($name,$email,$password,$tokenstr)
	{
		$sql = "insert into user (`name`, `email`, `password`, `logintoken`) values ('".$name."','".$email."','".$password."','".$tokenstr."')";

		$result = mysqli_query($this->db,$sql) or die(mysql_error($sql));
		return $this->print_json_create_user($tokenstr,$result);
	}


	function check_user_valid($email,$password)
	{
	    $sql = "select * from user where email='".$email."' and password= '" .$password."'";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error($this->db));
		$userid = $result->fetch_assoc()["id"];	
		
		if($userid)
		{
				$tokenstr = md5(uniqid(rand(), true));
				$resultofupdate = $this->update_user_token($userid,$tokenstr,$this->db);
				return $this->print_json_check_user($tokenstr,$resultofupdate);
		}
		else
		{
				$resultofupdate = false;
				$tokenstr =null;
				return $this->print_json_check_user($tokenstr,$resultofupdate);
		}	
	}


	function delete_article($token,$id)
	{
		$checkusersql = "select * from user where logintoken ='".$token."'";
		$result = mysqli_query($this->db,$checkusersql) or die(mysqli_error($this->db));
		$userid = $result->fetch_assoc()["id"];

		if(!$userid)
		{
				$output=[
				'code' => '700',
				'message' => ['Cannot delete article'],
				'success' => 'false',
				'token' => '',
				];
		}
		else
		{
				$sql = "delete from article where id ='".$id."'";
				$result = mysqli_query($this->db,$sql);

				$output=[
				'code' => '200',
				'message' => ['Article deleted successfully'],
				'success' => 'true',
				'token' => $token,
				];
		}

		
		return json_encode($output);
	}





	function update_user_token($userid,$tokenstr,$db)
	{							
		$tokenstr = md5(uniqid(rand(), true));
		$updatetokensql = "update user set logintoken='".$tokenstr."' where id=".$userid;
		$resultofupdate = mysqli_query($db,$updatetokensql) or die(mysqli_error($this->db));
		return $resultofupdate;

	}

	function print_json_create_user($tokenstr,$result)
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
		return json_encode($output);
	}


	function print_json_check_user($tokenstr,$resultofupdate)
	{
		if($resultofupdate)
		{
			$output=[
			'code' => '200',
			'message' => ['You have Retrieved results successfully'],
			'success' => 'true',
			'token'=> $tokenstr,
			];
		}
		else
		{
			$output=[
			'code' => '300',
			'message' => ['No Records Found'],
			'success' => 'false',
			'token'=> '',
			];
		}

		return json_encode($output);
	}



	function print_json_get_user_info($result,$token)
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
			return json_encode($output);
		

	}



	function getuserinfo($token)
	{
		$checkusersql = "select * from user where logintoken ='".$token."'";
		$result = mysqli_query($this->db,$checkusersql) or die(mysqli_error($this->db));
		$userid = $result->fetch_assoc()["id"];

		$getusersql = "select * from user where id ='".$userid."'";
		$result = mysqli_query($this->db,$getusersql);
		
		if($result){

			return $this->print_json_get_user_info($result,$token);
		}

	}



	function insert_into_article($title,$body,$token)
	{
		$checkusersql = "select * from user where logintoken ='".$token."'";
		$result = mysqli_query($this->db,$checkusersql) or die(mysqli_error($this->db));
		$userid = $result->fetch_assoc()["id"];


		$sql = "insert into article (`userId`, `title`, `body`) values (". $userid. ",'".$title."','".$body."')";
		$result = mysqli_query($this->db,$sql) or die(mysqli_error($this->db));
		
		return $this->print_json_create_article($result,$token);
	}	



	function print_json_create_article($result,$token)
	{
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
		return json_encode($output);
	}


	function view_article($id,$token)
	{
		$checkusersql = "select * from user where logintoken ='".$token."'";
		$result = mysqli_query($this->db,$checkusersql) or die(mysqli_error($this->db));
		$userid = $result->fetch_assoc()["id"];

		if($userid)
		{
			$getarticlesql = "select * from article where id ='".$id."'";
			$result = mysqli_query($this->db,$getarticlesql) or die(mysqli_error($this->db));
		}
		else
		{
			$result = "";
		}	
		return $this->print_json_view_article($token, $result);
	}


	function print_json_all_articles($token,$result)
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

			return (json_encode($output));
	}






	function print_json_view_article($token,$result)
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
			return json_encode($output);
	}
}









$server = new soap_server();
$server->configureWSDL("articleservice");

$server->register("article.create_new_user",
array("name" => "xsd:string","email"=>"xsd.string","password"=>"xsd.string","tokenstr"=>"xsd.string"),
array("return" => "xsd:string"));

$server->register("article.check_user_valid",
array("email"=>"xsd.string","password"=>"xsd.string"),
array("return" => "xsd:string"));

$server->register("article.getuserinfo",
array("token"=>"xsd.string"),
array("return" => "xsd:string"));

$server->register("article.insert_into_article",
array("title"=>"xsd.string","body"=>"xsd.string","token"=>"xsd.string"),
array("return" => "xsd:string"));

$server->register("article.view_article",
array("id"=>"xsd.int","token"=>"xsd.string"),
array("return" => "xsd:string"));

$server->register("article.viewarticles",
array("token"=>"xsd.string"),
array("return" => "xsd:string"));


$server->register("article.delete_article",
array("token"=>"xsd.string","id"=>"xsd.int"),
array("return" => "xsd:string"));




$server->service(file_get_contents("php://input"));



// viewall articles
// $art = new article();
// $token = "df4ece4404333b74b1e12aefcd4fee38";
// echo $art->viewarticles($token);
// exit();



//create new
// $art = new article();
// $name="UserNameTest";
// $email="Test1@hotmail.com";
// $password="1";
// $tokenstr = md5(uniqid(rand(), true));
// echo $art->create_new_user($name,$email,$password,$tokenstr = NULL);


//login
// $art = new article();
// $email="nada@hotmail.com";
// $password="1";

// echo $art->check_user_valid($email,$password);


//getuserinfo
// $art = new article();
// $token = "df4ece4404333b74b1e12aefcd4fee38";
// echo $art->getuserinfo($token);


//createarticle
// $art = new article();
// $token = "df4ece4404333b74b1e12aefcd4fee38";
// $title = "article test title";
// $body ="article test body ";
// echo $art->insert_into_article($title,$body,$token);



//viewarticle
// $art = new article();
// $token = "df4ece4404333b74b1e12aefcd4fee38";
// $id = 4;
// echo $art->view_article($id,$token);


//delete article
// $art = new article();
// $token = "df4ece4404333b74b1e12aefcd4fee38";
// $id=1;
// echo $art->delete_article($token,$id);
// exit();