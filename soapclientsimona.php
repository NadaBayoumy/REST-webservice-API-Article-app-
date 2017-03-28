<?php
include('nusoap-master/nusoap.php');
$client=new nusoap_client("http://www.wsdl.com");
$error=$client->getError();

if($error)
{
  echo "<h2>constructor error</h2><pre>".$error."</pre>";
}


$result=$client->call("employee.login",array());
var_dump($result);
?>