<?php
session_start();

require("./phpMQTT.php");
require("./db_connection.php");

$mqtt = new phpMQTT("192.168.1.6", 1500, "sub"); 

if(!$mqtt->connect())
{
  exit(1);
}

$topics['command'] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);

while($mqtt->proc())
{
}

$mqtt->close();

function procmsg($topic,$msg)
{
  global $connection;
 //echo "$msg";
 $sql = "UPDATE manage SET value='$msg' WHERE id=1";
 $connection->query($sql);
}
?>