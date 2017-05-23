<?php
$myfile = fopen("botdata.txt", "r") or die("Unable to open file!");
echo fread($myfile,filesize("botdata.txt"));
fclose($myfile);
?>
