<?php
$botfilepath = "botdata.txt";
$delim = "|#|";

error_log("test log\r\n", 3, "bot.log", "");

// Open a botdata.txt file
$myfile = fopen($botfilepath, "r") or die("Unable to open file!");

$botwords = array();
//$botkeys = array();
//$botvalues = array();
$line = "";

while(!feof($myfile)) {
  $line = fgets($myfile);
  // Add each line into response dictionary
  $entry = explode($delim, $line);
  $botwords[trim($entry[0])] = trim($entry[1]);
  //$botkeys[] = $entry[0];
  //$botvalues[] = $entry[1];
  // Output one line until end-of-file
  //echo $line . "<br>";
}
fclose($myfile);

//print_r($botwords);
//echo "<br />";
//echo array_key_exists("tour", $botwords)."<br />";

//print_r($botkeys);
//print_r($botvalues);

//reset($botwords);
//reset($botkeys);

function getReply($message) {
  global $botwords;
  $response = "";
  //$response = $message . " ";
  
  //print_r($botwords);
  //var_dump($botwords);
  
  reset($botwords);
  //reset($botkeys);
  //reset($botvalues);
  
  //echo gettype($message);
  //echo gettype($botkeys[1]); // tour
  //var_dump($message);
  //echo "<br />"; 
  //var_dump(array_key_exists($message, $botwords));
  //var_dump(in_array($message, $botkeys));
  
  if (array_key_exists(strtolower($message), $botwords) == 1) {
    $response .= $botwords[$message];
  }
  else {
    $response .= "I will get back to you ASAP.";
  }
  
  return $response;
}

// echo getReply("tour") . "<br />";

//print_r(array_keys($botwords))."<br />";
//print_r(array_values($botwords))."<br />";
?>
