<?php
$botfile = "botdata.txt";
$delim = "|#|";

// Open a botdata.txt file
$myfile = fopen($botfile, "r") or die("Unable to open file!");

$botwords = array();
$line = "";

while(!feof($myfile)) {
  $line = fgets($myfile);
  // Add each line into response dictionary
  $entry = explode($delim, $line);
  $botwords[$entry[0]] = $entry[1];
  // Output one line until end-of-file
  //echo $line . "<br>";
}
fclose($myfile);

//print_r($botwords);
//echo "<br />";
echo array_key_exists("tour", $botwords)."<br />";

function getReply($message) {
  $response = $message . " ";
  echo $message."<br />";
  echo array_key_exists($message, $botwords)."<br />"; 
  if (!!array_key_exists($message, $botwords)) {
    $response .= $botwords[$message];
  }
  else {
    $response .= "I will get back to you ASAP.";
  }
  
  return $response;
}

echo getReply("tour") . "<br />";

print_r(array_keys($botwords))."<br />";
print_r(array_values($botwords))."<br />";
?>
