<?php
require("./phpMQTT.php");
include 'db_get_status.php';

$mqtt = new phpMQTT("192.168.1.2", 1500, "pub"); 

$msg1 = $_POST['msg1'];
$msg2 = $_POST['msg2'];
$msg3 = $_POST['msg3'];

$from_date = $_POST['from_date'];
$from_time = $_POST['from_time'];
$to_date = $_POST['to_date'];
$to_time = $_POST['to_time'];
$to_sec  = $_POST['to_sec'];

$i = 0;

$from_date = explode('-', $from_date);
$from_year = $from_date[0];
$from_month= $from_date[1];
$from_day  = $from_date[2];

$from_time = explode(':', $from_time);
$from_hour = $from_time[0];
$from_min  = $from_time[1];

$to_date = explode('-', $to_date);
$to_year = $to_date[0];
$to_month= $to_date[1];
$to_day  = $to_date[2];

$to_time = explode(':', $to_time);
$to_hour = $to_time[0];
$to_min  = $to_time[1];

if ($mqtt->connect()) 
{
  if($msg2)
  {    
    $status1 ='{"s":"'.$msg2.'","a":'.$from_year.',"b":'.$from_month.',"e":'.$from_year.',"f":'.$from_month.',"g":'.$from_day.',"h":'.$from_hour.',"i":'.$from_min.',"j":'.$to_year.',"k":'.$to_month.',"l":'.$to_day.',"m":'.$to_hour.',"n":'.$to_min.',"o":'.$to_sec.'}';

    if($data != "{\"status\":\"ON\"}")
    {
      while ($i < 4)
      {
        $i++;
        usleep(500000);
        $mqtt->publish("presence",$status1,0);
      }
      usleep(2000000);
      include 'db_get_status.php';
      $x = $data ; 
      if ($x == "{\"status\":\"Order Saved\"}") 
      {
        $mqtt->close();
        header("Location:application.php");
      }    
      elseif ($x != "{\"status\":\"ON\"}")
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
    $status2 ='{"s": "'.$msg3.'" }' ;
    if($data != "{\"status\":\"OFF\"}")
    {
      while ($i < 4)
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