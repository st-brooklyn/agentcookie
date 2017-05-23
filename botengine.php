<?php
$botfile = "botdata.txt";
//Open a botdata.txt file
$myfile = fopen($botfile, "r") or die("Unable to open file!");

$botwords = array();
$line = "";

while(!feof($myfile)) {
  $line = fgets($myfile);
  // Add each line into response dictionary
  
  // Output one line until end-of-file
  echo $line . "<br>";
}
fclose($myfile);
?>
