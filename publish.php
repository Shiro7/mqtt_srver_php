<?php
require("./phpMQTT.php");
include 'db_get_status.php';

$mqtt = new phpMQTT("192.168.1.6", 1500, "pub"); 

$msg1 = $_POST['msg1'];
$msg2 = $_POST['msg2'];
$msg3 = $_POST['msg3'];
$i = 0;

if ($mqtt->connect()) 
{
  if($msg2)
  {    
    $status1 ='{"status": "'.$msg2.'" , "duration" :"'.$msg1.'"}' ;
    if($data != "{\"status\":\"ON\"}")
    {
      while ($i < 7)
      {
        $i++;
        usleep(500000);
        $mqtt->publish("presence",$status1,0);
      }
      usleep(2000000);
      include 'db_get_status.php';
      $x = $data ;     
      if ($x != "{\"status\":\"ON\"}")
      { 
        require("./db_connection.php");
        global $connection;
        $error = "{\"status\":\"Plz Send Again\"}";
        $sql = "UPDATE manage SET value= '$error' WHERE id=1";
        $connection->query($sql);
        header("Location:application.php");
      }
      else
      {
        $mqtt->close();
        header("Location:application.php");
      }
    }
  }
    elseif($msg3)
  {
    $status2 ='{"status": "'.$msg3.'" }' ;
    if($data != "{\"status\":\"OFF\"}")
    {
      while ($i < 7)
      {
        $i++;
        usleep(500000);
        $mqtt->publish("presence",$status2,0);
      }
      usleep(2000000);
      include 'db_get_status.php';
      $x = $data ;
      if ($x != "{\"status\":\"OFF\"}")
      {
        require("./db_connection.php");
        global $connection;
        $error = "{\"status\":\"Plz Send Again\"}";
        $sql = "UPDATE manage SET value= '$error' WHERE id=1";
        $connection->query($sql);
        header("Location:application.php");
      }
      else
      {
        $mqtt->close();
        header("Location:application.php");
      }
    }
  }
}
?>