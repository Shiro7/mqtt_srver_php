<?php

require("./phpMQTT.php");

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
    while ( $i < 100 && $_SESSION['button'] == "ON" )
    {
      $i++;
	     $mqtt->publish("presence",$status1,0);
    }
    if ($i >= 100)
          header("Location:www.google.com");

    $mqtt->close();
    header("Location:application.php");
  }
  elseif($msg3)
  {
  	$status2 ='{"status": "'.$msg3.'" }' ;
	$mqtt->publish("presence",$status2,0);
	$mqtt->close();
	header("Location:application.php");
  }
}

?>