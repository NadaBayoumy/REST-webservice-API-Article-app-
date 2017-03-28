<?php
include('nusoap-master/nusoap.php');
$client=new nusoap_client("http://webservice.iti.com/soapserver.wsdl",true);
$error=$client->getError();
if($error)
{
  echo "<h2>constructor error</h2><pre>".$error."</pre>";
}

//$result=$client->call("article.create_new_user",array('name'=>'simona','email'=>'simona.soliman@yahoo.com','password'=>'123456','tokenstr'=>'1w23edsa7hyte6jg68fdwww'));

// $result=$client->call("article.check_user_valid",array('email'=>'simona.soliman@yahoo.com','password'=>'123456'));

$result=$client->call("article.getuserinfo",array('tokenstr'=>'1w23edsa7hyte6jg68fdwww'));

// $result=$client->call("article.insert_into_article",array('title'=>'hello','body'=>'hello it','token'=>'1w23edsa7hyte6jg68fdwww'));

//$result=$client->call("article.view_article",array('id'=>'14','token'=>'1w23edsa7hyte6jg68fdwww'));

// $result=$client->call("article.viewarticles",array('token'=>'1w23edsa7hyte6jg68fdwww'));

// $result=$client->call("article.delete_article",array('token'=>'1w23edsa7hyte6jg68fdwww','id'=>'14'));

var_dump($result);

?>